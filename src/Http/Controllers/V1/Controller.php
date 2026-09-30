<?php

namespace Webkul\RestApi\Http\Controllers\V1;

use Illuminate\Validation\ValidationException;
use Webkul\Core\Eloquent\Repository;
use Webkul\RestApi\Http\Controllers\RestApiController;

class Controller extends RestApiController
{
    /**
     * Exclude keys which not needed during searching.
     *
     * @var array
     */
    protected $excludeKeys = [
        'entity_type',
        'limit',
        'page',
        'pagination',
        'order',
        'sort',
    ];

    /**
     * Add entity type.
     *
     * @return void
     */
    protected function addEntityTypeInRequest($entityType)
    {
        request()->request->add(['entity_type' => $entityType]);
    }

    /**
     * Returns a listing of the resource.
     *
     * @return Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    protected function allResources(Repository $repository)
    {
        $query = $repository->query();

        /**
         * Filter and sort names arrive straight from the query string and were passed to the
         * query builder unchecked, so an unknown column produced an unhandled SQL error (a 500)
         * rather than a validation failure. Both are now matched against the real columns of the
         * table being queried.
         */
        $columns = $this->tableColumns($query);

        foreach (request()->except($this->excludeKeys) as $input => $value) {
            if (! in_array($input, $columns, true)) {
                throw ValidationException::withMessages([
                    $input => trans('validation.in', ['attribute' => $input]),
                ]);
            }

            $query = $query->whereIn($input, array_map('trim', explode(',', (string) $value)));
        }

        if ($sort = request()->input('sort')) {
            if (! in_array($sort, $columns, true)) {
                throw ValidationException::withMessages([
                    'sort' => trans('validation.in', ['attribute' => 'sort']),
                ]);
            }

            $order = strtolower((string) (request()->input('order') ?? 'desc'));

            if (! in_array($order, ['asc', 'desc'], true)) {
                throw ValidationException::withMessages([
                    'order' => trans('validation.in', ['attribute' => 'order']),
                ]);
            }

            $query = $query->orderBy($sort, $order);
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        if (is_null(request()->input('pagination')) || request()->input('pagination')) {
            return $query->paginate(request()->input('limit') ?? 10);
        }

        return $query->get();
    }

    /**
     * Column names of the table the given query targets, cached per request.
     *
     * @param  mixed  $query
     * @return array<int, string>
     */
    protected function tableColumns($query): array
    {
        static $cache = [];

        $table = $query->getModel()->getTable();

        if (! isset($cache[$table])) {
            $cache[$table] = $query->getConnection()
                ->getSchemaBuilder()
                ->getColumnListing($table);
        }

        return $cache[$table];
    }

    /**
     * Return a message-only error response carrying the given HTTP status code.
     *
     * `new JsonResource([...], $code)` does not work: JsonResource's constructor takes only the
     * resource, so the status code is discarded and the response is sent as 200. Authorization
     * failures in particular must be detectable by status code, not just by message text.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(string $message, int $statusCode)
    {
        return response()->json(['message' => $message], $statusCode);
    }

    /**
     * Return the user ids the authenticated user is allowed to see, or null for unrestricted.
     *
     * This mirrors `bouncer()->getAuthorizedUserIds()`, but resolves the acting user from the
     * request rather than the `user` guard. The core helper reads `auth()->guard('user')`, which
     * is always null on an API request authenticated through Sanctum, so calling it here raises
     * "Attempt to read property view_permission on null".
     *
     * @return array<int>|null
     */
    protected function authorizedUserIds(): ?array
    {
        $user = auth()->guard()->user();

        if ($user->view_permission == 'global') {
            return null;
        }

        if ($user->view_permission == 'group') {
            return $user->groups()
                ->with('users:id')
                ->get()
                ->pluck('users')
                ->flatten()
                ->pluck('id')
                ->unique()
                ->values()
                ->toArray();
        }

        return [$user->id];
    }
}
