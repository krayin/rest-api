<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\WebForm\Repositories\WebFormRepository;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Lead\Repositories\PipelineRepository;
use Webkul\Lead\Repositories\SourceRepository;
use Webkul\Lead\Repositories\TypeRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\WebFormResource;
use Illuminate\Support\Facades\Event;

class WebFormController extends Controller
{
    /**
     * AttributeRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * WebFormRepository object
     *
     * @var \Webkul\WebForm\Repositories\WebFormRepository
     */
    protected $webFormRepository;

    /**
     * LeadRepository object
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
     * PersonRepository object
     *
     * @var \Webkul\Contact\Repositories\PersonRepository
     */
    protected $personRepository;

    /**
     * SourceRepository object
     *
     * @var \Webkul\Lead\Repositories\SourceRepository
     */
    protected $sourceRepository;

    /**
     * TypeRepository object
     *
     * @var \Webkul\Lead\Repositories\TypeRepository
     */
    protected $typeRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\WebForm\Repositories\WebFormRepository  $webFormRepository
     * @param  \Webkul\Contact\Repositories\PersonRepository  $personRepository
     * @param  \Webkul\Lead\Repositories\LeadRepository  $leadRepository
     * @param \Webkul\Lead\Repositories\PipelineRepository  $pipelineRepository
     * @param \Webkul\Lead\Repositories\SourceRepository  $sourceRepository
     * @param \Webkul\Lead\Repositories\TypeRepository  $typeRepository
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        WebFormRepository $webFormRepository,
        PersonRepository $personRepository,
        LeadRepository $leadRepository,
        PipelineRepository $pipelineRepository,
        SourceRepository $sourceRepository,
        TypeRepository $typeRepository
    )
    {
        $this->attributeRepository = $attributeRepository;

        $this->webFormRepository = $webFormRepository;

        $this->personRepository = $personRepository;

        $this->leadRepository = $leadRepository;

        $this->pipelineRepository = $pipelineRepository;

        $this->sourceRepository = $sourceRepository;

        $this->typeRepository = $typeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->allResources($this->webFormRepository);

        return WebFormResource::collection($users);
    }

    /**
     * Display the specified resource.
     *
     * @param integer $id
     * @return WebFormResource
     */
    public function show($id)
    {
        $webForm = $this->webFormRepository->findOrFail($id);

        return new WebFormResource($webForm);
    }

    /**
     * Store a newly created email templates in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'title'                  => 'required',
            'submit_button_label'    => 'required',
            'submit_success_action'  => 'required',
            'submit_success_content' => 'required',
        ]);

        Event::dispatch('settings.web_forms.create.before');

        $data = request()->all();

        $data['create_lead'] = isset($data['create_lead']) ? 1 : 0;

        $webForm = $this->webFormRepository->create($data);

        Event::dispatch('settings.web_forms.create.after', $webForm);

        return response([
            'data'    => new WebFormResource($webForm),
            'message' => __('admin::app.settings.web-forms.create-success'),
        ]);
    }

     /**
     * Update the specified email template in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'title'                  => 'required',
            'submit_button_label'    => 'required',
            'submit_success_action'  => 'required',
            'submit_success_content' => 'required',
        ]);

        Event::dispatch('settings.web_forms.update.before', $id);

        $data = request()->all();

        $data['create_lead'] = isset($data['create_lead']) ? 1 : 0;

        $webForm = $this->webFormRepository->update($data, $id);

        Event::dispatch('settings.web_forms.update.after', $webForm);

        return response([
            'data'    => new WebFormResource($webForm),
            'message' => __('admin::app.settings.web-forms.update-success'),
        ]);
    }

    /**
     * Remove the specified email template from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Event::dispatch('settings.web_forms.delete.before', $id);

            $this->webFormRepository->delete($id);

            Event::dispatch('settings.web_forms.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.settings.web-forms.delete-success'),
            ], 200);
        } catch(\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.settings.web-forms.delete-failed'),
            ], 400);
        }

        return response()->json([
            'message' => trans('admin::app.settings.web-forms.delete-failed'),
        ], 400);
    }
}
