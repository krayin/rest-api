import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Settings Marketing Campaigns API', () => {
  test('@auth @crud @negative @regression list campaigns without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/settings/marketing/campaigns');
    await logResponseOnFailure(response, 'list campaigns without token');
    expect(response.status()).toBe(401);
  });

  test('@crud @regression list campaigns', async ({ marketingCampaignService }) => {
    const response = await marketingCampaignService.list();
    await logResponseOnFailure(response, 'list campaigns');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression create campaign', async ({ marketingCampaignService, emailTemplateService, marketingEventService }) => {
    const templateResponse = await emailTemplateService.create({
      name: unique('Template'),
      subject: 'Hello',
      content: '<p>Hello</p>',
    });
    const { data: template } = await templateResponse.json();

    const eventResponse = await marketingEventService.create({
      name: unique('Event'),
      description: 'Event description',
      date: '2027-01-01',
    });
    const { data: event } = await eventResponse.json();

    const response = await marketingCampaignService.create({
      name: unique('Campaign'),
      subject: 'Hello',
      marketing_template_id: template.id,
      marketing_event_id: event.id,
      status: 0,
    });
    await logResponseOnFailure(response, 'create campaign');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@validation @crud @negative @regression create campaign with empty payload', async ({ marketingCampaignService }) => {
    const response = await marketingCampaignService.create({});
    await logResponseOnFailure(response, 'create campaign with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show campaign', async ({ marketingCampaignService, emailTemplateService, marketingEventService }) => {
    const templateResponse = await emailTemplateService.create({
      name: unique('Template'),
      subject: 'Hello',
      content: '<p>Hello</p>',
    });
    const { data: template } = await templateResponse.json();

    const eventResponse = await marketingEventService.create({
      name: unique('Event'),
      description: 'Event description',
      date: '2027-01-01',
    });
    const { data: event } = await eventResponse.json();

    const createResponse = await marketingCampaignService.create({
      name: unique('Campaign'),
      subject: 'Hello',
      marketing_template_id: template.id,
      marketing_event_id: event.id,
      status: 0,
    });
    const { data: campaign } = await createResponse.json();

    const response = await marketingCampaignService.getById(campaign.id);
    await logResponseOnFailure(response, 'show campaign');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(campaign.id);
  });

  test('@crud @negative @regression show campaign not found', async ({ marketingCampaignService }) => {
    const response = await marketingCampaignService.getById(999999);
    await logResponseOnFailure(response, 'show campaign not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update campaign', async ({ marketingCampaignService, emailTemplateService, marketingEventService }) => {
    const templateResponse = await emailTemplateService.create({
      name: unique('Template'),
      subject: 'Hello',
      content: '<p>Hello</p>',
    });
    const { data: template } = await templateResponse.json();

    const eventResponse = await marketingEventService.create({
      name: unique('Event'),
      description: 'Event description',
      date: '2027-01-01',
    });
    const { data: event } = await eventResponse.json();

    const createResponse = await marketingCampaignService.create({
      name: unique('Campaign'),
      subject: 'Hello',
      marketing_template_id: template.id,
      marketing_event_id: event.id,
      status: 0,
    });
    const { data: campaign } = await createResponse.json();

    const response = await marketingCampaignService.update(campaign.id, { name: unique('Campaign') });
    await logResponseOnFailure(response, 'update campaign');
    expect(response.status()).toBe(200);
  });

  test('@validation @crud @negative @regression update campaign with empty payload', async ({ marketingCampaignService, emailTemplateService, marketingEventService }) => {
    const templateResponse = await emailTemplateService.create({
      name: unique('Template'),
      subject: 'Hello',
      content: '<p>Hello</p>',
    });
    const { data: template } = await templateResponse.json();

    const eventResponse = await marketingEventService.create({
      name: unique('Event'),
      description: 'Event description',
      date: '2027-01-01',
    });
    const { data: event } = await eventResponse.json();

    const createResponse = await marketingCampaignService.create({
      name: unique('Campaign'),
      subject: 'Hello',
      marketing_template_id: template.id,
      marketing_event_id: event.id,
      status: 0,
    });
    const { data: campaign } = await createResponse.json();

    const response = await marketingCampaignService.update(campaign.id, {});
    await logResponseOnFailure(response, 'update campaign with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression delete campaign', async ({ marketingCampaignService, emailTemplateService, marketingEventService }) => {
    const templateResponse = await emailTemplateService.create({
      name: unique('Template'),
      subject: 'Hello',
      content: '<p>Hello</p>',
    });
    const { data: template } = await templateResponse.json();

    const eventResponse = await marketingEventService.create({
      name: unique('Event'),
      description: 'Event description',
      date: '2027-01-01',
    });
    const { data: event } = await eventResponse.json();

    const createResponse = await marketingCampaignService.create({
      name: unique('Campaign'),
      subject: 'Hello',
      marketing_template_id: template.id,
      marketing_event_id: event.id,
      status: 0,
    });
    const { data: campaign } = await createResponse.json();

    const response = await marketingCampaignService.delete(campaign.id);
    await logResponseOnFailure(response, 'delete campaign');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression delete campaign not found', async ({ marketingCampaignService }) => {
    const response = await marketingCampaignService.delete(999999);
    await logResponseOnFailure(response, 'delete campaign not found');
    expect(response.status()).toBe(404);
  });

  test('@negative @regression wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/settings/marketing/campaigns/1', { data: {} });
    await logResponseOnFailure(response, 'wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@crud @negative @regression issue156: show campaign with invalid id', async ({ marketingCampaignService }) => {
    const response = await marketingCampaignService.getById(999999);
    await logResponseOnFailure(response, 'issue156: show campaign with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue157: update campaign with invalid id', async ({ marketingCampaignService }) => {
    const response = await marketingCampaignService.update(999999, { name: unique('Campaign') });
    await logResponseOnFailure(response, 'issue157: update campaign with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue158: delete campaign with invalid id', async ({ marketingCampaignService }) => {
    const response = await marketingCampaignService.delete(999999);
    await logResponseOnFailure(response, 'issue158: delete campaign with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@acid @crud @negative @regression issue159: mass destroy campaigns with invalid ids', async ({ marketingCampaignService }) => {
    const response = await marketingCampaignService.massDestroy([999999]);
    await logResponseOnFailure(response, 'issue159: mass destroy campaigns with invalid ids');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression issue160: import update returns complete response', async ({ marketingCampaignService, authedApi }) => {
    const createResponse = await marketingCampaignService.create({
      name: unique('Campaign'),
      subject: 'Hello',
      status: 0,
    });
    const { data: campaign } = await createResponse.json();

    const response = await authedApi.put(`/api/v1/settings/marketing/campaigns/${campaign.id}`, {
      data: { name: unique('Campaign'), status: 0 },
    });
    await logResponseOnFailure(response, 'issue160: import update returns complete response');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@crud @regression issue161: import create returns complete response', async ({ marketingCampaignService }) => {
    const response = await marketingCampaignService.create({
      name: unique('Campaign'),
      subject: 'Hello',
      status: 0,
    });
    await logResponseOnFailure(response, 'issue161: import create returns complete response');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });
});
