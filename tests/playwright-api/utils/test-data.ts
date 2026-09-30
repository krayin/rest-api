import { randomUUID } from 'node:crypto';

/**
 * Unique, environment-independent test data. Never a fixed id and never a value
 * that could collide between parallel workers.
 */
export const unique = (prefix = 'qa'): string =>
  `${prefix}_${Date.now()}_${process.env.TEST_WORKER_INDEX ?? 0}_${randomUUID().slice(0, 8)}`;

export const uniqueEmail = (prefix = 'qa'): string => `${unique(prefix)}@example.test`;

export const uniquePhone = (): string => String(9_000_000_000 + Math.floor(Math.random() * 999_999_999));

export const personPayload = (overrides: object = {}) => ({
  name: `Person ${unique()}`,
  emails: [{ value: uniqueEmail('person'), label: 'work' }],
  contact_numbers: [{ value: uniquePhone(), label: 'work' }],
  ...overrides,
});

export const organizationPayload = (overrides: object = {}) => ({
  name: `Organization ${unique()}`,
  address: {
    address: '1 Test Street',
    country: 'IN',
    state: 'UP',
    city: 'Noida',
    postcode: '201301',
  },
  ...overrides,
});

export const leadPayload = (overrides: object = {}) => ({
  title: `Lead ${unique()}`,
  description: 'Created by Playwright API automation',
  lead_value: 1000,
  person: personPayload(),
  lead_source_id: 1,
  lead_type_id: 1,
  lead_pipeline_id: 1,
  lead_pipeline_stage_id: 1,
  ...overrides,
});

export const productPayload = (overrides: object = {}) => ({
  sku: unique('sku'),
  name: `Product ${unique()}`,
  description: 'Created by Playwright API automation',
  quantity: 10,
  price: 500,
  ...overrides,
});

/**
 * Fluent builder for the cases where a spec needs to bend one field or drop a
 * required one (validation tests) without restating the whole payload.
 */
export class PayloadBuilder<T extends Record<string, any>> {
  constructor(private payload: T) {}

  with<K extends keyof T>(field: K, value: T[K]): this {
    this.payload[field] = value;

    return this;
  }

  without(field: keyof T): this {
    delete this.payload[field];

    return this;
  }

  build(): T {
    return { ...this.payload };
  }
}

export const buildLead = (overrides: object = {}) => new PayloadBuilder(leadPayload(overrides));
export const buildPerson = (overrides: object = {}) => new PayloadBuilder(personPayload(overrides));
export const buildProduct = (overrides: object = {}) => new PayloadBuilder(productPayload(overrides));
