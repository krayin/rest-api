import { APIResponse, expect } from '@playwright/test';
import { ZodTypeAny, z } from 'zod';

/**
 * Keys whose values must never reach a log line, a report or a failure message.
 */
const SECRET_KEYS = /password|token|secret|api[-_]?key|authorization/i;

const redact = (value: unknown): unknown => {
  if (Array.isArray(value)) {
    return value.map(redact);
  }

  if (value && typeof value === 'object') {
    return Object.fromEntries(
      Object.entries(value as Record<string, unknown>).map(([key, entry]) => [
        key,
        SECRET_KEYS.test(key) ? '***' : redact(entry),
      ]),
    );
  }

  return value;
};

/**
 * Redact secrets out of a raw response body before it is embedded in a failure
 * message. Falls back to the plain text when the body is not JSON.
 */
const redactBody = (body: string): string => {
  try {
    return JSON.stringify(redact(JSON.parse(body)));
  } catch {
    return body.replace(/("(?:[^"]*(?:password|token|secret|api[-_]?key)[^"]*)"\s*:\s*)"[^"]*"/gi, '$1"***"');
  }
};

/**
 * Everything needed to debug a failed API call: method, endpoint, status,
 * content type, request payload and response body — all with secrets redacted.
 */
export async function describeResponse(response: APIResponse, payload?: object): Promise<string> {
  const body = await response.text().catch(() => '(body unavailable)');

  return [
    `${response.url()}`,
    `status: ${response.status()} ${response.statusText()}`,
    `content-type: ${response.headers()['content-type'] ?? '(none)'}`,
    payload ? `payload: ${JSON.stringify(redact(payload))}` : null,
    `body: ${redactBody(body).slice(0, 2000)}`,
  ]
    .filter(Boolean)
    .join('\n');
}

export async function expectStatus(response: APIResponse, status: number, payload?: object): Promise<void> {
  expect(response.status(), await describeResponse(response, payload)).toBe(status);
}

/**
 * A status code alone is never a pass — the content type is asserted too, and
 * the parsed body is handed back for data assertions.
 */
export async function expectJsonResponse(response: APIResponse, status: number, payload?: object): Promise<any> {
  await expectStatus(response, status, payload);

  expect(response.headers()['content-type'] ?? '', await describeResponse(response, payload)).toContain(
    'application/json',
  );

  return response.json();
}

/**
 * Status + headers + schema + body, in one call.
 */
export async function expectSchema<T extends ZodTypeAny>(
  response: APIResponse,
  status: number,
  schema: T,
  payload?: object,
): Promise<z.infer<T>> {
  const body = await expectJsonResponse(response, status, payload);

  const result = schema.safeParse(body);

  expect(
    result.success,
    result.success
      ? ''
      : `schema mismatch:\n${JSON.stringify(result.error.issues, null, 2)}\n${await describeResponse(response, payload)}`,
  ).toBe(true);

  return (result as { data: z.infer<T> }).data;
}

/**
 * Assert a 422 carries a validation error for each of the expected fields.
 */
export async function expectValidationError(response: APIResponse, ...fields: string[]): Promise<any> {
  const body = await expectJsonResponse(response, 422);

  for (const field of fields) {
    expect(Object.keys(body.errors ?? {}), `expected a validation error on "${field}"\n${JSON.stringify(body)}`).toContain(
      field,
    );
  }

  return body;
}

export async function expectUnauthorized(response: APIResponse): Promise<void> {
  await expectStatus(response, 401);
}

export async function expectForbidden(response: APIResponse): Promise<void> {
  await expectStatus(response, 403);
}

export async function expectNotFound(response: APIResponse): Promise<void> {
  await expectStatus(response, 404);
}

/**
 * Nothing in the response leaks a credential, a hash or a token.
 */
export async function expectNoSensitiveData(response: APIResponse): Promise<void> {
  const body = await response.text();

  for (const marker of ['"password"', 'password_hash', '"remember_token"', '"api_token"', '$2y$']) {
    expect(body, `response leaked ${marker}`).not.toContain(marker);
  }
}

/**
 * Assert a paginated listing is internally consistent: the page never exceeds
 * per_page and the reported totals line up with last_page.
 */
export function expectConsistentPagination(
  meta: {
    current_page?: number;
    per_page?: number | string;
    total?: number;
    last_page?: number;
  },
  itemCount: number,
): void {
  expect(meta, 'expected pagination meta').toBeTruthy();

  const perPage = Number(meta.per_page);
  const total = Number(meta.total);

  expect(itemCount, `page returned more items than per_page (${perPage})`).toBeLessThanOrEqual(perPage);
  expect(Number(meta.current_page)).toBeGreaterThan(0);
  expect(Number(meta.last_page)).toBeGreaterThanOrEqual(1);
  expect(Number(meta.last_page), 'last_page does not match total/per_page').toBe(
    Math.max(1, Math.ceil(total / perPage)),
  );
}
