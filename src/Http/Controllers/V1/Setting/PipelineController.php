<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\Rule;
use Webkul\Lead\Repositories\PipelineRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\PipelineResource;
use Webkul\RestApi\Support\PipelineStageNormalizer;

class PipelineController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PipelineRepository $pipelineRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $pipelines = $this->allResources($this->pipelineRepository);

        return PipelineResource::collection($pipelines);
    }

    /**
     * Show resource.
     */
    public function show(int $id): PipelineResource
    {
        $resource = $this->findOrFailResource($this->pipelineRepository, $id);

        return new PipelineResource($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is done inline (not via the core `PipelineForm`, whose
     * create-vs-update detection keys off `request('id')` — a route parameter,
     * not body input, in this REST context). `stages` is required and each write
     * runs inside a transaction so a mid-write failure can never leave a
     * half-created pipeline.
     */
    public function store(Request $request): JsonResource
    {
        $this->validate($request, [
            'name'                 => 'required|unique:lead_pipelines,name',
            'rotten_days'          => 'sometimes|nullable|integer|min:0',
            'stages'               => 'required|array|min:1',
            'stages.*.name'        => 'required|string|distinct',
            'stages.*.code'        => 'required|string|distinct',
            'stages.*.probability' => 'sometimes|integer|min:0|max:100',
            'stages.*.sort_order'  => 'sometimes|integer',
        ]);

        $data = array_merge($request->all(), [
            'is_default' => $request->boolean('is_default') ? 1 : 0,
            'stages'     => (new PipelineStageNormalizer)->normalize($request->input('stages', [])),
        ]);

        Event::dispatch('settings.pipeline.create.before');

        $pipeline = DB::transaction(fn () => $this->pipelineRepository->create($data));

        Event::dispatch('settings.pipeline.create.after', $pipeline);

        return new JsonResource([
            'data'    => new PipelineResource($pipeline),
            'message' => trans('rest-api::app.settings.pipelines.create-success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * Normalizes the incoming `stages` list into the associative shape the core
     * `PipelineRepository::update()` expects (numeric key = existing stage to
     * update, `stage_<n>` key = new stage to create; existing stages absent from
     * the payload are removed = sync). The whole write runs in a transaction so
     * the previous "500 + partial write" (scalars saved, stages left broken)
     * cannot happen.
     */
    public function update(Request $request, int $id): JsonResource
    {
        $this->findOrFailResource($this->pipelineRepository, $id);

        $this->validate($request, [
            'name'                 => 'required|unique:lead_pipelines,name,'.$id,
            'rotten_days'          => 'sometimes|nullable|integer|min:0',
            'stages'               => 'required|array|min:1',
            'stages.*.id'          => [
                'sometimes',
                'integer',
                Rule::exists('lead_pipeline_stages', 'id')->where('lead_pipeline_id', $id),
            ],
            'stages.*.name'        => 'required|string|distinct',
            'stages.*.code'        => 'required|string|distinct',
            'stages.*.probability' => 'sometimes|integer|min:0|max:100',
            'stages.*.sort_order'  => 'sometimes|integer',
        ]);

        $data = array_merge($request->all(), [
            'is_default' => $request->boolean('is_default') ? 1 : 0,
            'stages'     => (new PipelineStageNormalizer)->normalize($request->input('stages', [])),
        ]);

        Event::dispatch('settings.pipeline.update.before', $id);

        $pipeline = DB::transaction(fn () => $this->pipelineRepository->update($data, $id));

        Event::dispatch('settings.pipeline.update.after', $pipeline);

        return new JsonResource([
            'data'    => new PipelineResource($pipeline),
            'message' => trans('rest-api::app.settings.pipelines.updated-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResource|JsonResponse
    {
        $pipeline = $this->pipelineRepository->findOrFail($id);

        if ($pipeline->is_default) {
            return $this->respondError(
                trans('rest-api::app.settings.pipelines.default-delete-error'),
                400,
            );
        }

        $defaultPipeline = $this->pipelineRepository->getDefaultPipeline();

        try {
            Event::dispatch('settings.pipeline.delete.before', $id);

            /**
             * Reassign the pipeline's leads to the default pipeline and delete
             * the pipeline atomically: if the delete fails, the lead
             * reassignment is rolled back so leads are never silently relocated
             * while the source pipeline still exists (previously a partial
             * write, the reassignment ran outside the try/catch).
             */
            DB::transaction(function () use ($pipeline, $defaultPipeline, $id) {
                $pipeline->leads()->update([
                    'lead_pipeline_id'       => $defaultPipeline->id,
                    'lead_pipeline_stage_id' => $defaultPipeline->stages()->first()->id,
                ]);

                $this->pipelineRepository->delete($id);
            });

            Event::dispatch('settings.pipeline.delete.after', $id);

            return $this->respondSuccess(trans('rest-api::app.settings.pipelines.delete-success'));
        } catch (\Exception $exception) {
            return $this->respondError(
                trans('rest-api::app.settings.pipelines.delete-failed'),
                500,
            );
        }
    }
}
