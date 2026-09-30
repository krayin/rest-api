import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';
import { LeadService } from '../../services/LeadService';
import { ProductService } from '../../services/ProductService';
import { TagService } from '../../services';

test.describe('Leads API', () => {
  async function createLead(leadService: LeadService): Promise<string> {
    const response = await leadService.create({
      title: unique('Lead'),
      lead_source_id: 1,
      lead_type_id: 1,
      lead_pipeline_id: 1,
      lead_pipeline_stage_id: 1,
    });
    const body = await response.json();
    return String(body.data.id);
  }

  async function createProduct(productService: ProductService): Promise<string> {
    const response = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const body = await response.json();
    return String(body.data.id);
  }

  async function createTag(tagService: TagService): Promise<string> {
    const response = await tagService.create({ name: unique('Tag') });
    const body = await response.json();
    return String(body.data.id);
  }

  test('@auth @crud @negative @regression list leads without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/leads');
    await logResponseOnFailure(response, 'list leads without token');
    expect(response.status()).toBe(401);
  });

  test('@smoke @crud @regression list leads', async ({ leadService }) => {
    const response = await leadService.list();
    await logResponseOnFailure(response, 'list leads');
    expect(response.status()).toBe(200);
  });

  test('@smoke @crud @regression create lead success', async ({ leadService }) => {
    const response = await leadService.create({
      title: unique('Lead'),
      lead_source_id: 1,
      lead_type_id: 1,
      lead_pipeline_id: 1,
      lead_pipeline_stage_id: 1,
    });

    await logResponseOnFailure(response, 'create lead success');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
    expect(body.message).toContain('created successfully');
  });

  test('@crud @negative @regression create lead with empty payload', async ({ leadService }) => {
    const response = await leadService.create({});
    await logResponseOnFailure(response, 'create lead with empty payload');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@crud @negative @regression create lead with invalid source', async ({ leadService }) => {
    const response = await leadService.create({
      title: unique('Lead'),
      lead_source_id: 9999,
      lead_type_id: 1,
      lead_pipeline_id: 1,
      lead_pipeline_stage_id: 1,
    });
    await logResponseOnFailure(response, 'create lead with invalid source');
    expect(response.status()).toBe(500);
  });

  test('@crud @regression show lead', async ({ leadService }) => {
    const id = await createLead(leadService);
    const response = await leadService.getById(id);
    await logResponseOnFailure(response, 'show lead');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(parseInt(id));
  });

  test('@crud @negative @regression show lead not found', async ({ leadService }) => {
    const response = await leadService.getById(999999);
    await logResponseOnFailure(response, 'show lead not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update lead', async ({ leadService }) => {
    const id = await createLead(leadService);
    const response = await leadService.update(id, {
      title: unique('Lead'),
      lead_source_id: 1,
      lead_type_id: 1,
      lead_pipeline_id: 1,
      lead_pipeline_stage_id: 1,
    });
    await logResponseOnFailure(response, 'update lead');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression update lead with empty payload', async ({ leadService }) => {
    const id = await createLead(leadService);
    const response = await leadService.update(id, {});
    await logResponseOnFailure(response, 'update lead with empty payload');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression delete lead', async ({ leadService }) => {
    const id = await createLead(leadService);
    const response = await leadService.delete(id);
    await logResponseOnFailure(response, 'delete lead');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.message).toContain('deleted successfully');
  });

  test('@crud @negative @regression delete lead not found', async ({ leadService }) => {
    const response = await leadService.delete(999999);
    await logResponseOnFailure(response, 'delete lead not found');
    expect(response.status()).toBe(404);
  });

  test('@pagination @regression search leads', async ({ leadService }) => {
    const response = await leadService.search('Test');
    await logResponseOnFailure(response, 'search leads');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression export leads', async ({ leadService }) => {
    const response = await leadService.export();
    await logResponseOnFailure(response, 'export leads');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression leads get stages', async ({ leadService }) => {
    const response = await leadService.getStages();
    await logResponseOnFailure(response, 'leads get stages');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression leads get stages with pipeline', async ({ leadService }) => {
    const response = await leadService.getStagesByPipeline(1);
    await logResponseOnFailure(response, 'leads get stages with pipeline');
    expect(response.status()).toBe(200);
  });

  test('@validation @negative @regression kanban lookup without fields', async ({ leadService }) => {
    const response = await leadService.kanbanLookup('', '');
    await logResponseOnFailure(response, 'kanban lookup without fields');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.column[0]).toContain('required');
    expect(body.errors.search[0]).toContain('required');
  });

  test('@acid @crud @regression mass update leads', async ({ leadService }) => {
    const id = await createLead(leadService);
    const response = await leadService.massUpdate([parseInt(id)], 1);
    await logResponseOnFailure(response, 'mass update leads');
    expect(response.status()).toBe(200);
  });

  test('@validation @acid @crud @negative @regression mass update leads with missing value', async ({ leadService }) => {
    const response = await leadService.massUpdate([1], undefined);
    await logResponseOnFailure(response, 'mass update leads with missing value');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.value[0]).toContain('required');
  });

  test('@acid @crud @regression mass destroy leads', async ({ leadService }) => {
    const id = await createLead(leadService);
    const response = await leadService.massDestroy([parseInt(id)]);
    await logResponseOnFailure(response, 'mass destroy leads');
    expect(response.status()).toBe(200);
  });

  test('@validation @acid @crud @negative @regression mass destroy leads with missing indices', async ({ leadService }) => {
    const response = await leadService.massDestroy([]);
    await logResponseOnFailure(response, 'mass destroy leads with missing indices');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression update lead stage', async ({ leadService }) => {
    const id = await createLead(leadService);
    const response = await leadService.updateStage(id, 5);
    await logResponseOnFailure(response, 'update lead stage');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression update lead attributes', async ({ leadService }) => {
    const id = await createLead(leadService);
    const response = await leadService.updateAttributes(id, { title: unique('Lead') });
    await logResponseOnFailure(response, 'update lead attributes');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression lead add product', async ({ leadService, productService }) => {
    const leadId = await createLead(leadService);
    const productId = await createProduct(productService);
    const response = await leadService.addProduct(leadId, parseInt(productId), 2, 50);
    await logResponseOnFailure(response, 'lead add product');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.product_id).toBeTruthy();
  });

  test('@validation @crud @negative @regression lead add product with missing product id', async ({ leadService }) => {
    const leadId = await createLead(leadService);
    const response = await leadService.addProduct(leadId, undefined, undefined, undefined);
    await logResponseOnFailure(response, 'lead add product with missing product id');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression lead remove product', async ({ leadService, productService }) => {
    const leadId = await createLead(leadService);
    const productId = await createProduct(productService);

    await leadService.addProduct(leadId, parseInt(productId), 2, 50);

    const response = await leadService.removeProduct(leadId);
    await logResponseOnFailure(response, 'lead remove product');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.message).toContain('removed');
  });

  test('@crud @regression lead activities', async ({ leadService }) => {
    const id = await createLead(leadService);
    const response = await leadService.getActivities(id);
    await logResponseOnFailure(response, 'lead activities');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression lead emails', async ({ leadService }) => {
    const id = await createLead(leadService);
    const response = await leadService.createEmail(id, {
      subject: unique('Mail'),
      reply: 'reply body',
      reply_to: [unique('mail') + '@example.com'],
    });
    await logResponseOnFailure(response, 'lead emails');
    expect(response.status()).toBe(200);
  });

  test('@validation @negative @regression lead emails with missing reply', async ({ leadService }) => {
    const id = await createLead(leadService);
    const response = await leadService.createEmail(id, {
      subject: '',
      reply: '',
      reply_to: [],
    });
    await logResponseOnFailure(response, 'lead emails with missing reply');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression lead attach tag', async ({ leadService, tagService }) => {
    const leadId = await createLead(leadService);
    const tagId = await createTag(tagService);
    const response = await leadService.attachTag(leadId, parseInt(tagId));
    await logResponseOnFailure(response, 'lead attach tag');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.message).toContain('attached successfully');
  });

  test('@validation @crud @negative @regression lead attach tag with missing tag id', async ({ leadService }) => {
    const leadId = await createLead(leadService);
    const response = await leadService.attachTag(leadId, undefined);
    await logResponseOnFailure(response, 'lead attach tag with missing tag id');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.tag_id[0]).toContain('required');
  });

  test('@crud @regression lead detach tag', async ({ leadService, tagService }) => {
    const leadId = await createLead(leadService);
    const tagId = await createTag(tagService);

    await leadService.attachTag(leadId, parseInt(tagId));

    const response = await leadService.detachTag(leadId, parseInt(tagId));
    await logResponseOnFailure(response, 'lead detach tag');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.message).toContain('detached successfully');
  });

  test('@negative @regression lead with wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/leads/1', { data: {} });
    await logResponseOnFailure(response, 'lead with wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@crud @negative @regression create lead by ai without files', async ({ leadService }) => {
    const response = await leadService.createByAi();
    await logResponseOnFailure(response, 'create lead by ai without files');
    expect(response.status()).toBe(500);
  });

  test('@crud @regression issue15: create lead with minimal payload', async ({ leadService }) => {
    const response = await leadService.create({ title: unique('Lead') });
    await logResponseOnFailure(response, 'issue15: create lead with minimal payload');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@validation @crud @negative @regression issue25: create lead with invalid source id', async ({ leadService }) => {
    const response = await leadService.create({
      title: unique('Lead'),
      lead_source_id: 999999,
      lead_type_id: 1,
      lead_pipeline_id: 1,
      lead_pipeline_stage_id: 1,
    });
    await logResponseOnFailure(response, 'issue25: create lead with invalid source id');
    expect(response.status()).toBe(422);
  });

  test('@pagination @negative @regression issue26: search leads with non-matching title returns empty', async ({ leadService }) => {
    const response = await leadService.search('NonExistentLeadTitle12345XYZ');
    await logResponseOnFailure(response, 'issue26: search leads with non-matching title returns empty');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.length).toBe(0);
  });

  test('@crud @negative @regression issue27: remove product not attached to lead', async ({ leadService }) => {
    const leadId = await createLead(leadService);
    const response = await leadService.removeProduct(leadId, { product_id: 999999 });
    await logResponseOnFailure(response, 'issue27: remove product not attached to lead');
    expect(response.status()).toBe(404);
  });

  test('@validation @crud @negative @regression issue28: attach tag with invalid id to lead', async ({ leadService }) => {
    const leadId = await createLead(leadService);
    const response = await leadService.attachTag(leadId, 999999);
    await logResponseOnFailure(response, 'issue28: attach tag with invalid id to lead');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue29: detach tag not attached to lead returns 404', async ({ leadService, tagService }) => {
    const leadId = await createLead(leadService);
    const tagId = await createTag(tagService);

    await leadService.attachTag(leadId, parseInt(tagId));

    const detachResponse = await leadService.detachTag(leadId, parseInt(tagId));
    await logResponseOnFailure(detachResponse, 'issue29: detach tag first time');
    expect(detachResponse.status()).toBe(200);

    const secondDetachResponse = await leadService.detachTag(leadId, parseInt(tagId));
    await logResponseOnFailure(secondDetachResponse, 'issue29: detach tag second time');
    expect(secondDetachResponse.status()).toBe(404);
  });
});
