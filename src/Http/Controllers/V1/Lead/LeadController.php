<?php

namespace Webkul\RestApi\Http\Controllers\V1\Lead;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Requests\LeadForm;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Lead\Repositories\PipelineRepository;
use Webkul\Lead\Repositories\StageRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Lead\LeadResource;

class LeadController extends Controller
{
    /**
     * Lead repository instance.
     *
     * @var \Webkul\Lead\Repositories\LeadRepository
     */
    protected $leadRepository;

    /**
     * Pipeline repository instance.
     *
     * @var \Webkul\Lead\Repositories\PipelineRepository
     */
    protected $pipelineRepository;

    /**
     * Stage repository instance.
     *
     * @var \Webkul\Lead\Repositories\StageRepository
     */
    protected $stageRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Lead\Repositories\LeadRepository  $leadRepository
     * @param  \Webkul\Lead\Repositories\PipelineRepository  $pipelineRepository
     * @param  \Webkul\Lead\Repositories\StageRepository  $stageRepository
     * @return void
     */
    public function __construct(
        LeadRepository $leadRepository,
        PipelineRepository $pipelineRepository,
        StageRepository $stageRepository
    ) {
        $this->leadRepository = $leadRepository;

        $this->pipelineRepository = $pipelineRepository;

        $this->stageRepository = $stageRepository;

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
     * @param  int  $id
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

        return response([
            'data'    => new LeadResource($lead),
            'message' => __('admin::app.leads.create-success'),
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

        return response([
            'data'    => new LeadResource($lead),
            'message' => __('admin::app.leads.update-success'),
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
        $this->leadRepository->findOrFail($id);

        try {
            Event::dispatch('lead.delete.before', $id);

            $this->leadRepository->delete($id);

            Event::dispatch('lead.delete.after', $id);

            return response([
                'message' => __('admin::app.response.destroy-success', ['name' => __('admin::app.leads.lead')]),
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => __('admin::app.response.destroy-failed', ['name' => __('admin::app.leads.lead')]),
            ], 500);
        }
    }

    /**
     * Mass update the leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate()
    {
        $data = request()->all();

        foreach ($data['rows'] as $leadId) {
            Event::dispatch('lead.update.before', $leadId);

            $lead = $this->leadRepository->find($leadId);

            $lead->update(['lead_pipeline_stage_id' => $data['value']]);

            Event::dispatch('lead.update.before', $leadId);
        }

        return response([
            'message' => __('admin::app.response.update-success', ['name' => __('admin::app.leads.title')]),
        ]);
    }

    /**
     * Mass delete the leads.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        foreach (request('rows') as $leadId) {
            Event::dispatch('lead.delete.before', $leadId);

            $this->leadRepository->delete($leadId);

            Event::dispatch('lead.delete.after', $leadId);
        }

        return response([
            'message' => __('admin::app.response.destroy-success', ['name' => __('admin::app.leads.title')]),
        ]);
    }
}
