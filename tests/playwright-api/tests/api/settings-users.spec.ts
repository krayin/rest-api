import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Settings Users API', () => {
  test('@auth @crud @negative @regression list users without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/settings/users');
    await logResponseOnFailure(response, 'list users without token');
    expect(response.status()).toBe(401);
  });

  test('@crud @regression list users', async ({ userService }) => {
    const response = await userService.list();
    await logResponseOnFailure(response, 'list users');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression create user', async ({ userService }) => {
    const response = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      view_permission: 'global',
    });
    await logResponseOnFailure(response, 'create user');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@validation @crud @negative @regression create user with empty payload', async ({ userService }) => {
    const response = await userService.create({});
    await logResponseOnFailure(response, 'create user with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show user', async ({ userService }) => {
    const createResponse = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      view_permission: 'global',
    });
    const { data } = await createResponse.json();

    const response = await userService.getById(data.id);
    await logResponseOnFailure(response, 'show user');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(data.id);
  });

  test('@crud @negative @regression show user not found', async ({ userService }) => {
    const response = await userService.getById(999999);
    await logResponseOnFailure(response, 'show user not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update user', async ({ userService }) => {
    const createResponse = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      view_permission: 'global',
    });
    const { data } = await createResponse.json();

    const response = await userService.update(data.id, { name: unique('User') });
    await logResponseOnFailure(response, 'update user');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression update user with empty payload', async ({ userService }) => {
    const createResponse = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      view_permission: 'global',
    });
    const { data } = await createResponse.json();

    const response = await userService.update(data.id, {});
    await logResponseOnFailure(response, 'update user with empty payload');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression delete user', async ({ userService }) => {
    const createResponse = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      view_permission: 'global',
    });
    const { data } = await createResponse.json();

    const response = await userService.delete(data.id);
    await logResponseOnFailure(response, 'delete user');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression delete user not found', async ({ userService }) => {
    const response = await userService.delete(999999);
    await logResponseOnFailure(response, 'delete user not found');
    expect(response.status()).toBe(404);
  });

  test('@negative @regression wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/settings/users/1', { data: {} });
    await logResponseOnFailure(response, 'wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@validation @crud @negative @regression issue21: update user with empty email', async ({ userService }) => {
    const createResponse = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      view_permission: 'global',
    });
    const { data } = await createResponse.json();

    const response = await userService.update(data.id, { email: '' });
    await logResponseOnFailure(response, 'issue21: update user with empty email');
    expect(response.status()).toBe(422);
  });

  test('@authorization @validation @crud @negative @regression issue94: create user with invalid view permission', async ({ userService }) => {
    const response = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      view_permission: 'bogus',
    });
    await logResponseOnFailure(response, 'issue94: create user with invalid view permission');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression issue95: create user with invalid role id', async ({ userService }) => {
    const response = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 999999,
      view_permission: 'global',
    });
    await logResponseOnFailure(response, 'issue95: create user with invalid role id');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression issue96: create user with invalid group id', async ({ userService }) => {
    const response = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      group_id: 999999,
      view_permission: 'global',
    });
    await logResponseOnFailure(response, 'issue96: create user with invalid group id');
    expect(response.status()).toBe(422);
  });

  test('@validation @security @crud @negative @regression issue97: create user with empty password', async ({ userService }) => {
    const response = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: '',
      confirm_password: '',
      role_id: 1,
      view_permission: 'global',
    });
    await logResponseOnFailure(response, 'issue97: create user with empty password');
    expect(response.status()).toBe(422);
  });

  test('@validation @pagination @crud @negative @regression issue100: list users with invalid sort parameter', async ({ userService }) => {
    const response = await userService.list({
      params: { sort: 'bogus' },
    });
    await logResponseOnFailure(response, 'issue100: list users with invalid sort parameter');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression issue104: update user with invalid group id', async ({ userService }) => {
    const createResponse = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      view_permission: 'global',
    });
    const { data } = await createResponse.json();

    const response = await userService.update(data.id, { group_id: 999999 });
    await logResponseOnFailure(response, 'issue104: update user with invalid group id');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue105: delete user with invalid id', async ({ userService }) => {
    const response = await userService.delete(999999);
    await logResponseOnFailure(response, 'issue105: delete user with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@acid @crud @negative @regression issue108: mass update users with invalid indices', async ({ userService }) => {
    const response = await userService.massUpdate([999999], 1);
    await logResponseOnFailure(response, 'issue108: mass update users with invalid indices');
    expect(response.status()).toBe(404);
  });

  test('@acid @crud @negative @regression issue109: mass destroy users with invalid indices', async ({ userService }) => {
    const response = await userService.massDestroy([999999]);
    await logResponseOnFailure(response, 'issue109: mass destroy users with invalid indices');
    expect(response.status()).toBe(404);
  });

  test('@validation @crud @negative @regression issue98: create user with missing role id does not flag name and email', async ({ userService }) => {
    const response = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: null,
      view_permission: 'global',
    });
    await logResponseOnFailure(response, 'issue98: create user with missing role id does not flag name and email');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.role_id).toBeTruthy();
  });

  test('@validation @security @crud @negative @regression issue101: update user with only password fields', async ({ userService }) => {
    const createResponse = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      view_permission: 'global',
    });
    const { data } = await createResponse.json();

    const response = await userService.update(data.id, {
      password: 'newpassword123',
      confirm_password: 'newpassword123',
    });
    await logResponseOnFailure(response, 'issue101: update user with only password fields');
    expect(response.status()).toBe(422);
  });

  test('@validation @security @crud @negative @regression issue102: update user with empty password', async ({ userService }) => {
    const createResponse = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      view_permission: 'global',
    });
    const { data } = await createResponse.json();

    const response = await userService.update(data.id, {
      password: '',
      confirm_password: '',
    });
    await logResponseOnFailure(response, 'issue102: update user with empty password');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression issue103: update user with missing role id does not flag name and email', async ({ userService }) => {
    const createResponse = await userService.create({
      name: unique('User'),
      email: unique('user') + '@example.com',
      password: 'password123',
      confirm_password: 'password123',
      role_id: 1,
      view_permission: 'global',
    });
    const { data } = await createResponse.json();

    const response = await userService.update(data.id, { role_id: null });
    await logResponseOnFailure(response, 'issue103: update user with missing role id does not flag name and email');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.role_id).toBeTruthy();
  });

  test('@validation @acid @crud @negative @regression issue107: mass update users with string value', async ({ userService }) => {
    const response = await userService.massUpdate([1], 'inactive');
    await logResponseOnFailure(response, 'issue107: mass update users with string value');
    expect(response.status()).toBe(422);
  });
});
