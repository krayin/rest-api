import { APIResponse } from '@playwright/test';
import { ApiClient } from '../api/ApiClient';
import { Service } from './Service';

export interface MailData {
  subject?: string;
  reply_to?: string[];
  reply?: string;
  is_draft?: boolean;
}

export class MailService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/mails');
  }

  async downloadAttachment(id: number | string): Promise<APIResponse> {
    return this.client.get(`/api/v1/mails/attachment-download/${id}`);
  }

  async massUpdateFolders(indices: number[], folders: string[], value: number): Promise<APIResponse> {
    return this.client.post('/api/v1/mails/mass-update', {
      data: { indices, folders, value },
    });
  }

  async attachTag(id: number | string, tagId: number): Promise<APIResponse> {
    return this.client.post(`/api/v1/mails/${id}/tags`, {
      data: { tag_id: tagId },
    });
  }

  async detachTag(id: number | string, tagId: number): Promise<APIResponse> {
    return this.client.delete(`/api/v1/mails/${id}/tags`, {
      data: { tag_id: tagId },
    });
  }
}
