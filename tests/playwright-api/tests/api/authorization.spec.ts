import { test, expect, hasLimitedUser, expectStatus, expectNoSensitiveData, expectJsonResponse } from '../../fixtures/api.fixture';

/**
 * Authentication (401) and authorization (403) coverage.
 *
 * The 403 specs need a second, low-permission account; they skip themselves
 * when TEST_LIMITED_USER_EMAIL / TEST_LIMITED_USER_PASSWORD are not configured
 * rather than failing the run.
 */
test.describe('Authentication', () => {
  test('@smoke @auth @negative @regression a protected endpoint rejects an anonymous request', async ({ apiClient }) => {
    await expectStatus(await apiClient.get('/api/v1/leads'), 401);
  });

  test('@auth @negative @regression a malformed bearer token is rejected', async ({ apiClient }) => {
    await expectStatus(
      await apiClient.get('/api/v1/leads', { headers: { Authorization: 'Bearer not-a-real-token' } }),
      401,
    );
  });

  test('@auth @security @regression the account endpoint never exposes credentials', async ({ authedApi }) => {
    const response = await authedApi.get('/api/v1/get');

    await expectJsonResponse(response, 200);
    await expectNoSensitiveData(response);
  });

  test('@security @regression a user listing never exposes password hashes', async ({ userService }) => {
    const response = await userService.list();

    await expectJsonResponse(response, 200);
    await expectNoSensitiveData(response);
  });
});

test.describe('Authorization', () => {
  test.skip(() => ! hasLimitedUser(), 'TEST_LIMITED_USER_* is not configured');

  test('@authorization @negative @regression a low-permission user cannot list users', async ({ limitedApi }) => {
    const response = await limitedApi.get('/api/v1/settings/users');

    expect([401, 403], `expected the limited user to be denied, got ${response.status()}`).toContain(response.status());
  });

  test('@authorization @negative @regression a low-permission user cannot delete a lead owned by someone else', async ({
    leadService,
    limitedApi,
    cleanup,
  }) => {
    const created = await expectJsonResponse(
      await leadService.create({
        title: `Lead ${Date.now()}`,
        description: 'Authorization fixture',
        lead_value: 100,
        person: { name: `Person ${Date.now()}`, emails: [{ value: `qa${Date.now()}@example.test`, label: 'work' }] },
        lead_source_id: 1,
        lead_type_id: 1,
        lead_pipeline_id: 1,
        lead_pipeline_stage_id: 1,
      }),
      200,
    );

    cleanup.track('leads', created.data.id);

    const response = await limitedApi.delete(`/api/v1/leads/${created.data.id}`);

    expect([401, 403, 404], `expected the delete to be denied, got ${response.status()}`).toContain(response.status());
  });
});
