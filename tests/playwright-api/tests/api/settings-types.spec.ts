import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Settings Types API', () => {
  test('@auth @crud @negative @regression list types without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/settings/types');
    await logResponseOnFailure(response, 'list types without token');
    expect(response.status()).toBe(401);
  });

  test('@crud @regression list types', async ({ typeService }) => {
    const response = await typeService.list();
    await logResponseOnFailure(response, 'list types');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression create type', async ({ typeService }) => {
    const response = await typeService.create({ name: unique('Type') });
    await logResponseOnFailure(response, 'create type');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@validation @crud @negative @regression create type with empty payload', async ({ typeService }) => {
    const response = await typeService.create({});
    await logResponseOnFailure(response, 'create type with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show type', async ({ typeService }) => {
    const createResponse = await typeService.create({ name: unique('Type') });
    const { data } = await createResponse.json();

    const response = await typeService.getById(data.id);
    await logResponseOnFailure(response, 'show type');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(data.id);
  });

  test('@crud @negative @regression show type not found', async ({ typeService }) => {
    const response = await typeService.getById(999999);
    await logResponseOnFailure(response, 'show type not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update type', async ({ typeService }) => {
    const createResponse = await typeService.create({ name: unique('Type') });
    const { data } = await createResponse.json();

    const response = await typeService.update(data.id, { name: unique('Type') });
    await logResponseOnFailure(response, 'update type');
    expect(response.status()).toBe(200);
  });

  test('@validation @crud @negative @regression update type with empty payload', async ({ typeService }) => {
    const createResponse = await typeService.create({ name: unique('Type') });
    const { data } = await createResponse.json();

    const response = await typeService.update(data.id, {});
    await logResponseOnFailure(response, 'update type with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression delete type', async ({ typeService }) => {
    const createResponse = await typeService.create({ name: unique('Type') });
    const { data } = await createResponse.json();

    const response = await typeService.delete(data.id);
    await logResponseOnFailure(response, 'delete type');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression delete type not found', async ({ typeService }) => {
    const response = await typeService.delete(999999);
    await logResponseOnFailure(response, 'delete type not found');
    expect(response.status()).toBe(404);
  });

  test('@negative @regression wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/settings/types/1', { data: {} });
    await logResponseOnFailure(response, 'wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@crud @negative @regression issue133: show type with invalid id', async ({ typeService }) => {
    const response = await typeService.getById(999999);
    await logResponseOnFailure(response, 'issue133: show type with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue134: update type with invalid id', async ({ typeService }) => {
    const response = await typeService.update(999999, { name: unique('Type') });
    await logResponseOnFailure(response, 'issue134: update type with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue135: delete type with invalid id', async ({ typeService }) => {
    const response = await typeService.delete(999999);
    await logResponseOnFailure(response, 'issue135: delete type with invalid id');
    expect(response.status()).toBe(404);
  });
});
