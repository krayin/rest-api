import { APIResponse } from '@playwright/test';
import { ApiClient } from '../api/ApiClient';
import { Service } from './Service';

export interface ActivityData {
  type?: 'note' | 'call' | 'meeting' | 'email' | 'other';
  comment?: string;
  schedule_from?: string;
  schedule_to?: string;
}

export class ActivityService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/activities');
  }

  async downloadFile(id: number | string): Promise<APIResponse> {
    return this.client.get(`/api/v1/activities/file-download/${id}`);
  }
}
