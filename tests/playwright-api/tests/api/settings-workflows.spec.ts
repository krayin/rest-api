import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Settings Workflows API', () => {
  test('@auth @crud @negative @regression list workflows without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/settings/workflows');
    await logResponseOnFailure(response, 'list workflows without token');
    expect(response.status()).toBe(401);
  });

  test('@crud @regression list workflows', async ({ workflowService }) => {
    const response = await workflowService.list();
    await logResponseOnFailure(response, 'list workflows');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression create workflow', async ({ workflowService }) => {
    const response = await workflowService.create({ name: unique('Workflow') });
    await logResponseOnFailure(response, 'create workflow');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@validation @crud @negative @regression create workflow with empty payload', async ({ workflowService }) => {
    const response = await workflowService.create({});
    await logResponseOnFailure(response, 'create workflow with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show workflow', async ({ workflowService }) => {
    const createResponse = await workflowService.create({ name: unique('Workflow') });
    const { data } = await createResponse.json();

    const response = await workflowService.getById(data.id);
    await logResponseOnFailure(response, 'show workflow');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(data.id);
  });

  test('@crud @negative @regression show workflow not found', async ({ workflowService }) => {
    const response = await workflowService.getById(999999);
    await logResponseOnFailure(response, 'show workflow not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update workflow', async ({ workflowService }) => {
    const createResponse = await workflowService.create({ name: unique('Workflow') });
    const { data } = await createResponse.json();

    const response = await workflowService.update(data.id, { name: unique('Workflow') });
    await logResponseOnFailure(response, 'update workflow');
    expect(response.status()).toBe(200);
  });

  test('@validation @crud @negative @regression update workflow with empty payload', async ({ workflowService }) => {
    const createResponse = await workflowService.create({ name: unique('Workflow') });
    const { data } = await createResponse.json();

    const response = await workflowService.update(data.id, {});
    await logResponseOnFailure(response, 'update workflow with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression delete workflow', async ({ workflowService }) => {
    const createResponse = await workflowService.create({ name: unique('Workflow') });
    const { data } = await createResponse.json();

    const response = await workflowService.delete(data.id);
    await logResponseOnFailure(response, 'delete workflow');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression delete workflow not found', async ({ workflowService }) => {
    const response = await workflowService.delete(999999);
    await logResponseOnFailure(response, 'delete workflow not found');
    expect(response.status()).toBe(404);
  });

  test('@negative @regression wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/settings/workflows/1', { data: {} });
    await logResponseOnFailure(response, 'wrong http method');
    expect(response.status()).toBe(405);
  });
});
