import { test, expect, logResponseOnFailure } from '../../fixtures/api.fixture';
import { config } from '../../utils/config';

test.describe('Login API', () => {
  test('@smoke @auth @regression login with valid credentials', async ({ authService }) => {
    const response = await authService.login({
      email: config.testUser.email,
      password: config.testUser.password,
      device_name: 'android',
    });

    await logResponseOnFailure(response, 'login with valid credentials');

    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.message).toBe('Login successful.');
    expect(body.token).toBeTruthy();
    expect(body.data.email).toBe(config.testUser.email);
    expect(body.data.name).toBeTruthy();

  });

  test('@auth @validation @negative @regression login with missing fields', async ({ authService }) => {
    const response = await authService.login({
      email: '',
      password: '',
    });

    await logResponseOnFailure(response, 'login with missing fields');

    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.message).toContain('required');
    expect(body.errors.email[0]).toContain('required');
    expect(body.errors.password[0]).toContain('required');
    expect(body.errors.device_name[0]).toContain('required');
  });

  test('@auth @validation @negative @regression login with invalid email format', async ({ authService }) => {
    const response = await authService.login({
      email: 'not-an-email',
      password: config.testUser.password,
    });

    await logResponseOnFailure(response, 'login with invalid email format');

    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.email[0]).toContain('valid email address');
  });

  test('@auth @validation @security @negative @regression login with wrong password', async ({ authService }) => {
    const response = await authService.login({
      email: config.testUser.email,
      password: 'wrong-password',
      device_name: 'android',
    });

    await logResponseOnFailure(response, 'login with wrong password');

    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.email[0]).toBe('The provided credentials are incorrect.');
  });

  test('@auth @negative @regression login with wrong http method', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/login');

    await logResponseOnFailure(response, 'login with wrong http method');

    expect(response.status()).toBe(405);
    const body = await response.json();
    expect(body.message).toBe('Method Not Allowed');
  });
});

test.describe('Get Current User', () => {
  test('@smoke @auth @crud @regression get current user with valid token', async ({ authedApi }) => {
    const response = await authedApi.get('/api/v1/get');

    await logResponseOnFailure(response, 'get current user with valid token');

    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data).toBeTruthy();
  });

  test('@auth @crud @negative @regression get current user without token', async ({ authService }) => {
    const response = await authService.getAccount();

    await logResponseOnFailure(response, 'get current user without token');

    expect(response.status()).toBe(401);
  });
});

test.describe('Logout', () => {
  test('@auth @regression logout successfully', async ({ authedApi }) => {
    const response = await authedApi.delete('/api/v1/logout');

    await logResponseOnFailure(response, 'logout successfully');

    expect(response.status()).toBe(200);
  });
});
