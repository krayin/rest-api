import { test, expect, unique, uniqueNumber, logResponseOnFailure } from '../../fixtures/api.fixture';
import { LocationService } from '../../services';

test.describe('Products API', () => {
  test('@auth @crud @negative @regression list products without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/products');
    await logResponseOnFailure(response, 'list products without token');
    expect(response.status()).toBe(401);
  });

  test('@smoke @crud @regression list products', async ({ productService }) => {
    const response = await productService.list();
    await logResponseOnFailure(response, 'list products');
    expect(response.status()).toBe(200);
  });

  test('@smoke @crud @regression create product success', async ({ productService }) => {
    const response = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });

    await logResponseOnFailure(response, 'create product success');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
    expect(body.message).toContain('created successfully');
  });

  test('@validation @crud @negative @regression create product with non-numeric price', async ({ productService }) => {
    const response = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 'not-a-number',
    });
    await logResponseOnFailure(response, 'create product with non-numeric price');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.price[0]).toContain('must be a decimal');
  });

  test('@validation @crud @negative @regression create product with negative price', async ({ productService }) => {
    const response = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: -5,
    });
    await logResponseOnFailure(response, 'create product with negative price');
    expect(response.status()).toBe(422);
    const body = await response.json();
    expect(body.errors.price[0]).toContain('must be a decimal');
  });

  test('@crud @regression show product', async ({ productService }) => {
    const createResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data } = await createResponse.json();
    const response = await productService.getById(data.id);
    await logResponseOnFailure(response, 'show product');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(data.id);
  });

  test('@crud @negative @regression show product not found', async ({ productService }) => {
    const response = await productService.getById(999999);
    await logResponseOnFailure(response, 'show product not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update product', async ({ productService }) => {
    const createResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data } = await createResponse.json();
    const response = await productService.update(data.id, {
      name: unique('Product'),
      sku: unique('SKU'),
      price: 200,
      quantity: 20,
    });
    await logResponseOnFailure(response, 'update product');
    expect(response.status()).toBe(200);
  });

  test('@validation @crud @negative @regression update product with invalid price', async ({ productService }) => {
    const createResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data } = await createResponse.json();
    const response = await productService.update(data.id, { price: -1 });
    await logResponseOnFailure(response, 'update product with invalid price');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression delete product', async ({ productService }) => {
    const createResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data } = await createResponse.json();
    const response = await productService.delete(data.id);
    await logResponseOnFailure(response, 'delete product');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.message).toContain('deleted successfully');
  });

  test('@crud @negative @regression delete product not found', async ({ productService }) => {
    const response = await productService.delete(999999);
    await logResponseOnFailure(response, 'delete product not found');
    expect(response.status()).toBe(404);
  });

  test('@pagination @regression search products', async ({ productService }) => {
    const response = await productService.search('Test');
    await logResponseOnFailure(response, 'search products');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression export products', async ({ productService }) => {
    const response = await productService.export();
    await logResponseOnFailure(response, 'export products');
    expect(response.status()).toBe(200);
  });

  test('@acid @crud @regression mass destroy products', async ({ productService }) => {
    const createResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data } = await createResponse.json();
    const response = await productService.massDestroy([data.id]);
    await logResponseOnFailure(response, 'mass destroy products');
    expect(response.status()).toBe(200);
  });

  test('@validation @acid @crud @negative @regression mass destroy products with missing indices', async ({ productService }) => {
    const response = await productService.massDestroyRaw({});
    await logResponseOnFailure(response, 'mass destroy products with missing indices');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression product warehouses', async ({ productService }) => {
    const createResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data } = await createResponse.json();
    const response = await productService.getWarehouses(data.id);
    await logResponseOnFailure(response, 'product warehouses');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression product store inventories', async ({ authedApi, productService, warehouseService }) => {
    const createResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await createResponse.json();

    const warehouseResponse = await warehouseService.create({
      name: unique('Warehouse'),
      contact_name: 'John',
      contact_emails: [{ value: unique('wh') + '@example.com', label: 'work' }],
      contact_numbers: [{ value: uniqueNumber(), label: 'work' }],
      contact_address: {
        address: 'Street 1',
        country: 'US',
        state: 'AL',
        city: 'Montgomery',
        postcode: '36101',
      },
    });
    const { data: warehouse } = await warehouseResponse.json();

    const locationService = new LocationService(authedApi);
    const locationResponse = await locationService.create({
      name: unique('Location'),
      warehouse_id: warehouse.id,
    });
    const { data: location } = await locationResponse.json();

    const response = await productService.storeInventories(product.id, {
      inventory_1: {
        warehouse_location_id: location.id,
        warehouse_id: warehouse.id,
        in_stock: 5,
        allocated: 0,
      },
    });
    await logResponseOnFailure(response, 'product store inventories');
    expect(response.status()).toBe(200);
  });

  test('@validation @crud @negative @regression product store inventories with missing stock', async ({ productService }) => {
    const createResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data } = await createResponse.json();
    const response = await productService.storeInventories(data.id, {
      inventory_1: {},
    });
    await logResponseOnFailure(response, 'product store inventories with missing stock');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression product activities', async ({ productService }) => {
    const createResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data } = await createResponse.json();
    const response = await productService.getActivities(data.id);
    await logResponseOnFailure(response, 'product activities');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression product attach tag', async ({ productService, tagService }) => {
    const productResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await productResponse.json();

    const tagResponse = await tagService.create({ name: unique('Tag') });
    const { data: tag } = await tagResponse.json();

    const response = await productService.attachTag(product.id, tag.id);
    await logResponseOnFailure(response, 'product attach tag');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.message).toContain('attached successfully');
  });

  test('@validation @crud @negative @regression product attach tag with missing tag id', async ({ authedApi, productService }) => {
    const productResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await productResponse.json();

    const response = await authedApi.post(`/api/v1/products/${product.id}/tags`, {
      data: {},
    });
    await logResponseOnFailure(response, 'product attach tag with missing tag id');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression product detach tag', async ({ productService, tagService }) => {
    const productResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await productResponse.json();

    const tagResponse = await tagService.create({ name: unique('Tag') });
    const { data: tag } = await tagResponse.json();

    const response = await productService.detachTag(product.id, tag.id);
    await logResponseOnFailure(response, 'product detach tag');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.message).toContain('detached successfully');
  });

  test('@negative @regression product with wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/products/1', { data: {} });
    await logResponseOnFailure(response, 'product with wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@validation @crud @negative @regression issue60: create product with negative quantity', async ({ productService }) => {
    const response = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: -5,
    });
    await logResponseOnFailure(response, 'issue60: create product with negative quantity');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue61: product inventory endpoint not supported via GET', async ({ authedApi }) => {
    const response = await authedApi.get('/api/v1/products/1/inventories');
    await logResponseOnFailure(response, 'issue61: product inventory endpoint not supported via GET');
    expect(response.status()).toBe(405);
  });

  test('@validation @crud @negative @regression issue62: store inventories with negative stock', async ({ authedApi, productService, warehouseService }) => {
    const createResponse = await productService.create({
      name: unique('Product'),
      sku: unique('SKU'),
      price: 100,
      quantity: 10,
    });
    const { data: product } = await createResponse.json();

    const warehouseResponse = await warehouseService.create({
      name: unique('Warehouse'),
      contact_name: 'John',
      contact_emails: [{ value: unique('wh') + '@example.com', label: 'work' }],
      contact_numbers: [{ value: uniqueNumber(), label: 'work' }],
      contact_address: {
        address: 'Street 1',
        country: 'US',
        state: 'AL',
        city: 'Montgomery',
        postcode: '36101',
      },
    });
    const { data: warehouse } = await warehouseResponse.json();

    const { LocationService } = await import('../../services');
    const locationService = new LocationService(authedApi);
    const locationResponse = await locationService.create({
      name: unique('Location'),
      warehouse_id: warehouse.id,
    });
    const { data: location } = await locationResponse.json();

    const response = await productService.storeInventories(product.id, {
      inventory_1: {
        warehouse_location_id: location.id,
        warehouse_id: warehouse.id,
        in_stock: -5,
        allocated: 0,
      },
    });
    await logResponseOnFailure(response, 'issue62: store inventories with negative stock');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue63: show product with invalid id', async ({ productService }) => {
    const response = await productService.getById(999999);
    await logResponseOnFailure(response, 'issue63: show product with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue64: update product with invalid id', async ({ productService }) => {
    const response = await productService.update(999999, { name: unique('Product') });
    await logResponseOnFailure(response, 'issue64: update product with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue65: delete product with invalid id', async ({ productService }) => {
    const response = await productService.delete(999999);
    await logResponseOnFailure(response, 'issue65: delete product with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@acid @crud @negative @regression issue66: mass destroy products with invalid ids', async ({ productService }) => {
    const response = await productService.massDestroy([999999]);
    await logResponseOnFailure(response, 'issue66: mass destroy products with invalid ids');
    expect(response.status()).toBe(404);
  });
});
