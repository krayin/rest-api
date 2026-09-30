import {
  test,
  expect,
  expectJsonResponse,
  expectValidationError,
  expectSchema,
  expectConsistentPagination,
} from '../../fixtures/api.fixture';
import { collectionSchema, leadSchema } from '../../schemas/api-schemas';

/**
 * The listing contract is implemented once in `SanitizesListQuery` and shared by
 * every list endpoint, so it is asserted once here against a representative
 * resource rather than repeated per resource.
 */
test.describe('Listing contract', () => {
  test('@smoke @pagination @crud @regression a listing returns a valid paginated collection', async ({ leadService }) => {
    const body = await expectSchema(await leadService.list(), 200, collectionSchema(leadSchema));

    expect(body.meta, 'a default listing must be paginated').toBeDefined();

    expectConsistentPagination(body.meta!, body.data.length);
  });

  test('@pagination @regression limit caps the page size', async ({ leadService }) => {
    const body = await expectJsonResponse(await leadService.list({ params: { limit: 2 } }), 200);

    expect(body.data.length).toBeLessThanOrEqual(2);
    expect(Number(body.meta.per_page)).toBe(2);
  });

  test('@pagination @regression page navigation returns a different slice', async ({ leadService }) => {
    const first = await expectJsonResponse(await leadService.list({ params: { limit: 1, page: 1 } }), 200);

    test.skip(first.meta.total < 2, 'needs at least two leads to compare pages');

    const second = await expectJsonResponse(await leadService.list({ params: { limit: 1, page: 2 } }), 200);

    expect(second.data[0].id).not.toBe(first.data[0].id);
    expect(second.meta.current_page).toBe(2);
  });

  test('@pagination @regression pagination=0 returns a flat collection without meta', async ({ leadService }) => {
    const body = await expectJsonResponse(await leadService.list({ params: { pagination: 0 } }), 200);

    expect(Array.isArray(body.data)).toBe(true);
    expect(body.meta).toBeUndefined();
  });

  test('@pagination @negative @validation @regression an unknown sort column is rejected', async ({ leadService }) => {
    await expectValidationError(await leadService.list({ params: { sort: 'no_such_column' } }), 'sort');
  });

  test('@pagination @regression an invalid order value falls back to descending', async ({ leadService }) => {
    const body = await expectJsonResponse(await leadService.list({ params: { sort: 'id', order: 'sideways' } }), 200);

    const ids = body.data.map((lead: any) => lead.id);

    expect(ids).toEqual([...ids].sort((a: number, b: number) => b - a));
  });

  test('@pagination @regression default ordering is id descending', async ({ leadService }) => {
    const body = await expectJsonResponse(await leadService.list(), 200);

    const ids = body.data.map((lead: any) => lead.id);

    expect(ids).toEqual([...ids].sort((a: number, b: number) => b - a));
  });

  test('@security @negative @regression an unknown filter param is ignored, never executed', async ({ leadService }) => {
    const baseline = await expectJsonResponse(await leadService.list(), 200);

    const injected = await expectJsonResponse(
      await leadService.list({ params: { 'id) OR 1=1--': '1' } }),
      200,
    );

    expect(injected.meta.total, 'an unknown parameter must not reach SQL').toBe(baseline.meta.total);
  });
});
