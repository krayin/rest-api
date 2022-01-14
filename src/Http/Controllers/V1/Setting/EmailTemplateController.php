<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Support\Facades\Event;
use Webkul\EmailTemplate\Repositories\EmailTemplateRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\EmailTemplateResource;
use Webkul\Workflow\Helpers\Entity;

class EmailTemplateController extends Controller
{
    /**
     * Email template repository instance.
     *
     * @var \Webkul\EmailTemplate\Repositories\EmailTemplateRepository
     */
    protected $emailTemplateRepository;

    /**
     * Entity instance.
     *
     * @var \Workflow\Workflow\Repositories\Entity
     */
    protected $workflowEntityHelper;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\EmailTemplate\Repositories\EmailTemplateRepository  $emailTemplateRepository
     * @param  \Workflow\Workflow\Repositories\Entity  $workflowEntityHelper
     * @return void
     */
    public function __construct(
        EmailTemplateRepository $emailTemplateRepository,
        Entity $workflowEntityHelper
    ) {
        $this->emailTemplateRepository = $emailTemplateRepository;

        $this->workflowEntityHelper = $workflowEntityHelper;
    }

    /**
     * Display a listing of the email template.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emailTemplates = $this->allResources($this->emailTemplateRepository);

        return EmailTemplateResource::collection($emailTemplates);
    }

    /**
     * Show resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->emailTemplateRepository->find($id);

        return new EmailTemplateResource($resource);
    }

    /**
     * Store a newly created email templates in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name'    => 'required',
            'subject' => 'required',
            'content' => 'required',
        ]);

        Event::dispatch('settings.email_templates.create.before');

        $emailTemplate = $this->emailTemplateRepository->create(request()->all());

        Event::dispatch('settings.email_templates.create.after', $emailTemplate);

        return response([
            'data'    => new EmailTemplateResource($emailTemplate),
            'message' => __('admin::app.settings.email-templates.create-success'),
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
            'name'    => 'required',
            'subject' => 'required',
            'content' => 'required',
        ]);

        Event::dispatch('settings.email_templates.update.before', $id);

        $emailTemplate = $this->emailTemplateRepository->update(request()->all(), $id);

        Event::dispatch('settings.email_templates.update.after', $emailTemplate);

        return response([
            'data'    => new EmailTemplateResource($emailTemplate),
            'message' => __('admin::app.settings.email-templates.update-success'),
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
            Event::dispatch('settings.email_templates.delete.before', $id);

            $this->emailTemplateRepository->delete($id);

            Event::dispatch('settings.email_templates.delete.after', $id);

            return response([
                'message' => __('admin::app.settings.email-templates.delete-success'),
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => __('admin::app.settings.email-templates.delete-failed'),
            ], 500);
        }
    }
}
