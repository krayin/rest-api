import { test, expect, unique, logResponseOnFailure } from '../../fixtures/api.fixture';
import { OrganizationService } from '../../services/ContactService';

test.describe('Contacts Organizations API', () => {
  async function createOrganization(organizationService: OrganizationService): Promise<string> {
    const response = await organizationService.create({ name: unique('Org') });
    const body = await response.json();
    return String(body.data.id);
  }

  test('@auth @crud @negative @regression list organizations without token', async ({ apiClient }) => {
    const response = await apiClient.get('/api/v1/contacts/organizations');
    await logResponseOnFailure(response, 'list organizations without token');
    expect(response.status()).toBe(401);
  });

  test('@smoke @crud @regression list organizations', async ({ organizationService }) => {
    const response = await organizationService.list();
    await logResponseOnFailure(response, 'list organizations');
    expect(response.status()).toBe(200);
  });

  test('@smoke @crud @regression create organization success', async ({ organizationService }) => {
    const response = await organizationService.create({ name: unique('Org') });

    await logResponseOnFailure(response, 'create organization success');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBeTruthy();
    expect(body.message).toContain('created successfully');
  });

  test('@crud @negative @regression create organization with empty payload', async ({ organizationService }) => {
    const response = await organizationService.create({});
    await logResponseOnFailure(response, 'create organization with empty payload');
    expect(response.status()).toBe(500);
  });

  test('@validation @crud @negative @regression create organization with invalid country', async ({ organizationService }) => {
    const response = await organizationService.create({
      name: 'Org',
      address: { country: 'ZZ' },
    });
    await logResponseOnFailure(response, 'create organization with invalid country');
    expect(response.status()).toBe(422);
  });

  test('@crud @regression show organization', async ({ organizationService }) => {
    const id = await createOrganization(organizationService);
    const response = await organizationService.getById(id);
    await logResponseOnFailure(response, 'show organization');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.id).toBe(parseInt(id));
  });

  test('@crud @negative @regression show organization not found', async ({ organizationService }) => {
    const response = await organizationService.getById(999999);
    await logResponseOnFailure(response, 'show organization not found');
    expect(response.status()).toBe(404);
  });

  test('@crud @regression update organization', async ({ organizationService }) => {
    const id = await createOrganization(organizationService);
    const response = await organizationService.update(id, { name: unique('Org') });
    await logResponseOnFailure(response, 'update organization');
    expect(response.status()).toBe(200);
  });

  test('@crud @negative @regression update organization with empty payload', async ({ organizationService }) => {
    const id = await createOrganization(organizationService);
    const response = await organizationService.update(id, {});
    await logResponseOnFailure(response, 'update organization with empty payload');
    expect(response.status()).toBe(200);
  });

  test('@crud @regression delete organization', async ({ organizationService }) => {
    const id = await createOrganization(organizationService);
    const response = await organizationService.delete(id);
    await logResponseOnFailure(response, 'delete organization');
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.data.message).toContain('deleted successfully');
  });

  test('@crud @negative @regression delete organization not found', async ({ organizationService }) => {
    const response = await organizationService.delete(999999);
    await logResponseOnFailure(response, 'delete organization not found');
    expect(response.status()).toBe(404);
  });


  test('@acid @crud @regression mass destroy organizations', async ({ organizationService }) => {
    const id = await createOrganization(organizationService);
    const response = await organizationService.massDestroy([parseInt(id)]);
    await logResponseOnFailure(response, 'mass destroy organizations');
    expect(response.status()).toBe(200);
  });

  test('@validation @acid @crud @negative @regression mass destroy organizations with missing indices', async ({ organizationService }) => {
    const response = await organizationService.massDestroy([]);
    await logResponseOnFailure(response, 'mass destroy organizations with missing indices');
    expect(response.status()).toBe(422);
  });

  test('@negative @regression organization with wrong http method', async ({ authedApi }) => {
    const response = await authedApi.delete('/api/v1/contacts/organizations/1/export');
    await logResponseOnFailure(response, 'organization with wrong http method');
    expect(response.status()).toBe(404);
  });

  test('@validation @crud @negative @regression issue48: create organization with invalid country', async ({ organizationService }) => {
    const response = await organizationService.create({
      name: unique('Org'),
      address: { country: 'ZZ' },
    });
    await logResponseOnFailure(response, 'issue48: create organization with invalid country');
    expect(response.status()).toBe(422);
  });

  test('@crud @negative @regression issue49: show organization with invalid id', async ({ organizationService }) => {
    const response = await organizationService.getById(999999);
    await logResponseOnFailure(response, 'issue49: show organization with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue50: update organization with invalid id', async ({ organizationService }) => {
    const response = await organizationService.update(999999, { name: unique('Org') });
    await logResponseOnFailure(response, 'issue50: update organization with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@crud @negative @regression issue51: delete organization with invalid id', async ({ organizationService }) => {
    const response = await organizationService.delete(999999);
    await logResponseOnFailure(response, 'issue51: delete organization with invalid id');
    expect(response.status()).toBe(404);
  });

  test('@acid @crud @negative @regression issue52: mass destroy organizations with invalid ids', async ({ organizationService }) => {
    const response = await organizationService.massDestroy([999999]);
    await logResponseOnFailure(response, 'issue52: mass destroy organizations with invalid ids');
    expect(response.status()).toBe(404);
  });
});
