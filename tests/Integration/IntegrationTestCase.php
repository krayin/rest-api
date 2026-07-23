<?php

namespace Webkul\RestApi\Tests\Integration;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * Base class for black-box integration tests that exercise the REST API against
 * a REAL, running Krayin CRM (this package installed on top of it) — as opposed
 * to the Feature/Unit suites, which boot only the package under Orchestra
 * Testbench and never touch the CRM's models, database or controllers.
 *
 * These tests drive the plugin exactly the way a real API consumer does: over
 * HTTP, with a Sanctum bearer token, hitting the actual `api/v1/*` endpoints of
 * a live instance. That is the only way to prove the fixes in this fork behave
 * end-to-end (real controllers, real repositories, real Krayin attribute
 * validation), which the package-only harness cannot.
 *
 * The whole suite is INERT unless it is told where to run. Configure via env:
 *
 *   KRAYIN_BASE_URL       e.g. https://crm.example.com  (required to run)
 *   KRAYIN_API_EMAIL      an admin user's email         (required to run)
 *   KRAYIN_API_PASSWORD   that user's password          (required to run)
 *
 * With KRAYIN_BASE_URL unset every test is skipped, so the default
 * `vendor/bin/phpunit` run (and the package CI matrix) stays green without a
 * CRM. The dedicated integration CI job provisions a throwaway Krayin, sets
 * these vars and runs `--testsuite Integration`.
 */
abstract class IntegrationTestCase extends TestCase
{
    private ?Client $client = null;

    /**
     * Cached bearer token, shared across tests in the process so we log in once.
     */
    private static ?string $token = null;

    /**
     * Delete callbacks for resources created during a test, run on tearDown so
     * the suite is self-cleaning and can run repeatedly against the same
     * instance without leaving junk behind.
     *
     * @var array<int, callable>
     */
    private array $cleanup = [];

    protected function setUp(): void
    {
        parent::setUp();

        if (! self::baseUrl()) {
            $this->markTestSkipped(
                'Integration tests skipped: set KRAYIN_BASE_URL (+ KRAYIN_API_EMAIL / '
                .'KRAYIN_API_PASSWORD) to run them against a live Krayin instance.'
            );
        }
    }

    protected function tearDown(): void
    {
        foreach (array_reverse($this->cleanup) as $callback) {
            try {
                $callback();
            } catch (\Throwable $e) {
                // Best-effort cleanup; never fail a test because teardown could
                // not delete a record.
            }
        }

        $this->cleanup = [];

        parent::tearDown();
    }

    // -- Configuration ------------------------------------------------------

    protected static function baseUrl(): ?string
    {
        $url = getenv('KRAYIN_BASE_URL') ?: null;

        return $url ? rtrim($url, '/') : null;
    }

    protected static function email(): string
    {
        return getenv('KRAYIN_API_EMAIL') ?: 'admin@example.com';
    }

    protected static function password(): string
    {
        return getenv('KRAYIN_API_PASSWORD') ?: 'admin123';
    }

    // -- HTTP plumbing ------------------------------------------------------

    private function client(): Client
    {
        return $this->client ??= new Client([
            'base_uri'    => self::baseUrl().'/',
            'http_errors' => false,
            'timeout'     => 30,
            'headers'     => ['Accept' => 'application/json'],
        ]);
    }

    /**
     * Perform a request and return a normalized [status, json] pair.
     *
     * @param  array<string, mixed>|null  $data  JSON body (for POST/PUT).
     * @return array{status:int, json:array<mixed>}
     */
    protected function request(string $method, string $path, ?array $data = null, bool $auth = true): array
    {
        $result = $this->sendRequest($method, $path, $data, $auth);

        /**
         * Krayin's login revokes ALL of a user's existing tokens
         * (`$user->tokens()->delete()`), so the process-wide cached bearer token
         * gets invalidated the moment any test performs a fresh login. When an
         * authenticated call comes back 401, transparently drop the cached token,
         * re-login and retry once — this makes the suite immune to test ordering
         * (a genuinely unauthorized call still fails on the retry).
         */
        if ($auth && $result['status'] === 401) {
            self::$token = null;

            $result = $this->sendRequest($method, $path, $data, $auth);
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>|null  $data
     * @return array{status:int, json:array<mixed>}
     */
    private function sendRequest(string $method, string $path, ?array $data, bool $auth): array
    {
        $options = [];

        if ($data !== null) {
            $options['json'] = $data;
        }

        if ($auth) {
            $options['headers']['Authorization'] = 'Bearer '.$this->token();
        }

        $response = $this->client()->request($method, ltrim($path, '/'), $options);

        $body = (string) $response->getBody();
        $json = json_decode($body, true);

        return [
            'status' => $response->getStatusCode(),
            'json'   => is_array($json) ? $json : [],
        ];
    }

    /** @return array{status:int, json:array<mixed>} */
    protected function get(string $path, bool $auth = true): array
    {
        return $this->request('GET', $path, null, $auth);
    }

    /** @return array{status:int, json:array<mixed>} */
    protected function post(string $path, array $data, bool $auth = true): array
    {
        return $this->request('POST', $path, $data, $auth);
    }

    /** @return array{status:int, json:array<mixed>} */
    protected function put(string $path, array $data, bool $auth = true): array
    {
        return $this->request('PUT', $path, $data, $auth);
    }

    /** @return array{status:int, json:array<mixed>} */
    protected function delete(string $path, bool $auth = true): array
    {
        return $this->request('DELETE', $path, null, $auth);
    }

    // -- Auth ---------------------------------------------------------------

    /**
     * Log in once (per process) and return the bearer token.
     */
    protected function token(): string
    {
        if (self::$token !== null) {
            return self::$token;
        }

        $response = $this->request('POST', 'api/v1/login', [
            'email'       => self::email(),
            'password'    => self::password(),
            'device_name' => 'integration-tests',
        ], auth: false);

        $token = $this->extractToken($response['json']);

        $this->assertNotNull(
            $token,
            'Could not obtain a Sanctum token from POST /api/v1/login. '
            .'Response: '.json_encode($response)
        );

        return self::$token = $token;
    }

    /**
     * The login endpoint has historically returned the token either at the top
     * level or nested under `data`; accept both so the suite is resilient to
     * that shape.
     *
     * @param  array<mixed>  $json
     */
    private function extractToken(array $json): ?string
    {
        if (isset($json['token']) && is_string($json['token'])) {
            return $json['token'];
        }

        if (isset($json['data']['token']) && is_string($json['data']['token'])) {
            return $json['data']['token'];
        }

        return null;
    }

    // -- Helpers ------------------------------------------------------------

    /**
     * Register a path to DELETE during tearDown (self-cleaning tests).
     */
    protected function deleteOnTearDown(string $path): void
    {
        $this->cleanup[] = fn () => $this->delete($path);
    }

    /**
     * A value unique to this test run, to avoid collisions with existing data
     * and across repeated runs against the same instance.
     */
    protected function unique(string $prefix = ''): string
    {
        return $prefix.uniqid('it_', true);
    }

    /**
     * Assert an error message is human-facing text, not a raw translation key
     * (e.g. "rest-api::app.common.unauthenticated") leaking through — one of the
     * things this fork fixes.
     *
     * @param  array<mixed>  $json
     */
    protected function assertHumanMessage(array $json): void
    {
        $this->assertArrayHasKey('message', $json, 'Error response has no "message".');
        $this->assertIsString($json['message']);
        $this->assertStringNotContainsString(
            '::',
            $json['message'],
            'Message looks like an unresolved translation key: '.$json['message']
        );
    }
}
