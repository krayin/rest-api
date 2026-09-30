import { APIRequestContext, APIResponse } from '@playwright/test';

export interface ApiClientConfig {
  baseUrl: string;
  token?: string;
  defaultHeaders?: Record<string, string>;
}

export interface RequestOptions {
  headers?: Record<string, string>;
  params?: Record<string, string | number | boolean>;
  data?: any;
  timeout?: number;
}

let sequence = 0;

export function unique(prefix: string): string {
  return `${prefix}_${Date.now() % 1_000_000_000}_${++sequence}`;
}

export function uniqueNumber(): string {
  return String(1_000_000_000 + (Date.now() % 1_000_000_000));
}

export async function waitForNextSecond(): Promise<void> {
  const now = Date.now();
  const ms = 1000 - (now % 1000);
  return new Promise(resolve => setTimeout(resolve, ms));
}

export class ApiClient {
  private requestContext: APIRequestContext;
  private config: ApiClientConfig;

  constructor(requestContext: APIRequestContext, config: ApiClientConfig) {
    this.requestContext = requestContext;
    this.config = config;
  }

  private getUrl(path: string, params?: Record<string, string | number | boolean>): string {
    const baseUrl = this.config.baseUrl.endsWith('/') ? this.config.baseUrl : `${this.config.baseUrl}/`;
    const cleanPath = path.startsWith('/') ? path.substring(1) : path;
    const url = new URL(cleanPath, baseUrl);
    if (params) {
      Object.entries(params).forEach(([key, value]) => {
        url.searchParams.append(key, String(value));
      });
    }
    return url.toString();
  }

  private getDefaultHeaders(): Record<string, string> {
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...this.config.defaultHeaders,
    };
  }

  private getAuthHeaders(): Record<string, string> {
    const headers: Record<string, string> = {};
    if (this.config.token) {
      headers['Authorization'] = `Bearer ${this.config.token}`;
    }
    return headers;
  }

  async request(method: string, path: string, options: RequestOptions = {}): Promise<APIResponse> {
    const url = this.getUrl(path, options.params);
    const headers = {
      ...this.getDefaultHeaders(),
      ...this.getAuthHeaders(),
      ...options.headers,
    };

    return this.requestContext.fetch(url, {
      method: method as any,
      headers,
      data: options.data,
      timeout: options.timeout,
    });
  }

  async get(path: string, options: RequestOptions = {}): Promise<APIResponse> {
    return this.request('GET', path, options);
  }

  async post(path: string, options: RequestOptions = {}): Promise<APIResponse> {
    return this.request('POST', path, options);
  }

  async put(path: string, options: RequestOptions = {}): Promise<APIResponse> {
    return this.request('PUT', path, options);
  }

  async patch(path: string, options: RequestOptions = {}): Promise<APIResponse> {
    return this.request('PATCH', path, options);
  }

  async delete(path: string, options: RequestOptions = {}): Promise<APIResponse> {
    return this.request('DELETE', path, options);
  }

  setToken(token: string): void {
    this.config.token = token;
  }

  getToken(): string | undefined {
    return this.config.token;
  }
}
