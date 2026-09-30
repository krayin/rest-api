<?php

namespace Webkul\RestApi\Http\Controllers\V1;

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

        foreach (request()->except($this->excludeKeys) as $input => $value) {
            $query = $query->whereIn($input, array_map('trim', explode(',', $value)));
        }

        if ($sort = request()->input('sort')) {
            $query = $query->orderBy($sort, request()->input('order') ?? 'desc');
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        if (is_null(request()->input('pagination')) || request()->input('pagination')) {
            return $query->paginate(request()->input('limit') ?? 10);
        }

        return $query->get();
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
