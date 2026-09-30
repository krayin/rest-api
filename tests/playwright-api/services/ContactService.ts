import { APIResponse } from '@playwright/test';
import { ApiClient } from '../api/ApiClient';
import { Service } from './Service';

export interface PersonData {
  name?: string;
  emails?: Array<{ value: string; label: string }>;
}

export interface OrganizationData {
  name?: string;
  address?: {
    country?: string;
    state?: string;
    city?: string;
    address?: string;
    postcode?: string;
  };
}

export class PersonService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/contacts/persons');
  }

  /**
   * Persons expose no `emails` sub-resource — kept so the spec asserting that
   * (404) goes through the service layer rather than a raw URL.
   */
  async createEmail(id: number | string, data: any): Promise<APIResponse> {
    return this.client.post(`/api/v1/contacts/persons/${id}/emails`, { data });
  }

  async getActivities(id: number | string): Promise<APIResponse> {
    return this.client.get(`/api/v1/contacts/persons/${id}/activities`);
  }

  async attachTag(id: number | string, tagId: number): Promise<APIResponse> {
    return this.client.post(`/api/v1/contacts/persons/${id}/tags`, {
      data: { tag_id: tagId },
    });
  }

  async detachTag(id: number | string, tagId: number): Promise<APIResponse> {
    return this.client.delete(`/api/v1/contacts/persons/${id}/tags`, {
      data: { tag_id: tagId },
    });
  }
}

export class OrganizationService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/contacts/organizations');
  }
}
