<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Support\Facades\Event;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\WorkflowResource;
use Webkul\Workflow\Repositories\WorkflowRepository;

class WorkflowController extends Controller
{
    /**
     * Workflow repository instance.
     *
     * @var \Webkul\Workflow\Repositories\WorkflowRepository
     */
    protected $workflowRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Workflow\Repositories\WorkflowRepository  $workflowRepository
     * @return void
     */
    public function __construct(WorkflowRepository $workflowRepository)
    {
        $this->workflowRepository = $workflowRepository;
    }

    /**
     * Display a listing of the workflow.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workflows = $this->allResources($this->workflowRepository);

        return WorkflowResource::collection($workflows);
    }

    /**
     * Show resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->workflowRepository->find($id);

        return new WorkflowResource($resource);
    }

    /**
     * Store a newly created workflow in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required',
        ]);

        Event::dispatch('settings.workflow.create.before');

        $workflow = $this->workflowRepository->create(request()->all());

        Event::dispatch('settings.workflow.create.after', $workflow);

        return response([
            'data'    => new WorkflowResource($workflow),
            'message' => __('admin::app.settings.workflows.create-success'),
        ]);
    }

    /**
     * Update the specified workflow in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'name' => 'required',
        ]);

        Event::dispatch('settings.workflow.update.before', $id);

        $workflow = $this->workflowRepository->update(request()->all(), $id);

        Event::dispatch('settings.workflow.update.after', $workflow);

        return response([
            'data'    => new WorkflowResource($workflow),
            'message' => __('admin::app.settings.workflows.update-success'),
        ]);
    }

    /**
     * Remove the specified workflow from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Event::dispatch('settings.workflow.delete.before', $id);

            $this->workflowRepository->delete($id);

            Event::dispatch('settings.workflow.delete.after', $id);

            return response([
                'message' => __('admin::app.settings.workflows.delete-success'),
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => __('admin::app.settings.workflows.delete-failed'),
            ], 500);
        }
    }
}
