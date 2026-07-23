<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\Lead\Repositories\SourceRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\SourceResource;

class SourceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SourceRepository $sourceRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $sources = $this->allResources($this->sourceRepository);

        return SourceResource::collection($sources);
    }

    /**
     * Show resource.
     */
    public function show(int $id): SourceResource
    {
        $resource = $this->findOrFailResource($this->sourceRepository, $id);

        return new SourceResource($resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResource
    {
        $this->validate(request(), [
            'name' => 'required|unique:lead_sources,name',
        ]);

        Event::dispatch('settings.source.create.before');

        $source = $this->sourceRepository->create(request()->all());

        Event::dispatch('settings.source.create.after', $source);

        return new JsonResource([
            'data'    => new SourceResource($source),
            'message' => trans('rest-api::app.settings.sources.create-success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): JsonResource
    {
        $this->findOrFailResource($this->sourceRepository, $id);

        $this->validate(request(), [
            'name' => 'required|unique:lead_sources,name,'.$id,
        ]);

        Event::dispatch('settings.source.update.before', $id);

        $source = $this->sourceRepository->update(request()->all(), $id);

        Event::dispatch('settings.source.update.after', $source);

        return new JsonResource([
            'data'    => new SourceResource($source),
            'message' => trans('rest-api::app.settings.sources.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResource|JsonResponse
    {
        return $this->destroyResource(
            $this->sourceRepository,
            $id,
            'rest-api::app.settings.sources.delete-success',
            'settings.source',
            'rest-api::app.settings.sources.delete-failed',
        );
    }
}
