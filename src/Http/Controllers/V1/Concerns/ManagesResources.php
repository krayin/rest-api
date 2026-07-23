<?php

namespace Webkul\RestApi\Http\Controllers\V1\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

/**
 * Shared CRUD helpers for the V1 controllers: find-or-404, single delete, and
 * mass delete with an accurate deleted-count.
 *
 * Expects the consuming controller to also use {@see RespondsWithJson}.
 */
trait ManagesResources
{
    /**
     * Fetch a single resource by id or fail with a 404.
     *
     * Centralizes the `findOrFail` call so `show`/`update` never pass a null
     * model into a JsonResource (the root cause of the "500 on non-existent
     * ID" reports). The thrown ModelNotFoundException is rendered as a friendly
     * JSON 404 by the package exception handler.
     *
     * @param  int|string  $id
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function findOrFailResource(Repository $repository, $id)
    {
        return $repository->findOrFail($id);
    }

    /**
     * Delete a single resource, guaranteeing a 404 for unknown ids and a real
     * status code / message on failure.
     *
     * @param  int|string  $id
     */
    protected function destroyResource(
        Repository $repository,
        $id,
        string $messageKey,
        ?string $eventPrefix = null,
        ?string $failedMessageKey = null
    ): JsonResource|JsonResponse {
        $resource = $repository->findOrFail($id);

        try {
            if ($eventPrefix) {
                Event::dispatch("{$eventPrefix}.delete.before", $id);
            }

            $repository->delete($id);

            if ($eventPrefix) {
                Event::dispatch("{$eventPrefix}.delete.after", $id);
            }

            return $this->respondSuccess(trans($messageKey));
        } catch (\Exception $exception) {
            return $this->respondError(
                trans($failedMessageKey ?? $messageKey),
                500
            );
        }
    }

    /**
     * Delete many resources, skipping ids that do not exist and reporting the
     * real number of deleted rows so the caller can avoid the misleading
     * "deleted successfully" message when nothing matched.
     *
     * @return array{deleted: int, total: int}
     */
    protected function massDestroyResources(
        Repository $repository,
        array $indices,
        ?string $eventPrefix = null
    ): array {
        $deleted = 0;

        foreach ($indices as $id) {
            $resource = $repository->find($id);

            if (! $resource) {
                continue;
            }

            if ($eventPrefix) {
                Event::dispatch("{$eventPrefix}.delete.before", $id);
            }

            $resource->delete();

            if ($eventPrefix) {
                Event::dispatch("{$eventPrefix}.delete.after", $id);
            }

            $deleted++;
        }

        return [
            'deleted' => $deleted,
            'total'   => count($indices),
        ];
    }
}
