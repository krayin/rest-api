import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Settings Webhooks API', () => {
  test('@auth @crud @negative @regression list webhooks without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/settings/webhooks');
    await logResponseOnFailure(response, 'list webhooks without token');
    expect(response.status()).toBe(401);
  });

  test('@crud @regression list webhooks', async ({ webhookService }) => {
    const response = await webhookService.list();
    await logResponseOnFailure(response, 'list webhooks');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression create webhook', async ({ webhookService }) => {
    const response = await webhookService.create({
      name: unique('Webhook'),
      entity_type: 'leads',
      method: 'POST',
      end_point: 'http://localhost:9000/hook',
      payload_type: 'default',
    });
    await logResponseOnFailure(response, 'create webhook');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@validation @crud @negative @regression create webhook with empty payload', async ({ webhookService }) => {
    const response = await webhookService.create({});
    await logResponseOnFailure(response, 'create webhook with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show webhook', async ({ webhookService }) => {
    const createResponse = await webhookService.create({
      name: unique('Webhook'),
      entity_type: 'leads',
      method: 'POST',
      end_point: 'http://localhost:9000/hook',
      payload_type: 'default',
    });
    const { data } = await createResponse.json();

    const response = await webhookService.getById(data.id);
    await logResponseOnFailure(response, 'show webhook');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(data.id);
  });

  test('@crud @negative @regression show webhook not found', async ({ webhookService }) => {
    const response = await webhookService.getById(999999);
    await logResponseOnFailure(response, 'show webhook not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update webhook', async ({ webhookService }) => {
    const createResponse = await webhookService.create({
      name: unique('Webhook'),
      entity_type: 'leads',
      method: 'POST',
      end_point: 'http://localhost:9000/hook',
      payload_type: 'default',
    });
    const { data } = await createResponse.json();

    const response = await webhookService.update(data.id, { name: unique('Webhook') });
    await logResponseOnFailure(response, 'update webhook');
    expect(response.status()).toBe(200);
  });

  test('@validation @crud @negative @regression update webhook with empty payload', async ({ webhookService }) => {
    const createResponse = await webhookService.create({
      name: unique('Webhook'),
      entity_type: 'leads',
      method: 'POST',
      end_point: 'http://localhost:9000/hook',
      payload_type: 'default',
    });
    const { data } = await createResponse.json();

    const response = await webhookService.update(data.id, {});
    await logResponseOnFailure(response, 'update webhook with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression delete webhook', async ({ webhookService }) => {
    const createResponse = await webhookService.create({
      name: unique('Webhook'),
      entity_type: 'leads',
      method: 'POST',
      end_point: 'http://localhost:9000/hook',
      payload_type: 'default',
    });
    const { data } = await createResponse.json();

    const response = await webhookService.delete(data.id);
    await logResponseOnFailure(response, 'delete webhook');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression delete webhook not found', async ({ webhookService }) => {
    const response = await webhookService.delete(999999);
    await logResponseOnFailure(response, 'delete webhook not found');
    expect(response.status()).toBe(404);
  });

  test('@negative @regression wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/settings/webhooks/1', { data: {} });
    await logResponseOnFailure(response, 'wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@validation @crud @negative @regression issue147: create webhook with invalid entity type', async ({ webhookService }) => {
    const response = await webhookService.create({
      name: unique('Webhook'),
      entity_type: 'bogus',
      method: 'POST',
      end_point: 'http://localhost:9000/hook',
      payload_type: 'default',
    });
    await logResponseOnFailure(response, 'issue147: create webhook with invalid entity type');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression issue148: create webhook with duplicate name', async ({ webhookService }) => {
    const name = unique('Webhook');
    await webhookService.create({
      name,
      entity_type: 'leads',
      method: 'POST',
      end_point: 'http://localhost:9000/hook',
      payload_type: 'default',
    });
    const response = await webhookService.create({
      name,
      entity_type: 'leads',
      method: 'POST',
      end_point: 'http://localhost:9000/hook2',
      payload_type: 'default',
    });
    await logResponseOnFailure(response, 'issue148: create webhook with duplicate name');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue149: update webhook with invalid id', async ({ webhookService }) => {
    const response = await webhookService.update(999999, { name: unique('Webhook') });
    await logResponseOnFailure(response, 'issue149: update webhook with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue150: delete webhook with invalid id', async ({ webhookService }) => {
    const response = await webhookService.delete(999999);
    await logResponseOnFailure(response, 'issue150: delete webhook with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@validation @crud @negative @regression issue151: create webhook with duplicate events', async ({ webhookService }) => {
    const response = await webhookService.create({
      name: unique('Webhook'),
      entity_type: 'leads',
      method: 'POST',
      end_point: 'http://localhost:9000/hook',
      payload_type: 'default',
      events: ['lead.create', 'lead.create'],
    });
    await logResponseOnFailure(response, 'issue151: create webhook with duplicate events');
    expect(response.status()).toBe(422);
  });
});
