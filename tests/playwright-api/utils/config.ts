import 'dotenv/config';

/**
 * Read a required environment variable, failing loudly and early rather than
 * letting a spec run against a silent default. Credentials and base URLs are
 * never hardcoded in the suite.
 */
function requireEnv(name: string): string {
  const value = process.env[name];

  if (! value) {
    throw new Error(
      `Missing required environment variable "${name}". Copy .env.example to .env and fill it in, or export it in CI.`,
    );
  }

  return value;
}

/**
 * Optional variable — used for the credentials that only some suites need
 * (the low-permission user drives the 403/ACL specs).
 */
function optionalEnv(name: string): string | undefined {
  return process.env[name] || undefined;
}

export const config = {
  baseUrl: requireEnv('APP_URL').replace(/\/+$/, ''),

  deviceName: process.env.TEST_DEVICE_NAME || 'playwright-api-tests',

  testUser: {
    email: requireEnv('TEST_USER_EMAIL'),
    password: requireEnv('TEST_USER_PASSWORD'),
  },

  /**
   * Low-permission user for authorization (403) coverage. Specs that need it
   * must skip themselves when it is not configured — see `hasLimitedUser`.
   */
  limitedUser: {
    email: optionalEnv('TEST_LIMITED_USER_EMAIL'),
    password: optionalEnv('TEST_LIMITED_USER_PASSWORD'),
  },

  timeouts: {
    short: 5000,
    medium: 10000,
    long: Number(process.env.TEST_TIMEOUT ?? 30000),
  },
};

export const hasLimitedUser = (): boolean =>
  Boolean(config.limitedUser.email && config.limitedUser.password);

export default config;
