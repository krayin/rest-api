# Krayin REST API — Playwright Automation

Executable API automation for the Krayin CRM REST module (`/api/v1`), built on
`@playwright/test` and `APIRequestContext`.

## Layout

```
api/ApiClient.ts          HTTP client: base URL, auth header, JSON defaults
services/                 One service per resource — no raw requests in specs
fixtures/api.fixture.ts   Auth contexts, services and per-test cleanup
schemas/api-schemas.ts    Zod response contracts (envelopes + resources)
utils/assertions.ts       Status + header + schema assertions with redacted debug output
utils/cleanup.ts          LIFO cleanup registry
utils/test-data.ts        Unique, parallel-safe payload factories and builders
utils/config.ts           Environment configuration (fails fast when unset)
tests/api/                The specs
```

## Setup

```bash
cp .env.example .env      # then fill it in
npm ci
npx playwright install chromium --with-deps
```

### Environment variables

Nothing is hardcoded — the suite refuses to start if a required variable is missing.

| Variable | Required | Purpose |
|---|---|---|
| `APP_URL` | yes | Base URL of the Krayin app with the REST API installed |
| `TEST_USER_EMAIL` | yes | Admin account used by most specs |
| `TEST_USER_PASSWORD` | yes | Password for that account |
| `TEST_LIMITED_USER_EMAIL` | no | Low-permission account for the 403 specs |
| `TEST_LIMITED_USER_PASSWORD` | no | Password for that account |
| `TEST_DEVICE_NAME` | no | Sanctum token label (default `playwright-api-tests`) |
| `TEST_TIMEOUT` | no | Per-test timeout in ms (default `30000`) |

The authorization specs skip themselves when the limited user is not configured.

## Running

```bash
npm test                  # everything
npm run test:smoke        # core happy paths — the PR gate
npm run test:regression   # full regression
npm run test:crud
npm run test:negative
npm run test:validation
npm run test:auth
npm run test:security
npm run typecheck         # TypeScript, no tests executed
npm run test:report       # open the last HTML report
```

Arbitrary filters work too:

```bash
npx playwright test --grep "@pagination|@acid"
npx playwright test --grep-invert @security
```

## Tags

Every test carries at least one tag plus `@regression`.

`@smoke` `@regression` `@crud` `@negative` `@auth` `@authorization`
`@validation` `@pagination` `@security` `@acid`

## Conventions

- **Services only.** Specs never call `request.post()` or touch a URL directly;
  every endpoint gets a named service method.
- **A 200 is not a pass.** Use the helpers in `utils/assertions.ts` — they assert
  status *and* content type *and* schema, and on failure print the endpoint,
  payload and body with every secret redacted.
- **No secrets in output.** Tokens, passwords and API keys are never logged, not
  even in failure messages.
- **Self-cleaning.** Register anything you create with the `cleanup` fixture;
  it runs LIFO after the test whether it passed or failed.
- **Unique data.** Build payloads with `utils/test-data.ts`; never a fixed id.

### Parallelism

Krayin's `login` revokes every existing token for the user, so two workers
authenticating as the same account invalidate each other mid-run. The suite
therefore runs single-worker and serial. Raising `workers` requires provisioning
a distinct API user per worker first.

## CI

`.github/workflows/playwright-api-tests.yml` installs Krayin, registers this
repository as the REST API module, creates a throwaway admin with a per-run
generated password, type-checks the suite, runs `@smoke` as a gate and then the
full `@regression` suite. Reports upload as an artifact on every run.
