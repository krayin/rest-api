import { APIResponse } from '@playwright/test';
import { ApiClient } from '../api/ApiClient';
import { Service } from './Service';

export interface QuoteData {
  subject?: string;
  description?: string;
  person_id?: number;
  user_id?: number;
  lead_id?: number;
  expired_at?: string;
  billing_address?: Record<string, any>;
  shipping_address?: Record<string, any>;
  discount_percent?: number;
  discount_amount?: number;
  tax_amount?: number;
  adjustment_amount?: number;
  items?: Array<{ product_id: number; quantity: number; price: number; [key: string]: any }>;
  [key: string]: any;
}

export class QuoteService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/quotes');
  }

  async create(data: QuoteData, options?: any): Promise<APIResponse> {
    return this.client.post(this.basePath, { data, ...options });
  }

  async getItems(id: number | string): Promise<APIResponse> {
    return this.client.get(`/api/v1/quotes/${id}/items`);
  }

  async getLeadProducts(leadId: number | string): Promise<APIResponse> {
    return this.client.get(`/api/v1/quotes/lead-products/${leadId}`);
  }
  

  async sendMail(id: number | string, data: { to: string; subject: string; body: string }): Promise<APIResponse> {
    return this.client.post(`/api/v1/quotes/${id}/mail`, { data });
  }
}
