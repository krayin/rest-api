import { defineConfig } from '@playwright/test';
import 'dotenv/config';
import * as path from 'path';

const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
const reportDir = path.join(__dirname, 'reports', timestamp);

export default defineConfig({
  testDir: './tests/api',

  /**
   * Krayin's login revokes every existing token for a user, so two workers
   * authenticating as the same account invalidate each other mid-run. Until the
   * suite provisions a user per worker, it stays single-worker and serial.
   */
  fullyParallel: false,
  workers: 1,

  forbidOnly: !! process.env.CI,

  /**
   * One retry, for infrastructure flakiness only — a functional failure must
   * stay red rather than be retried into a pass.
   */
  retries: process.env.CI ? 1 : 0,

  reporter: [
    ['html', { outputFolder: path.join(reportDir, 'html'), open: 'never' }],
    ['json', { outputFile: path.join(reportDir, 'results.json') }],
    ['junit', { outputFile: path.join(reportDir, 'results.xml') }],
    ['list'],
  ],

  use: {
    baseURL: process.env.APP_URL,
    extraHTTPHeaders: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
    trace: 'retain-on-failure',
  },

  projects: [{ name: 'api' }],

  timeout: Number(process.env.TEST_TIMEOUT ?? 30_000),

  expect: {
    timeout: 5_000,
  },
});
