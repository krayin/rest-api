import { APIResponse } from '@playwright/test';
import { ApiClient } from '../api/ApiClient';

export class AuthService {
  private client: ApiClient;

  constructor(client: ApiClient) {
    this.client = client;
  }

  async login(data: { email: string; password: string; device_name?: string }): Promise<APIResponse> {
    return this.client.post('/api/v1/login', {
      data: {
        ...data,
        device_name: data.device_name,
      },
    });
  }

  async logout(): Promise<APIResponse> {
    return this.client.delete('/api/v1/logout');
  }

  async getAccount(): Promise<APIResponse> {
    return this.client.get('/api/v1/get');
  }
}
