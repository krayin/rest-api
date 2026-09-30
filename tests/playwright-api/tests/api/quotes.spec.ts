import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';

test.describe('Quotes API', () => {
  test('@auth @crud @negative @regression list quotes without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/quotes');
    await logResponseOnFailure(response, 'list quotes without token');
    expect(response.status()).toBe(401);
  });

  test('@smoke @crud @regression list quotes', async ({ quoteService }) => {
    const response = await quoteService.list();
    await logResponseOnFailure(response, 'list quotes');
    expect(response.status()).toBe(200);
  });

  test('@smoke @crud @regression create quote success', async ({ personService, productService, quoteService }) => {
    const personResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: person } = await personResponse.json();

    const productResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await productResponse.json();

    const response = await quoteService.create({
      subject: unique('Quote'),
      person_id: person.id,
      items: [{ product_id: product.id, quantity: 2, price: 50 }],
    });

    await logResponseOnFailure(response, 'create quote success');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
    expect(body.message).toContain('created successfully');
  });

  test('@validation @crud @negative @regression create quote with empty payload', async ({ quoteService }) => {
    const response = await quoteService.create({});
    await logResponseOnFailure(response, 'create quote with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression create quote with invalid item quantity', async ({ personService, quoteService }) => {
    const personResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: person } = await personResponse.json();

    const response = await quoteService.create({
      subject: unique('Quote'),
      person_id: person.id,
      items: [{
        quantity: -1, price: 50,
        product_id: 0
      }],
    });
    await logResponseOnFailure(response, 'create quote with invalid item quantity');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show quote', async ({ personService, productService, quoteService }) => {
    const personResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: person } = await personResponse.json();

    const productResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await productResponse.json();

    const createResponse = await quoteService.create({
      subject: unique('Quote'),
      person_id: person.id,
      items: [{ product_id: product.id, quantity: 2, price: 50 }],
    });
    const { data: quote } = await createResponse.json();

    const response = await quoteService.getById(quote.id);
    await logResponseOnFailure(response, 'show quote');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(quote.id);
  });

  test('@crud @negative @regression show quote not found', async ({ quoteService }) => {
    const response = await quoteService.getById(999999);
    await logResponseOnFailure(response, 'show quote not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update quote', async ({ personService, productService, quoteService }) => {
    const personResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: person } = await personResponse.json();

    const productResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await productResponse.json();

    const createResponse = await quoteService.create({
      subject: unique('Quote'),
      person_id: person.id,
      items: [{ product_id: product.id, quantity: 2, price: 50 }],
    });
    const { data: quote } = await createResponse.json();

    const updatePersonResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: updatePerson } = await updatePersonResponse.json();

    const response = await quoteService.update(quote.id, {
      subject: unique('Quote'),
      person_id: updatePerson.id,
    });
    await logResponseOnFailure(response, 'update quote');
    expect(response.status()).toBe(200);
  });

  test('@validation @crud @negative @regression update quote with invalid date', async ({ personService, productService, quoteService }) => {
    const personResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: person } = await personResponse.json();

    const productResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await productResponse.json();

    const createResponse = await quoteService.create({
      subject: unique('Quote'),
      person_id: person.id,
      items: [{ product_id: product.id, quantity: 2, price: 50 }],
    });
    const { data: quote } = await createResponse.json();

    const updatePersonResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: updatePerson } = await updatePersonResponse.json();

    const response = await quoteService.update(quote.id, {
      subject: unique('Quote'),
      person_id: updatePerson.id,
      expired_at: 'not-a-date',
    });
    await logResponseOnFailure(response, 'update quote with invalid date');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression delete quote', async ({ personService, productService, quoteService }) => {
    const personResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: person } = await personResponse.json();

    const productResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await productResponse.json();

    const createResponse = await quoteService.create({
      subject: unique('Quote'),
      person_id: person.id,
      items: [{ product_id: product.id, quantity: 2, price: 50 }],
    });
    const { data: quote } = await createResponse.json();

    const response = await quoteService.delete(quote.id);
    await logResponseOnFailure(response, 'delete quote');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.message).toContain('deleted successfully');
  });

  test('@crud @negative @regression delete quote not found', async ({ quoteService }) => {
    const response = await quoteService.delete(999999);
    await logResponseOnFailure(response, 'delete quote not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression export quotes', async ({ quoteService }) => {
    const response = await quoteService.export();
    await logResponseOnFailure(response, 'export quotes');
    expect(response.status()).toBe(200);
  });

  test('@pagination @regression search quotes', async ({ quoteService }) => {
    const response = await quoteService.search('Test');
    await logResponseOnFailure(response, 'search quotes');
    expect(response.status()).toBe(200);
  });

  test('@acid @crud @regression mass destroy quotes', async ({ personService, productService, quoteService }) => {
    const personResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: person } = await personResponse.json();

    const productResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await productResponse.json();

    const createResponse = await quoteService.create({
      subject: unique('Quote'),
      person_id: person.id,
      items: [{ product_id: product.id, quantity: 2, price: 50 }],
    });
    const { data: quote } = await createResponse.json();

    const response = await quoteService.massDestroy([quote.id]);
    await logResponseOnFailure(response, 'mass destroy quotes');
    expect(response.status()).toBe(200);
  });

  test('@validation @acid @crud @negative @regression mass destroy quotes with missing indices', async ({ quoteService }) => {
    const response = await quoteService.massDestroyRaw({});
    await logResponseOnFailure(response, 'mass destroy quotes with missing indices');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression quote items', async ({ personService, productService, quoteService }) => {
    const personResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: person } = await personResponse.json();

    const productResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await productResponse.json();

    const createResponse = await quoteService.create({
      subject: unique('Quote'),
      person_id: person.id,
      items: [{ product_id: product.id, quantity: 2, price: 50 }],
    });
    const { data: quote } = await createResponse.json();

    const response = await quoteService.getItems(quote.id);
    await logResponseOnFailure(response, 'quote items');
    expect(response.status()).toBe(200);
  });

  test('@negative @regression quote items for missing quote', async ({ quoteService }) => {
    const response = await quoteService.getItems(999999);
    await logResponseOnFailure(response, 'quote items for missing quote');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression quote lead products', async ({ leadService, quoteService }) => {
    const leadResponse = await leadService.create({
      title: unique('Lead'),
      lead_source_id: 1,
      lead_type_id: 1,
      lead_pipeline_id: 1,
      lead_pipeline_stage_id: 1,
    });
    const { data: lead } = await leadResponse.json();

    const response = await quoteService.getLeadProducts(lead.id);
    await logResponseOnFailure(response, 'quote lead products');
    expect(response.status()).toBe(200);
  });

  test('@negative @regression quote lead products not found', async ({ quoteService }) => {
    const response = await quoteService.getLeadProducts(999999);
    await logResponseOnFailure(response, 'quote lead products not found');
    expect(response.status()).toBe(404);
  });

  test('@negative @regression quote mail fails without mail server', async ({ personService, productService, quoteService }) => {
    const personResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: person } = await personResponse.json();

    const productResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await productResponse.json();

    const createResponse = await quoteService.create({
      subject: unique('Quote'),
      person_id: person.id,
      items: [{ product_id: product.id, quantity: 2, price: 50 }],
    });
    const { data: quote } = await createResponse.json();

    const response = await quoteService.sendMail(quote.id, {
      to: unique('mail') + '@example.com',
      subject: 'Quote',
      body: 'Hello',
    });
    await logResponseOnFailure(response, 'quote mail fails without mail server');
    expect(response.status()).toBe(500);
  });

  test('@negative @regression quote with wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/quotes/1', { data: {} });
    await logResponseOnFailure(response, 'quote with wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@validation @crud @negative @regression issue40: create quote with non-existent person', async ({ quoteService }) => {
    const response = await quoteService.create({
      subject: unique('Quote'),
      person_id: 999999,
      items: [{ product_id: 1, quantity: 1, price: 10 }],
    });
    await logResponseOnFailure(response, 'issue40: create quote with non-existent person');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression issue41: create quote with past expired date', async ({ quoteService, personService }) => {
    const personResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: person } = await personResponse.json();

    const response = await quoteService.create({
      subject: unique('Quote'),
      person_id: person.id,
      expired_at: '2020-01-01',
      items: [{ product_id: 1, quantity: 1, price: 10 }],
    });
    await logResponseOnFailure(response, 'issue41: create quote with past expired date');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression issue42: create quote with invalid user id', async ({ quoteService, personService }) => {
    const personResponse = await personService.create({
      name: unique('Person'),
      emails: [{ value: unique('person') + '@example.com', label: 'work' }],
    });
    const { data: person } = await personResponse.json();

    const response = await quoteService.create({
      subject: unique('Quote'),
      person_id: person.id,
      user_id: 999999,
      items: [{ product_id: 1, quantity: 1, price: 10 }],
    });
    await logResponseOnFailure(response, 'issue42: create quote with invalid user id');
    expect(response.status()).toBe(422);
  });

  test('@pagination @negative @regression issue43: search quotes with non-matching person name returns empty', async ({ quoteService }) => {
    const response = await quoteService.search('NonExistentPersonXYZ12345');
    await logResponseOnFailure(response, 'issue43: search quotes with non-matching person name returns empty');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.length).toBe(0);
  });

  test('@crud @negative @regression issue44: show quote with invalid id', async ({ quoteService }) => {
    const response = await quoteService.getById(999999);
    await logResponseOnFailure(response, 'issue44: show quote with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue45: update quote with invalid id', async ({ quoteService }) => {
    const response = await quoteService.update(999999, { subject: unique('Quote') });
    await logResponseOnFailure(response, 'issue45: update quote with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue46: delete quote with invalid id', async ({ quoteService }) => {
    const response = await quoteService.delete(999999);
    await logResponseOnFailure(response, 'issue46: delete quote with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@acid @crud @negative @regression issue47: mass destroy quotes with invalid ids', async ({ quoteService }) => {
    const response = await quoteService.massDestroy([999999]);
    await logResponseOnFailure(response, 'issue47: mass destroy quotes with invalid ids');
    expect(response.status()).toBe(404);
  });
});
