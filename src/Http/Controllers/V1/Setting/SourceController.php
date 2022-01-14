<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Support\Facades\Event;
use Webkul\Lead\Repositories\SourceRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\SourceResource;

class SourceController extends Controller
{
    /**
     * Source repository instance.
     *
     * @var \Webkul\Lead\Repositories\SourceRepository
     */
    protected $sourceRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Lead\Repositories\SourceRepository  $sourceRepository
     * @return void
     */
    public function __construct(SourceRepository $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sources = $this->allResources($this->sourceRepository);

        return SourceResource::collection($sources);
    }

    /**
     * Show resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->sourceRepository->find($id);

        return new SourceResource($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|unique:lead_sources,name',
        ]);

        Event::dispatch('settings.source.create.before');

        $source = $this->sourceRepository->create(request()->all());

        Event::dispatch('settings.source.create.after', $source);

        return response([
            'data'    => new SourceResource($source),
            'message' => __('admin::app.settings.sources.create-success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'name' => 'required|unique:lead_sources,name,' . $id,
        ]);

        Event::dispatch('settings.source.update.before', $id);

        $source = $this->sourceRepository->update(request()->all(), $id);

        Event::dispatch('settings.source.update.after', $source);

        return response([
            'data'    => new SourceResource($source),
            'message' => __('admin::app.settings.sources.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Event::dispatch('settings.source.delete.before', $id);

            $this->sourceRepository->delete($id);

            Event::dispatch('settings.source.delete.after', $id);

            return response([
                'message' => __('admin::app.settings.sources.delete-success'),
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => __('admin::app.settings.sources.delete-failed'),
            ], 500);
        }
    }
}
