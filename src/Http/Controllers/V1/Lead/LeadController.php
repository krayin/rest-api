<?php

namespace Webkul\RestApi\Http\Controllers\V1\Lead;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Requests\LeadForm;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Lead\Repositories\PipelineRepository;
use Webkul\Lead\Repositories\StageRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Request\MassDestroyRequest;
use Webkul\RestApi\Http\Request\MassUpdateRequest;
use Webkul\RestApi\Http\Resources\V1\Lead\LeadResource;

class LeadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected LeadRepository $leadRepository,
        protected PipelineRepository $pipelineRepository,
        protected StageRepository $stageRepository
    ) {
        $this->addEntityTypeInRequest('leads');
    }

    /**
     * Returns a listing of the leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = $this->allResources($this->leadRepository);

        return LeadResource::collection($leads);
    }

    /**
     * Show resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->leadRepository->find($id);

        return new LeadResource($resource);
    }

    /**
     * Store a newly created lead in storage.
     *
     * @param  \Webkul\RestApi\Http\Requests\LeadForm  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadForm $request)
    {
        Event::dispatch('lead.create.before');

        $data = $request->all();

        $data['status'] = 1;

        if ($data['lead_pipeline_stage_id']) {
            $stage = $this->stageRepository->findOrFail($data['lead_pipeline_stage_id']);

            $data['lead_pipeline_id'] = $stage->lead_pipeline_id;
        } else {
            $pipeline = $this->pipelineRepository->getDefaultPipeline();

            $stage = $pipeline->stages()->first();

            $data['lead_pipeline_id'] = $pipeline->id;

            $data['lead_pipeline_stage_id'] = $stage->id;
        }

        if (in_array($stage->code, ['won', 'lost'])) {
            $data['closed_at'] = Carbon::now();
        }

        $lead = $this->leadRepository->create($data);

        Event::dispatch('lead.create.after', $lead);

        return new JsonResource([
            'data'    => new LeadResource($lead),
            'message' => trans('admin::app.leads.create-success'),
        ]);
    }

    /**
     * Update the lead in storage.
     *
     * @param  \Webkul\RestApi\Http\Requests\LeadForm  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeadForm $request, $id)
    {
        Event::dispatch('lead.update.before', $id);

        $data = $request->all();

        if ($data['lead_pipeline_stage_id']) {
            $stage = $this->stageRepository->findOrFail($data['lead_pipeline_stage_id']);

            $data['lead_pipeline_id'] = $stage->lead_pipeline_id;
        } else {
            $pipeline = $this->pipelineRepository->getDefaultPipeline();

            $stage = $pipeline->stages()->first();

            $data['lead_pipeline_id'] = $pipeline->id;

            $data['lead_pipeline_stage_id'] = $stage->id;
        }

        $lead = $this->leadRepository->update($data, $id);

        Event::dispatch('lead.update.after', $lead);

        return new JsonResource([
            'data'    => new LeadResource($lead),
            'message' => trans('admin::app.leads.update-success'),
        ]);
    }

    /**
     * Remove the lead from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lead = $this->leadRepository->findOrFail($id);

        try {
            Event::dispatch('lead.delete.before', $id);

            $lead->delete();

            Event::dispatch('lead.delete.after', $id);

            return new JsonResource([
                'message' => trans('admin::app.response.destroy-success', ['name' => trans('admin::app.leads.lead')]),
            ]);
        } catch (\Exception $exception) {
            return new JsonResource([
                'message' => trans('admin::app.response.destroy-failed', ['name' => trans('admin::app.leads.lead')]),
            ], 500);
        }
    }

    /**
     * Mass update the leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest)
    {
        $leadIds = $massUpdateRequest->input('indices', []);

        foreach ($leadIds as $leadId) {
            $lead = $this->leadRepository->find($leadId);

            if (! $lead) {
                continue;
            }

            Event::dispatch('lead.update.before', $leadId);

            $lead->update(['lead_pipeline_stage_id' => $massUpdateRequest->input('value')]);

            Event::dispatch('lead.update.before', $leadId);
        }

        return new JsonResource([
            'message' => trans('admin::app.response.update-success', ['name' => trans('admin::app.leads.title')]),
        ]);
    }

    /**
     * Mass delete the leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $leadIds = $massDestroyRequest->input('indices', []);

        foreach ($leadIds as $leadId) {
            $lead = $this->leadRepository->find($leadId);

            if (! $lead) {
                continue;
            }

            Event::dispatch('lead.delete.before', $leadId);

            $this->leadRepository->delete($leadId);

            Event::dispatch('lead.delete.after', $leadId);
        }

        return new JsonResource([
            'message' => trans('admin::app.response.destroy-success', ['name' => trans('admin::app.leads.title')]),
        ]);
    }
}
