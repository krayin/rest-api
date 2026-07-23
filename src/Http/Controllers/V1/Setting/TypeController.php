<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\Lead\Repositories\TypeRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\TypeResource;

class TypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected TypeRepository $typeRepository) {}

    /**
     * Display a listing of the type.
     */
    public function index(): JsonResource
    {
        $types = $this->allResources($this->typeRepository);

        return TypeResource::collection($types);
    }

    /**
     * Show the specified resource.
     */
    public function show(int $id): TypeResource
    {
        $resource = $this->findOrFailResource($this->typeRepository, $id);

        return new TypeResource($resource);
    }

    /**
     * Store a newly created type in storage.
     */
    public function store(): JsonResource
    {
        $this->validate(request(), [
            'name' => 'required|unique:lead_types,name',
        ]);

        Event::dispatch('settings.type.create.before');

        $type = $this->typeRepository->create(request()->all());

        Event::dispatch('settings.type.create.after', $type);

        return new JsonResource([
            'data'    => new TypeResource($type),
            'message' => trans('rest-api::app.settings.types.create-success'),
        ]);
    }

    /**
     * Update the specified type in storage.
     */
    public function update(int $id): JsonResource
    {
        $this->findOrFailResource($this->typeRepository, $id);

        $this->validate(request(), [
            'name' => 'required|unique:lead_types,name,'.$id,
        ]);

        Event::dispatch('settings.type.update.before', $id);

        $type = $this->typeRepository->update(request()->all(), $id);

        Event::dispatch('settings.type.update.after', $type);

        return new JsonResource([
            'data'    => new TypeResource($type),
            'message' => trans('rest-api::app.settings.types.update-success'),
        ]);
    }

    /**
     * Remove the specified type from storage.
     */
    public function destroy(int $id): JsonResource|JsonResponse
    {
        return $this->destroyResource(
            $this->typeRepository,
            $id,
            'rest-api::app.settings.types.delete-success',
            'settings.type',
            'rest-api::app.settings.types.delete-failed',
        );
    }
}
