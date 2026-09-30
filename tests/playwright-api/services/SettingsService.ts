import { APIResponse } from '@playwright/test';
import { ApiClient } from '../api/ApiClient';
import { Service } from './Service';

export class TagService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/tags');
  }

  async massUpdateNotSupported(): Promise<APIResponse> {
    return this.client.post('/api/v1/settings/tags/mass-update', {
      data: { indices: [1], value: { name: 'x' } },
    });
  }
}

export class RoleService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/roles');
  }
}

export class PipelineService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/pipelines');
  }
}

export class StageService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/stages');
  }
}

export class SourceService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/sources');
  }
}

export class TypeService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/types');
  }
}

export class UserService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/users');
  }
}

export class WarehouseService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/warehouses');
  }

  async getLocations(warehouseId: number | string): Promise<APIResponse> {
    return this.client.get(`/api/v1/settings/locations`, {
      params: { warehouse_id: warehouseId },
    });
  }

  async getActivities(warehouseId: number | string): Promise<APIResponse> {
    return this.client.get(`${this.basePath}/${warehouseId}/activities`);
  }

  async attachTag(warehouseId: number | string, data: any): Promise<APIResponse> {
    return this.client.post(`${this.basePath}/${warehouseId}/tags`, { data });
  }

  async detachTag(warehouseId: number | string, tagId: number | string): Promise<APIResponse> {
    return this.client.delete(`${this.basePath}/${warehouseId}/tags/${tagId}`);
  }
}

export class LocationService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/locations');
  }
}

export class GroupService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/groups');
  }
}

export class AttributeService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/attributes');
  }
}

export class WebhookService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/webhooks');
  }
}

export class WorkflowService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/workflows');
  }
}

export class EmailTemplateService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/email-templates');
  }
}

export class WebFormService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/web-forms');
  }
}

export class MarketingEventService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/marketing/events');
  }
}

export class MarketingCampaignService extends Service {
  constructor(client: ApiClient) {
    super(client, '/api/v1/settings/marketing/campaigns');
  }
}
