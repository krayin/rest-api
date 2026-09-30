import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Settings Email Templates API', () => {
  test('@auth @crud @negative @regression list email templates without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/settings/email-templates');
    await logResponseOnFailure(response, 'list email templates without token');
    expect(response.status()).toBe(401);
  });

  test('@crud @regression list email templates', async ({ emailTemplateService }) => {
    const response = await emailTemplateService.list();
    await logResponseOnFailure(response, 'list email templates');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression create email template', async ({ emailTemplateService }) => {
    const response = await emailTemplateService.create({
      name: unique('Template'),
      subject: 'Hello',
      content: '<p>Hello</p>',
    });
    await logResponseOnFailure(response, 'create email template');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@validation @crud @negative @regression create email template with empty payload', async ({ emailTemplateService }) => {
    const response = await emailTemplateService.create({});
    await logResponseOnFailure(response, 'create email template with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show email template', async ({ emailTemplateService }) => {
    const createResponse = await emailTemplateService.create({
      name: unique('Template'),
      subject: 'Hello',
      content: '<p>Hello</p>',
    });
    const { data } = await createResponse.json();

    const response = await emailTemplateService.getById(data.id);
    await logResponseOnFailure(response, 'show email template');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(data.id);
  });

  test('@crud @negative @regression show email template not found', async ({ emailTemplateService }) => {
    const response = await emailTemplateService.getById(999999);
    await logResponseOnFailure(response, 'show email template not found');
    expect(response.status()).toBe(404);
  });

  test('@validation @crud @negative @regression update email template', async ({ emailTemplateService }) => {
    const createResponse = await emailTemplateService.create({
      name: unique('Template'),
      subject: 'Hello',
      content: '<p>Hello</p>',
    });
    const { data } = await createResponse.json();

    const response = await emailTemplateService.update(data.id, { name: unique('Template') });
    await logResponseOnFailure(response, 'update email template');
    expect(response.status()).toBe(422);
  

  });

  test('@validation @crud @negative @regression update email template with empty payload', async ({ emailTemplateService }) => {
    const createResponse = await emailTemplateService.create({
      name: unique('Template'),
      subject: 'Hello',
      content: '<p>Hello</p>',
    });
    const { data } = await createResponse.json();

    const response = await emailTemplateService.update(data.id, {});
    await logResponseOnFailure(response, 'update email template with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression delete email template', async ({ emailTemplateService }) => {
    const createResponse = await emailTemplateService.create({
      name: unique('Template'),
      subject: 'Hello',
      content: '<p>Hello</p>',
    });
    const { data } = await createResponse.json();

    const response = await emailTemplateService.delete(data.id);
    await logResponseOnFailure(response, 'delete email template');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression delete email template not found', async ({ emailTemplateService }) => {
    const response = await emailTemplateService.delete(999999);
    await logResponseOnFailure(response, 'delete email template not found');
    expect(response.status()).toBe(404);
  });

  test('@negative @regression wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/settings/email-templates/1', { data: {} });
    await logResponseOnFailure(response, 'wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@validation @crud @negative @regression issue136: create email template with duplicate name', async ({ emailTemplateService }) => {
    const name = unique('Template');
    await emailTemplateService.create({ name, subject: 'Hello', content: '<p>Hello</p>' });
    const response = await emailTemplateService.create({ name, subject: 'Hello', content: '<p>Hello</p>' });
    await logResponseOnFailure(response, 'issue136: create email template with duplicate name');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue137: show email template with invalid id', async ({ emailTemplateService }) => {
    const response = await emailTemplateService.getById(999999);
    await logResponseOnFailure(response, 'issue137: show email template with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue138: update email template with invalid id', async ({ emailTemplateService }) => {
    const response = await emailTemplateService.update(999999, { name: unique('Template') });
    await logResponseOnFailure(response, 'issue138: update email template with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue139: delete email template with invalid id', async ({ emailTemplateService }) => {
    const response = await emailTemplateService.delete(999999);
    await logResponseOnFailure(response, 'issue139: delete email template with invalid id');
    expect(response.status()).toBe(404);
  });
});
