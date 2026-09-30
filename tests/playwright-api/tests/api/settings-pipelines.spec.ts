import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Settings Pipelines API', () => {
  test('@auth @crud @negative @regression list pipelines without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/settings/pipelines');
    await logResponseOnFailure(response, 'list pipelines without token');
    expect(response.status()).toBe(401);
  });

  test('@crud @regression list pipelines', async ({ pipelineService }) => {
    const response = await pipelineService.list();
    await logResponseOnFailure(response, 'list pipelines');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression create pipeline', async ({ pipelineService }) => {
    const response = await pipelineService.create({
      name: unique('Pipeline'),
      stages: [
        { name: 'Stage A', code: `stage-a-${unique('code')}`, probability: 100, sort_order: 1 },
      ],
    });
    await logResponseOnFailure(response, 'create pipeline');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@validation @crud @negative @regression create pipeline with empty payload', async ({ pipelineService }) => {
    const response = await pipelineService.create({});
    await logResponseOnFailure(response, 'create pipeline with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show pipeline', async ({ pipelineService }) => {
    const createResponse = await pipelineService.create({
      name: unique('Pipeline'),
      stages: [
        { name: 'Stage A', code: `stage-a-${unique('code')}`, probability: 100, sort_order: 1 },
      ],
    });
    const { data } = await createResponse.json();

    const response = await pipelineService.getById(data.id);
    await logResponseOnFailure(response, 'show pipeline');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(data.id);
  });

  test('@crud @negative @regression show pipeline not found', async ({ pipelineService }) => {
    const response = await pipelineService.getById(999999);
    await logResponseOnFailure(response, 'show pipeline not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update pipeline', async ({ pipelineService }) => {
    const createResponse = await pipelineService.create({
      name: unique('Pipeline'),
      stages: [
        { name: 'Stage A', code: `stage-a-${unique('code')}`, probability: 100, sort_order: 1 },
      ],
    });
    const { data } = await createResponse.json();

    const response = await pipelineService.update(data.id, {
      name: unique('Pipeline'),
      stages: [
        { name: 'Stage B', code: `stage-b-${unique('code')}`, probability: 50, sort_order: 1 },
      ],
    });
    await logResponseOnFailure(response, 'update pipeline');
    expect(response.status()).toBe(200);
  });

  test('@validation @crud @negative @regression update pipeline with empty payload', async ({ pipelineService }) => {
    const createResponse = await pipelineService.create({
      name: unique('Pipeline'),
      stages: [
        { name: 'Stage A', code: `stage-a-${unique('code')}`, probability: 100, sort_order: 1 },
      ],
    });
    const { data } = await createResponse.json();

    const response = await pipelineService.update(data.id, {});
    await logResponseOnFailure(response, 'update pipeline with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression delete pipeline', async ({ pipelineService }) => {
    const createResponse = await pipelineService.create({
      name: unique('Pipeline'),
      stages: [
        { name: 'Stage A', code: `stage-a-${unique('code')}`, probability: 100, sort_order: 1 },
      ],
    });
    const { data } = await createResponse.json();

    const response = await pipelineService.delete(data.id);
    await logResponseOnFailure(response, 'delete pipeline');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression delete pipeline not found', async ({ pipelineService }) => {
    const response = await pipelineService.delete(999999);
    await logResponseOnFailure(response, 'delete pipeline not found');
    expect(response.status()).toBe(404);
  });

  test('@validation @crud @negative @regression create pipeline without stages', async ({ pipelineService }) => {
    const response = await pipelineService.create({ name: unique('Pipeline') });
    await logResponseOnFailure(response, 'create pipeline without stages');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.stages[0]).toContain('required');
  });

  test('@validation @crud @negative @regression create pipeline with duplicate stage codes', async ({ pipelineService }) => {
    const response = await pipelineService.create({
      name: unique('Pipeline'),
      stages: [
        { name: 'Stage A', code: 'same-code', probability: 100, sort_order: 1 },
        { name: 'Stage B', code: 'same-code', probability: 50, sort_order: 2 },
      ],
    });
    await logResponseOnFailure(response, 'create pipeline with duplicate stage codes');
    expect(response.status()).toBe(422);
  });

  test('@negative @regression wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/settings/pipelines/1', { data: {} });
    await logResponseOnFailure(response, 'wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@validation @pagination @crud @negative @regression issue127: list pipelines with invalid sort parameter', async ({ pipelineService }) => {
    const response = await pipelineService.list({
      params: { sort: 'bogus' },
    });
    await logResponseOnFailure(response, 'issue127: list pipelines with invalid sort parameter');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression issue128: create pipeline with negative rotten days', async ({ pipelineService }) => {
    const response = await pipelineService.create({
      name: unique('Pipeline'),
      rotten_days: -5,
      stages: [
        { name: 'Stage A', code: `stage-a-${unique('code')}`, probability: 100, sort_order: 1 },
      ],
    });
    await logResponseOnFailure(response, 'issue128: create pipeline with negative rotten days');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression issue129: create pipeline with is_default 0 is honored', async ({ pipelineService }) => {
    const response = await pipelineService.create({
      name: unique('Pipeline'),
      is_default: 0,
      stages: [
        { name: 'Stage A', code: `stage-a-${unique('code')}`, probability: 100, sort_order: 1 },
      ],
    });
    await logResponseOnFailure(response, 'issue129: create pipeline with is_default 0 is honored');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@validation @crud @negative @regression issue130: create pipeline with duplicate name', async ({ pipelineService }) => {
    const name = unique('Pipeline');
    await pipelineService.create({
      name,
      stages: [
        { name: 'Stage A', code: `stage-a-${unique('code')}`, probability: 100, sort_order: 1 },
      ],
    });
    const response = await pipelineService.create({
      name,
      stages: [
        { name: 'Stage B', code: `stage-b-${unique('code')}`, probability: 50, sort_order: 1 },
      ],
    });
    await logResponseOnFailure(response, 'issue130: create pipeline with duplicate name');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression issue132: update pipeline with negative rotten days', async ({ pipelineService }) => {
    const createResponse = await pipelineService.create({
      name: unique('Pipeline'),
      stages: [
        { name: 'Stage A', code: `stage-a-${unique('code')}`, probability: 100, sort_order: 1 },
      ],
    });
    const { data } = await createResponse.json();

    const response = await pipelineService.update(data.id, {
      name: unique('Pipeline'),
      rotten_days: -3,
    });
    await logResponseOnFailure(response, 'issue132: update pipeline with negative rotten days');
    expect(response.status()).toBe(422);
  });
});
