<?php

namespace Webkul\RestApi\Tests\Integration;

/**
 * End-to-end authentication behaviour against a live Krayin instance: the
 * Sanctum login flow, and the guarantee that unauthenticated / bad-token
 * requests get a clean 401 JSON (never a 500 "Route [login] not defined", nor
 * an HTML redirect) — the guest-auth fix in this fork, proven through the real
 * middleware stack rather than in isolation.
 */
class AuthIntegrationTest extends IntegrationTestCase
{
    public function test_login_with_valid_credentials_returns_a_token(): void
    {
        $response = $this->request('POST', 'api/v1/login', [
            'email'       => self::email(),
            'password'    => self::password(),
            'device_name' => 'integration-tests',
        ], auth: false);

        $this->assertSame(200, $response['status']);
        // token() encapsulates extraction; asserting it is non-empty proves the
        // shape is usable by a consumer.
        $this->assertNotSame('', $this->token());
    }

    public function test_login_with_wrong_password_is_rejected(): void
    {
        $response = $this->request('POST', 'api/v1/login', [
            'email'       => self::email(),
            'password'    => 'definitely-not-the-password',
            'device_name' => 'integration-tests',
        ], auth: false);

        // Wrong credentials must not hand out a token; 401 (auth failed) or 422
        // (validation) are both acceptable, but never 2xx.
        $this->assertContains($response['status'], [401, 422], 'Bad password should be rejected.');
        $this->assertArrayNotHasKey('token', $response['json']);
    }

    public function test_missing_credentials_return_422_validation_error(): void
    {
        $response = $this->request('POST', 'api/v1/login', [
            'device_name' => 'integration-tests',
        ], auth: false);

        $this->assertSame(422, $response['status']);
        $this->assertArrayHasKey('errors', $response['json']);
        $this->assertHumanMessage($response['json']);
    }

    public function test_request_without_token_returns_401_json(): void
    {
        $response = $this->get('api/v1/leads', auth: false);

        $this->assertSame(401, $response['status']);
        $this->assertHumanMessage($response['json']);
    }

    public function test_request_with_invalid_token_returns_401_json(): void
    {
        $response = $this->request('GET', 'api/v1/leads', null, auth: false);
        // Re-issue with a bogus bearer token explicitly.
        $response = $this->requestWithRawToken('999|totally-invalid-token');

        $this->assertSame(401, $response['status']);
        $this->assertHumanMessage($response['json']);
    }

    /**
     * @return array{status:int, json:array<mixed>}
     */
    private function requestWithRawToken(string $token): array
    {
        $client = new \GuzzleHttp\Client([
            'base_uri'    => self::baseUrl().'/',
            'http_errors' => false,
            'timeout'     => 30,
            'headers'     => ['Accept' => 'application/json'],
        ]);

        $response = $client->request('GET', 'api/v1/leads', [
            'headers' => ['Authorization' => 'Bearer '.$token],
        ]);

        $json = json_decode((string) $response->getBody(), true);

        return [
            'status' => $response->getStatusCode(),
            'json'   => is_array($json) ? $json : [],
        ];
    }
}
