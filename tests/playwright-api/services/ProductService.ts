import { APIResponse } from '@playwright/test';
import { ApiClient } from '../api/ApiClient';
import { Service } from './Service';

export interface ProductData {
  name?: string;
  sku?: string;
  price?: number;
  quantity?: number;
}

export class ProductService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/products');
  }

  async getWarehouses(id: number | string): Promise<APIResponse> {
    return this.client.get(`/api/v1/products/${id}/warehouses`);
  }

  async storeInventories(id: number | string, inventories: any): Promise<APIResponse> {
    return this.client.post(`/api/v1/products/${id}/inventories`, {
      data: { inventories },
    });
  }

  async getActivities(id: number | string): Promise<APIResponse> {
    return this.client.get(`/api/v1/products/${id}/activities`);
  }

  async attachTag(id: number | string, tagId: number): Promise<APIResponse> {
    return this.client.post(`/api/v1/products/${id}/tags`, {
      data: { tag_id: tagId },
    });
  }

  async detachTag(id: number | string, tagId: number): Promise<APIResponse> {
    return this.client.delete(`/api/v1/products/${id}/tags`, {
      data: { tag_id: tagId },
    });
  }
}
