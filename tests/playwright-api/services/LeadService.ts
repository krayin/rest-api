import { APIResponse } from '@playwright/test';
import { ApiClient } from '../api/ApiClient';
import { Service } from './Service';

export interface LeadData {
  title?: string;
  lead_source_id?: number;
  lead_type_id?: number;
  lead_pipeline_id?: number;
  lead_pipeline_stage_id?: number;
}

export class LeadService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/leads');
  }

  async getStages(): Promise<APIResponse> {
    return this.client.get('/api/v1/leads/get');
  }

  async getStagesByPipeline(pipelineId: number): Promise<APIResponse> {
    return this.client.get(`/api/v1/leads/get/${pipelineId}`);
  }

  async kanbanLookup(column: string, search: string): Promise<APIResponse> {
    return this.client.get('/api/v1/leads/kanban/look-up', {
      params: { column, search },
    });
  }

  async updateStage(id: number | string, stageId: number): Promise<APIResponse> {
    return this.client.put(`/api/v1/leads/stage/edit/${id}`, {
      data: { lead_pipeline_stage_id: stageId },
    });
  }

  async updateAttributes(id: number | string, data: any): Promise<APIResponse> {
    return this.client.put(`/api/v1/leads/attributes/edit/${id}`, { data });
  }

  async addProduct(leadId: number | string, productId: number, quantity: number, price: number): Promise<APIResponse> {
    return this.client.put(`/api/v1/leads/product/${leadId}`, {
      data: { product_id: productId, quantity, price },
    });
  }

  async removeProduct(leadId: number | string, data?: { product_id: number }): Promise<APIResponse> {
    return this.client.delete(`/api/v1/leads/product/${leadId}`, data ? { data } : undefined);
  }

  async getActivities(id: number | string): Promise<APIResponse> {
    return this.client.get(`/api/v1/leads/${id}/activities`);
  }

  async getEmails(id: number | string): Promise<APIResponse> {
    return this.client.get(`/api/v1/leads/${id}/emails`);
  }

  async createEmail(id: number | string, data: { subject: string; reply_to: string[]; reply: string }): Promise<APIResponse> {
    return this.client.post(`/api/v1/leads/${id}/emails`, { data });
  }

  async getQuotes(id: number | string): Promise<APIResponse> {
    return this.client.get(`/api/v1/leads/${id}/quotes`);
  }

  async attachTag(id: number | string, tagId: number): Promise<APIResponse> {
    return this.client.post(`/api/v1/leads/${id}/tags`, {
      data: { tag_id: tagId },
    });
  }

  async detachTag(id: number | string, tagId: number): Promise<APIResponse> {
    return this.client.delete(`/api/v1/leads/${id}/tags`, {
      data: { tag_id: tagId },
    });
  }

  async createByAi(): Promise<APIResponse> {
    return this.client.post('/api/v1/leads/create-by-ai');
  }
}
