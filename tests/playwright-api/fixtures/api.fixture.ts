import { test as base, expect, APIResponse, request as playwrightRequest } from '@playwright/test';
import { ApiClient, unique, uniqueNumber, waitForNextSecond } from '../api/ApiClient';
import { config, hasLimitedUser } from '../utils/config';
import { Cleanup } from '../utils/cleanup';
import {
  AuthService,
  LeadService,
  PersonService,
  OrganizationService,
  ProductService,
  QuoteService,
  ActivityService,
  MailService,
  TagService,
  RoleService,
  PipelineService,
  SourceService,
  TypeService,
  UserService,
  WarehouseService,
  GroupService,
  AttributeService,
  WebhookService,
  WorkflowService,
  EmailTemplateService,
  WebFormService,
  MarketingEventService,
  MarketingCampaignService,
} from '../services';

const SECRET_KEYS = /password|token|secret|api[-_]?key|authorization/i;

const redact = (value: unknown): unknown => {
  if (Array.isArray(value)) {
    return value.map(redact);
  }

  if (value && typeof value === 'object') {
    return Object.fromEntries(
      Object.entries(value as Record<string, unknown>).map(([key, entry]) => [
        key,
        SECRET_KEYS.test(key) ? '***' : redact(entry),
      ]),
    );
  }

  return value;
};

/**
 * Print enough context to debug a failed call, with every secret redacted.
 * Prefer the helpers in `utils/assertions.ts` — they attach this same context
 * to the assertion message itself instead of only the console.
 */
async function logResponseOnFailure(response: APIResponse, testName: string) {
  if (response.status() >= 400) {
    const body = await response.json().catch(() => null);

    console.log(`\n${'='.repeat(80)}`);
    console.log(`FAILED: ${testName}`);
    console.log(`URL: ${response.url()}`);
    console.log(`Status: ${response.status()} ${response.statusText()}`);
    console.log('Response Body:', JSON.stringify(redact(body), null, 2));
    console.log(`${'='.repeat(80)}\n`);
  }
}

/**
 * Mint a token for the given credentials on a throwaway context.
 *
 * Krayin's login revokes every existing token for the user, so two workers
 * logging in as the same account would invalidate each other — the suite runs
 * with a single worker (see playwright.config.ts) for that reason.
 */
async function tokenFor(email: string, password: string): Promise<string> {
  const context = await playwrightRequest.newContext({ baseURL: config.baseUrl });

  try {
    const response = await context.post('/api/v1/login', {
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      data: { email, password, device_name: config.deviceName },
    });

    if (! response.ok()) {
      throw new Error(
        `Login failed (${response.status()}) for the configured test user — check the TEST_USER_* environment variables.`,
      );
    }

    const token = (await response.json()).token;

    if (! token) {
      throw new Error('Login succeeded but no token was returned.');
    }

    return token;
  } finally {
    await context.dispose();
  }
}

type ApiFixtures = {
  /** No Authorization header — drives the 401 specs. */
  apiClient: ApiClient;
  authedApi: ApiClient;
  apiToken: string;
  /** Authenticated as the low-permission user — drives the 403 specs. */
  limitedApi: ApiClient;
  /** Resources registered here are deleted after the test, pass or fail. */
  cleanup: Cleanup;
  authService: AuthService;
  leadService: LeadService;
  personService: PersonService;
  organizationService: OrganizationService;
  productService: ProductService;
  quoteService: QuoteService;
  activityService: ActivityService;
  mailService: MailService;
  tagService: TagService;
  roleService: RoleService;
  pipelineService: PipelineService;
  sourceService: SourceService;
  typeService: TypeService;
  userService: UserService;
  warehouseService: WarehouseService;
  groupService: GroupService;
  attributeService: AttributeService;
  webhookService: WebhookService;
  workflowService: WorkflowService;
  emailTemplateService: EmailTemplateService;
  webFormService: WebFormService;
  marketingEventService: MarketingEventService;
  marketingCampaignService: MarketingCampaignService;
};

export const test = base.extend<ApiFixtures>({
  apiClient: async ({ request }, use) => {
    const client = new ApiClient(request, { baseUrl: config.baseUrl });
    await use(client);
  },

  apiToken: async ({}, use) => {
    await use(await tokenFor(config.testUser.email, config.testUser.password));
  },

  authedApi: async ({ request, apiToken }, use) => {
    const client = new ApiClient(request, {
      baseUrl: config.baseUrl,
      token: apiToken,
    });
    await use(client);
  },

  limitedApi: async ({ request }, use) => {
    if (! hasLimitedUser()) {
      throw new Error(
        'The limited-permission user is not configured. Set TEST_LIMITED_USER_EMAIL and TEST_LIMITED_USER_PASSWORD, or guard the test with `test.skip(! hasLimitedUser())`.',
      );
    }

    const token = await tokenFor(config.limitedUser.email!, config.limitedUser.password!);

    await use(new ApiClient(request, { baseUrl: config.baseUrl, token }));
  },

  cleanup: async ({ authedApi }, use) => {
    const cleanup = new Cleanup(authedApi);

    await use(cleanup);

    await cleanup.run();
  },

  authService: async ({ apiClient }, use) => {
    await use(new AuthService(apiClient));
  },

  leadService: async ({ authedApi }, use) => {
    await use(new LeadService(authedApi));
  },

  personService: async ({ authedApi }, use) => {
    await use(new PersonService(authedApi));
  },

  organizationService: async ({ authedApi }, use) => {
    await use(new OrganizationService(authedApi));
  },

  productService: async ({ authedApi }, use) => {
    await use(new ProductService(authedApi));
  },

  quoteService: async ({ authedApi }, use) => {
    await use(new QuoteService(authedApi));
  },

  activityService: async ({ authedApi }, use) => {
    await use(new ActivityService(authedApi));
  },

  mailService: async ({ authedApi }, use) => {
    await use(new MailService(authedApi));
  },

  tagService: async ({ authedApi }, use) => {
    await use(new TagService(authedApi));
  },

  roleService: async ({ authedApi }, use) => {
    await use(new RoleService(authedApi));
  },

  pipelineService: async ({ authedApi }, use) => {
    await use(new PipelineService(authedApi));
  },

  sourceService: async ({ authedApi }, use) => {
    await use(new SourceService(authedApi));
  },

  typeService: async ({ authedApi }, use) => {
    await use(new TypeService(authedApi));
  },

  userService: async ({ authedApi }, use) => {
    await use(new UserService(authedApi));
  },

  warehouseService: async ({ authedApi }, use) => {
    await use(new WarehouseService(authedApi));
  },

  groupService: async ({ authedApi }, use) => {
    await use(new GroupService(authedApi));
  },

  attributeService: async ({ authedApi }, use) => {
    await use(new AttributeService(authedApi));
  },

  webhookService: async ({ authedApi }, use) => {
    await use(new WebhookService(authedApi));
  },

  workflowService: async ({ authedApi }, use) => {
    await use(new WorkflowService(authedApi));
  },

  emailTemplateService: async ({ authedApi }, use) => {
    await use(new EmailTemplateService(authedApi));
  },

  webFormService: async ({ authedApi }, use) => {
    await use(new WebFormService(authedApi));
  },

  marketingEventService: async ({ authedApi }, use) => {
    await use(new MarketingEventService(authedApi));
  },

  marketingCampaignService: async ({ authedApi }, use) => {
    await use(new MarketingCampaignService(authedApi));
  },
});

export { expect } from '@playwright/test';
export { unique, uniqueNumber, waitForNextSecond, logResponseOnFailure };
export { config, hasLimitedUser } from '../utils/config';
export { Cleanup } from '../utils/cleanup';
export * from '../utils/assertions';
