import { test, expect, unique, waitForNextSecond, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Mails API', () => {
  test('@auth @crud @negative @regression list mails without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/mails');
    await logResponseOnFailure(response, 'list mails without token');
    expect(response.status()).toBe(401);
  });

  test('@crud @regression list mails', async ({ mailService }) => {
    const response = await mailService.list();
    await logResponseOnFailure(response, 'list mails');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression create mail', async ({ mailService }) => {
    await waitForNextSecond();
    const response = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'reply body',
    });

    await logResponseOnFailure(response, 'create mail');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@crud @regression create mail as draft', async ({ mailService }) => {
    await waitForNextSecond();
    const response = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'draft body',
      is_draft: true,
    });
    await logResponseOnFailure(response, 'create mail as draft');
    expect(response.status()).toBe(200);
  });

  test('@validation @crud @negative @regression create mail with empty payload', async ({ mailService }) => {
    const response = await mailService.create({});
    await logResponseOnFailure(response, 'create mail with empty payload');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.reply_to[0]).toContain('required');
    expect(body.errors.reply[0]).toContain('required');
  });

  test('@validation @crud @negative @regression create mail with invalid reply to', async ({ mailService }) => {
    const response = await mailService.create({
      reply_to: ['not-an-email'],
      reply: 'reply body',
    });
    await logResponseOnFailure(response, 'create mail with invalid reply to');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show mail', async ({ mailService }) => {
    await waitForNextSecond();
    const createResponse = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'reply body',
    });
    const { data } = await createResponse.json();

    const response = await mailService.getById(data.id);
    await logResponseOnFailure(response, 'show mail');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(data.id);
  });

  test('@crud @negative @regression show mail not found', async ({ mailService }) => {
    const response = await mailService.getById(999999);
    await logResponseOnFailure(response, 'show mail not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update mail', async ({ mailService }) => {
    await waitForNextSecond();
    const createResponse = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'reply body',
    });
    const { data } = await createResponse.json();

    const response = await mailService.update(data.id, {
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'updated reply',
    });
    await logResponseOnFailure(response, 'update mail');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression update mail with empty payload', async ({ mailService }) => {
    await waitForNextSecond();
    const createResponse = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'reply body',
    });
    const { data } = await createResponse.json();

    const response = await mailService.update(data.id, {});
    await logResponseOnFailure(response, 'update mail with empty payload');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression delete mail', async ({ mailService }) => {
    await waitForNextSecond();
    const createResponse = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'reply body',
    });
    const { data } = await createResponse.json();

    const response = await mailService.delete(data.id);
    await logResponseOnFailure(response, 'delete mail');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression delete mail not found', async ({ mailService }) => {
    const response = await mailService.delete(999999);
    await logResponseOnFailure(response, 'delete mail not found');
    expect(response.status()).toBe(404);
  });



  test('@acid @crud @regression mass update mails', async ({ mailService }) => {
    await waitForNextSecond();
    const createResponse = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'reply body',
    });
    const { data } = await createResponse.json();

    const response = await mailService.massUpdateFolders([data.id], ['inbox'], 1);
    await logResponseOnFailure(response, 'mass update mails');
    expect(response.status()).toBe(200);
  });

  test('@validation @acid @crud @negative @regression mass update mails with missing indices', async ({ mailService }) => {
    const response = await mailService.massUpdateRaw({});
    await logResponseOnFailure(response, 'mass update mails with missing indices');
    expect(response.status()).toBe(422);
  });

  test('@acid @crud @regression mass destroy mails', async ({ mailService }) => {
    await waitForNextSecond();
    const createResponse = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'reply body',
    });
    const { data } = await createResponse.json();

    const response = await mailService.massDestroy([data.id]);
    await logResponseOnFailure(response, 'mass destroy mails');
    expect(response.status()).toBe(200);
  });

  test('@validation @acid @crud @negative @regression mass destroy mails with missing indices', async ({ mailService }) => {
    const response = await mailService.massDestroyRaw({});
    await logResponseOnFailure(response, 'mass destroy mails with missing indices');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression mail attachment download not found', async ({ mailService }) => {
    const response = await mailService.downloadAttachment(999999);
    await logResponseOnFailure(response, 'mail attachment download not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression mail attach tag', async ({ mailService, tagService }) => {
    await waitForNextSecond();
    const mailResponse = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'reply body',
    });
    const { data: mail } = await mailResponse.json();

    const tagResponse = await tagService.create({ name: unique('Tag') });
    const { data: tag } = await tagResponse.json();

    const response = await mailService.attachTag(mail.id, tag.id);
    await logResponseOnFailure(response, 'mail attach tag');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.message).toContain('attached successfully');
  });

  test('@validation @crud @negative @regression mail attach tag with missing tag id', async ({ authedApi, mailService }) => {
    await waitForNextSecond();
    const mailResponse = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'reply body',
    });
    const { data: mail } = await mailResponse.json();

    const response = await authedApi.post(`/api/v1/mails/${mail.id}/tags`, {
      data: {},
    });
    await logResponseOnFailure(response, 'mail attach tag with missing tag id');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.tag_id[0]).toContain('required');
  });

  test('@crud @regression mail detach tag', async ({ mailService, tagService }) => {
    await waitForNextSecond();
    const mailResponse = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'reply body',
    });
    const { data: mail } = await mailResponse.json();

    const tagResponse = await tagService.create({ name: unique('Tag') });
    const { data: tag } = await tagResponse.json();

    await mailService.attachTag(mail.id, tag.id);

    const response = await mailService.detachTag(mail.id, tag.id);
    await logResponseOnFailure(response, 'mail detach tag');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.message).toContain('detached successfully');
  });

  test('@negative @regression mail with wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/mails/1', { data: {} });
    await logResponseOnFailure(response, 'mail with wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@crud @regression issue31: create mail draft flag is honored', async ({ mailService }) => {
    await waitForNextSecond();
    const response = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'draft body',
      is_draft: true,
    });
    await logResponseOnFailure(response, 'issue31: create mail draft flag is honored');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data).toBeTruthy();
  });

  test('@crud @negative @regression issue32: show mail with invalid id', async ({ mailService }) => {
    const response = await mailService.getById(999999);
    await logResponseOnFailure(response, 'issue32: show mail with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue34: delete mail with invalid id', async ({ mailService }) => {
    const response = await mailService.delete(999999);
    await logResponseOnFailure(response, 'issue34: delete mail with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@validation @acid @crud @negative @regression issue35: mass update mails with invalid input', async ({ mailService }) => {
    const response = await mailService.massUpdateRaw({});
    await logResponseOnFailure(response, 'issue35: mass update mails with invalid input');
    expect(response.status()).toBe(422);
  });

  test('@acid @crud @negative @regression issue36: mass destroy mails with invalid ids', async ({ mailService }) => {
    const response = await mailService.massDestroy([999999]);
    await logResponseOnFailure(response, 'issue36: mass destroy mails with invalid ids');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue38: attach tag to non-existent mail', async ({ mailService }) => {
    const response = await mailService.attachTag(999999, 1);
    await logResponseOnFailure(response, 'issue38: attach tag to non-existent mail');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression issue33: delete draft mail', async ({ mailService }) => {
    await waitForNextSecond();
    const createResponse = await mailService.create({
      subject: unique('Mail'),
      reply_to: [unique('mail') + '@example.com'],
      reply: 'draft body',
      is_draft: true,
    });
    const { data } = await createResponse.json();

    const response = await mailService.delete(data.id);
    await logResponseOnFailure(response, 'issue33: delete draft mail');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.message).toBeTruthy();
  });
});
