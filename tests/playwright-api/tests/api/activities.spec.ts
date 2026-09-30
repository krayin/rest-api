import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Activities API', () => {
  test('@auth @crud @negative @regression list activities without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/activities');
    await logResponseOnFailure(response, 'list activities without token');
    expect(response.status()).toBe(401);
  });

  test('@smoke @crud @regression list activities', async ({ activityService }) => {
    const response = await activityService.list();
    await logResponseOnFailure(response, 'list activities');
    expect(response.status()).toBe(200);
  });

  test('@smoke @crud @regression create activity note', async ({ activityService }) => {
    const response = await activityService.create({
      type: 'note',
      comment: unique('Note'),
    });

    await logResponseOnFailure(response, 'create activity note');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@smoke @crud @regression create activity call', async ({ activityService }) => {
    const response = await activityService.create({
      type: 'call',
      schedule_from: '2027-01-01 10:00:00',
      schedule_to: '2027-01-01 11:00:00',
    });
    await logResponseOnFailure(response, 'create activity call');
    expect(response.status()).toBe(200);
  });

  test('@validation @crud @negative @regression create activity with empty payload', async ({ activityService }) => {
    const response = await activityService.create({});
    await logResponseOnFailure(response, 'create activity with empty payload');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.type[0]).toContain('required');
  });

  test('@validation @crud @negative @regression create activity with invalid type', async ({ activityService }) => {
    const response = await activityService.create({ type: 'invalid-type' });
    await logResponseOnFailure(response, 'create activity with invalid type');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.type[0]).toContain('invalid');
  });

  test('@validation @crud @negative @regression create activity call without schedule', async ({ activityService }) => {
    const response = await activityService.create({ type: 'call' });
    await logResponseOnFailure(response, 'create activity call without schedule');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show activity', async ({ activityService }) => {
    const createResponse = await activityService.create({
      type: 'note',
      comment: unique('Note'),
    });
    const { data } = await createResponse.json();

    const response = await activityService.getById(data.id);
    await logResponseOnFailure(response, 'show activity');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(data.id);
  });

  test('@crud @negative @regression show activity not found', async ({ activityService }) => {
    const response = await activityService.getById(999999);
    await logResponseOnFailure(response, 'show activity not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update activity', async ({ activityService }) => {
    const createResponse = await activityService.create({
      type: 'note',
      comment: unique('Note'),
    });
    const { data } = await createResponse.json();

    const response = await activityService.update(data.id, {
      type: 'note',
      comment: unique('Note'),
    });
    await logResponseOnFailure(response, 'update activity');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression update activity with empty payload', async ({ activityService }) => {
    const createResponse = await activityService.create({
      type: 'note',
      comment: unique('Note'),
    });
    const { data } = await createResponse.json();

    const response = await activityService.update(data.id, {});
    await logResponseOnFailure(response, 'update activity with empty payload');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression delete activity', async ({ activityService }) => {
    const createResponse = await activityService.create({
      type: 'note',
      comment: unique('Note'),
    });
    const { data } = await createResponse.json();

    const response = await activityService.delete(data.id);
    await logResponseOnFailure(response, 'delete activity');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression delete activity not found', async ({ activityService }) => {
    const response = await activityService.delete(999999);
    await logResponseOnFailure(response, 'delete activity not found');
    expect(response.status()).toBe(404);
  });

  test('@acid @crud @regression mass update activities', async ({ activityService }) => {
    const createResponse = await activityService.create({
      type: 'note',
      comment: unique('Note'),
    });
    const { data } = await createResponse.json();

    const response = await activityService.massUpdate([data.id], true);
    await logResponseOnFailure(response, 'mass update activities');
    expect(response.status()).toBe(200);
  });

  test('@validation @acid @crud @negative @regression mass update activities with missing indices', async ({ activityService }) => {
    const response = await activityService.massUpdateRaw({});
    await logResponseOnFailure(response, 'mass update activities with missing indices');
    expect(response.status()).toBe(422);
  });

  test('@acid @crud @regression mass destroy activities', async ({ activityService }) => {
    const createResponse = await activityService.create({
      type: 'note',
      comment: unique('Note'),
    });
    const { data } = await createResponse.json();

    const response = await activityService.massDestroy([data.id]);
    await logResponseOnFailure(response, 'mass destroy activities');
    expect(response.status()).toBe(200);
  });

  test('@validation @acid @crud @negative @regression mass destroy activities with missing indices', async ({ activityService }) => {
    const response = await activityService.massDestroyRaw({});
    await logResponseOnFailure(response, 'mass destroy activities with missing indices');
    expect(response.status()).toBe(422);
  });

  test('@negative @regression download activity file not found', async ({ activityService }) => {
    const response = await activityService.downloadFile(999999);
    await logResponseOnFailure(response, 'download activity file not found');
    expect(response.status()).toBe(404);
  });

  test('@negative @regression activity with wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/activities/1', { data: {} });
    await logResponseOnFailure(response, 'activity with wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@validation @pagination @crud @negative @regression issue68: list activities with invalid sort parameter', async ({ activityService }) => {
    const response = await activityService.list({
      params: { sort: 'bogus' },
    });
    await logResponseOnFailure(response, 'issue68: list activities with invalid sort parameter');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue69: show activity with invalid id', async ({ activityService }) => {
    const response = await activityService.getById(999999);
    await logResponseOnFailure(response, 'issue69: show activity with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue70: update activity with invalid id', async ({ activityService }) => {
    const response = await activityService.update(999999, { type: 'note', comment: 'test' });
    await logResponseOnFailure(response, 'issue70: update activity with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@validation @crud @negative @regression issue71: update activity with invalid type', async ({ activityService }) => {
    const createResponse = await activityService.create({
      type: 'note',
      comment: unique('Note'),
    });
    const { data } = await createResponse.json();

    const response = await activityService.update(data.id, { type: 'bogus-type' });
    await logResponseOnFailure(response, 'issue71: update activity with invalid type');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue72: delete activity with invalid id', async ({ activityService }) => {
    const response = await activityService.delete(999999);
    await logResponseOnFailure(response, 'issue72: delete activity with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@acid @crud @negative @regression issue74: mass update activities with invalid ids', async ({ activityService }) => {
    const response = await activityService.massUpdate([999999], true);
    await logResponseOnFailure(response, 'issue74: mass update activities with invalid ids');
    expect(response.status()).toBe(404);
  });

  test('@acid @crud @negative @regression issue75: mass destroy activities with invalid ids', async ({ activityService }) => {
    const response = await activityService.massDestroy([999999]);
    await logResponseOnFailure(response, 'issue75: mass destroy activities with invalid ids');
    expect(response.status()).toBe(404);
  });
});
