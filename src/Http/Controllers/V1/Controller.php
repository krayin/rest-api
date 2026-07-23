<?php

namespace Webkul\RestApi\Http\Controllers\V1;

use Webkul\Core\Eloquent\Repository;
use Webkul\RestApi\Http\Controllers\RestApiController;
use Webkul\RestApi\Http\Controllers\V1\Concerns\ManagesResources;
use Webkul\RestApi\Http\Controllers\V1\Concerns\RespondsWithJson;
use Webkul\RestApi\Http\Controllers\V1\Concerns\SanitizesListQuery;

class Controller extends RestApiController
{
    use ManagesResources;
    use RespondsWithJson;
    use SanitizesListQuery;

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
    protected function allResources(Repository $repository, array $with = [])
    {
        $query = $repository->query();

        if ($with) {
            $query = $query->with($with);
        }

        return $this->applyListQuery($query);
    }
}
