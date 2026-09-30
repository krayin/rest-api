import { APIResponse } from '@playwright/test';
import { ApiClient, RequestOptions } from '../api/ApiClient';

export class Service {
  protected client: ApiClient;
  protected basePath: string;

  constructor(client: ApiClient, basePath: string) {
    this.client = client;
    this.basePath = basePath;
  }

  async list(options?: RequestOptions): Promise<APIResponse> {
    return this.client.get(this.basePath, options);
  }

  async getById(id: number | string, options?: RequestOptions): Promise<APIResponse> {
    return this.client.get(`${this.basePath}/${id}`, options);
  }

  async create(data: any, options?: RequestOptions): Promise<APIResponse> {
    return this.client.post(this.basePath, { data, ...options });
  }

  async update(id: number | string, data: any, options?: RequestOptions): Promise<APIResponse> {
    return this.client.put(`${this.basePath}/${id}`, { data, ...options });
  }

  async delete(id: number | string, options?: RequestOptions): Promise<APIResponse> {
    return this.client.delete(`${this.basePath}/${id}`, options);
  }

  async search(query: string, options?: RequestOptions): Promise<APIResponse> {
    return this.client.get(`${this.basePath}/search`, {
      params: { query },
      ...options,
    });
  }

  /**
   * Post an arbitrary body to `mass-destroy` — for the validation specs that
   * deliberately omit or malform `indices`.
   */
  async massDestroyRaw(data: any, options?: RequestOptions): Promise<APIResponse> {
    return this.client.post(`${this.basePath}/mass-destroy`, { data, ...options });
  }

  /**
   * Post an arbitrary body to `mass-update` — for the validation specs that
   * deliberately omit or malform the payload.
   */
  async massUpdateRaw(data: any, options?: RequestOptions): Promise<APIResponse> {
    return this.client.post(`${this.basePath}/mass-update`, { data, ...options });
  }

  async massDestroy(indices: number[], options?: RequestOptions): Promise<APIResponse> {
    return this.client.post(`${this.basePath}/mass-destroy`, {
      data: { indices },
      ...options,
    });
  }

  async massUpdate(indices: number[], value: any, options?: RequestOptions): Promise<APIResponse> {
    return this.client.post(`${this.basePath}/mass-update`, {
      data: { indices, value },
      ...options,
    });
  }

  async export(options?: RequestOptions): Promise<APIResponse> {
    return this.client.post(`${this.basePath}/export`, options);
  }
}
