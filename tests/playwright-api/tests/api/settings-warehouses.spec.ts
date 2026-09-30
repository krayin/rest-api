import { test, expect, unique, uniqueNumber, logResponseOnFailure } from '../../fixtures/api.fixture';
import { LocationService } from '../../services';

test.describe('Settings Warehouses API', () => {
  test('@auth @crud @negative @regression list warehouses without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/settings/warehouses');
    await logResponseOnFailure(response, 'list warehouses without token');
    expect(response.status()).toBe(401);
  });

  test('@crud @regression list warehouses', async ({ warehouseService }) => {
    const response = await warehouseService.list();
    await logResponseOnFailure(response, 'list warehouses');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression create warehouse', async ({ warehouseService }) => {
    const response = await warehouseService.create({
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
    await logResponseOnFailure(response, 'create warehouse');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
  });

  test('@validation @crud @negative @regression create warehouse with empty payload', async ({ warehouseService }) => {
    const response = await warehouseService.create({});
    await logResponseOnFailure(response, 'create warehouse with empty payload');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show warehouse', async ({ warehouseService }) => {
    const createResponse = await warehouseService.create({
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
    const { data } = await createResponse.json();

    const response = await warehouseService.getById(data.id);
    await logResponseOnFailure(response, 'show warehouse');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(data.id);
  });

  test('@crud @negative @regression show warehouse not found', async ({ warehouseService }) => {
    const response = await warehouseService.getById(999999);
    await logResponseOnFailure(response, 'show warehouse not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update warehouse', async ({ warehouseService }) => {
    const createResponse = await warehouseService.create({
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
    const { data } = await createResponse.json();

    const response = await warehouseService.update(data.id, { name: unique('Warehouse') });
    await logResponseOnFailure(response, 'update warehouse');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression update warehouse with empty payload', async ({ warehouseService }) => {
    const createResponse = await warehouseService.create({
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
    const { data } = await createResponse.json();

    const response = await warehouseService.update(data.id, {});
    await logResponseOnFailure(response, 'update warehouse with empty payload');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression delete warehouse', async ({ warehouseService }) => {
    const createResponse = await warehouseService.create({
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
    const { data } = await createResponse.json();

    const response = await warehouseService.delete(data.id);
    await logResponseOnFailure(response, 'delete warehouse');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression delete warehouse not found', async ({ warehouseService }) => {
    const response = await warehouseService.delete(999999);
    await logResponseOnFailure(response, 'delete warehouse not found');
    expect(response.status()).toBe(404);
  });

  test('@negative @regression wrong http method', async ({ authedApi }) => {
    const response = await authedApi.patch('/api/v1/settings/warehouses/1', { data: {} });
    await logResponseOnFailure(response, 'wrong http method');
    expect(response.status()).toBe(405);
  });

  test('@validation @crud @negative @regression issue140: create location with invalid warehouse id', async ({ authedApi }) => {
    const locationService = new LocationService(authedApi);
    const response = await locationService.create({
      name: unique('Location'),
      warehouse_id: 999999,
    });
    await logResponseOnFailure(response, 'issue140: create location with invalid warehouse id');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression issue141: create warehouse with invalid country', async ({ warehouseService }) => {
    const response = await warehouseService.create({
      name: unique('Warehouse'),
      contact_name: 'John',
      contact_emails: [{ value: unique('wh') + '@example.com', label: 'work' }],
      contact_numbers: [{ value: uniqueNumber(), label: 'work' }],
      contact_address: {
        address: 'Street 1',
        country: 'ZZ',
        state: 'AL',
        city: 'Montgomery',
        postcode: '36101',
      },
    });
    await logResponseOnFailure(response, 'issue141: create warehouse with invalid country');
    expect(response.status()).toBe(422);
  });

  test('@validation @crud @negative @regression issue142: create location with duplicate name', async ({ authedApi, warehouseService }) => {
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

    const name = unique('Location');
    await locationService.create({ name, warehouse_id: warehouse.id });
    const response = await locationService.create({ name, warehouse_id: warehouse.id });
    await logResponseOnFailure(response, 'issue142: create location with duplicate name');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue144: delete location with invalid id', async ({ authedApi }) => {
    const locationService = new LocationService(authedApi);
    const response = await locationService.delete(999999);
    await logResponseOnFailure(response, 'issue144: delete location with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue146: detach unattached tag from warehouse', async ({ warehouseService }) => {
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
    const response = await warehouseService.detachTag(warehouse.id, 999999);
    await logResponseOnFailure(response, 'issue146: detach unattached tag from warehouse');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression issue143: update location returns success message', async ({ authedApi, warehouseService }) => {
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
    const createResponse = await locationService.create({
      name: unique('Location'),
      warehouse_id: warehouse.id,
    });
    const { data: location } = await createResponse.json();

    const response = await locationService.update(location.id, { name: unique('Location') });
    await logResponseOnFailure(response, 'issue143: update location returns success message');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.message).toContain('Location updated successfully');
  });

  test('@crud @regression issue145: warehouse activities endpoint available', async ({ warehouseService }) => {
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
    const response = await warehouseService.getActivities(warehouse.id);
    await logResponseOnFailure(response, 'issue145: warehouse activities endpoint available');
    expect(response.status()).toBe(200);
  });
});
