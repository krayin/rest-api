import { test, expect, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Dashboard API', () => {
  test('@auth @negative @regression dashboard stats without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/dashboard/stats');
    await logResponseOnFailure(response, 'dashboard stats without token');
    expect(response.status()).toBe(401);
  });

  test('@crud @regression dashboard stats success', async ({ authedApi }) => {
    const response = await authedApi.get('/api/v1/dashboard/stats');
    await logResponseOnFailure(response, 'dashboard stats success');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.total_leads).toBeTruthy();
    expect(body.data.total_quotes).toBeTruthy();
    expect(body.data.total_activities).toBeTruthy();
    expect(body.data.total_persons).toBeTruthy();
    expect(body.data.total_organizations).toBeTruthy();
    expect(body.data.total_products).toBeTruthy();
  });

  test('@negative @regression dashboard stats with wrong http method', async ({ authedApi }) => {
    const response = await authedApi.post('/api/v1/dashboard/stats', { data: {} });
    await logResponseOnFailure(response, 'dashboard stats with wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@auth @negative @regression dashboard stats with invalid token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/dashboard/stats', {
      headers: { Authorization: 'Bearer invalid-token-123' },
    });
    await logResponseOnFailure(response, 'dashboard stats with invalid token');
    expect(response.status()).toBe(401);
  });
});
