import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Settings Roles API', () => {
  test('@auth @crud @negative @regression list roles without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/settings/roles');
    await logResponseOnFailure(response, 'list roles without token');
    expect(response.status()).toBe(401);
  });

  test('@crud @regression list roles', async ({ roleService }) => {
    const response = await roleService.list();
    await logResponseOnFailure(response, 'list roles');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression create role', async ({ roleService }) => {
    const response = await roleService.create({
      name: unique('Role'),
      permission_type: 'all',
    });
    await logResponseOnFailure(response, 'create role');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@validation @crud @negative @regression create role with empty payload', async ({ roleService }) => {
    const response = await roleService.create({});
    await logResponseOnFailure(response, 'create role with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show role', async ({ roleService }) => {
    const createResponse = await roleService.create({
      name: unique('Role'),
      permission_type: 'all',
    });
    const { data } = await createResponse.json();

    const response = await roleService.getById(data.id);
    await logResponseOnFailure(response, 'show role');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(data.id);
  });

  test('@crud @negative @regression show role not found', async ({ roleService }) => {
    const response = await roleService.getById(999999);
    await logResponseOnFailure(response, 'show role not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update role', async ({ roleService }) => {
    const createResponse = await roleService.create({
      name: unique('Role'),
      permission_type: 'all',
    });
    const { data } = await createResponse.json();

    const response = await roleService.update(data.id, {
      name: unique('Role'),
      permission_type: 'all',
    });
    await logResponseOnFailure(response, 'update role');
    expect(response.status()).toBe(200);
  });

  test('@validation @crud @negative @regression update role with empty payload', async ({ roleService }) => {
    const createResponse = await roleService.create({
      name: unique('Role'),
      permission_type: 'all',
    });
    const { data } = await createResponse.json();

    const response = await roleService.update(data.id, {});
    await logResponseOnFailure(response, 'update role with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression delete role', async ({ roleService }) => {
    const createResponse = await roleService.create({
      name: unique('Role'),
      permission_type: 'all',
    });
    const { data } = await createResponse.json();

    const response = await roleService.delete(data.id);
    await logResponseOnFailure(response, 'delete role');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression delete role not found', async ({ roleService }) => {
    const response = await roleService.delete(999999);
    await logResponseOnFailure(response, 'delete role not found');
    expect(response.status()).toBe(404);
  });

  test('@negative @regression wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/settings/roles/1', { data: {} });
    await logResponseOnFailure(response, 'wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@authorization @validation @crud @negative @regression issue87: create role with invalid permissions', async ({ roleService }) => {
    const response = await roleService.create({
      name: unique('Role'),
      permissions: { 'bogus.permission.key': 1 },
    });
    await logResponseOnFailure(response, 'issue87: create role with invalid permissions');
    expect(response.status()).toBe(422);
  });

  test('@authorization @validation @crud @negative @regression issue88: create role with invalid permission type', async ({ roleService }) => {
    const response = await roleService.create({
      name: unique('Role'),
      permission_type: 'bogus',
    });
    await logResponseOnFailure(response, 'issue88: create role with invalid permission type');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue89: show role with invalid id', async ({ roleService }) => {
    const response = await roleService.getById(999999);
    await logResponseOnFailure(response, 'issue89: show role with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@authorization @validation @crud @negative @regression issue90: update role with invalid permissions', async ({ roleService }) => {
    const createResponse = await roleService.create({
      name: unique('Role'),
      permission_type: 'all',
    });
    const { data } = await createResponse.json();

    const response = await roleService.update(data.id, {
      name: unique('Role'),
      permissions: { 'bogus.permission.key': 1 },
    });
    await logResponseOnFailure(response, 'issue90: update role with invalid permissions');
    expect(response.status()).toBe(422);
  });

  test('@authorization @validation @crud @negative @regression issue91: update role with invalid permission type', async ({ roleService }) => {
    const createResponse = await roleService.create({
      name: unique('Role'),
      permission_type: 'all',
    });
    const { data } = await createResponse.json();

    const response = await roleService.update(data.id, {
      name: unique('Role'),
      permission_type: 'bogus',
    });
    await logResponseOnFailure(response, 'issue91: update role with invalid permission type');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue92: delete role with invalid id', async ({ roleService }) => {
    const response = await roleService.delete(999999);
    await logResponseOnFailure(response, 'issue92: delete role with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression issue93: delete valid role', async ({ roleService }) => {
    const createResponse = await roleService.create({
      name: unique('Role'),
      permission_type: 'all',
    });
    const { data } = await createResponse.json();

    const response = await roleService.delete(data.id);
    await logResponseOnFailure(response, 'issue93: delete valid role');
    expect(response.status()).toBe(200);
  });
});
