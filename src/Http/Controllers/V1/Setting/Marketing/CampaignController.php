<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting\Marketing;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\EmailTemplate\Repositories\EmailTemplateRepository;
use Webkul\Marketing\Repositories\CampaignRepository;
use Webkul\Marketing\Repositories\EventRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Request\MassDestroyRequest;
use Webkul\RestApi\Http\Resources\V1\Setting\CampaignResource;

class CampaignController extends Controller
{
    /**
     * Create new a controller instance.
     */
    public function __construct(
        protected CampaignRepository $campaignRepository,
        protected EventRepository $eventRepository,
        protected EmailTemplateRepository $emailTemplateRepository,
    ) {}

    /**
     * Display a listing of the marketing campaigns.
     */
    public function index(): JsonResource
    {
        $campaigns = $this->allResources($this->campaignRepository);

        return CampaignResource::collection($campaigns);
    }

    /**
     * Store a newly created marketing campaign in storage.
     */
    public function store(): JsonResource
    {
        $validatedData = $this->validate(request(), [
            'name'                  => 'required|string|max:255',
            'subject'               => 'required|string|max:255',
            'marketing_template_id' => 'required|exists:email_templates,id',
            'marketing_event_id'    => 'required|exists:marketing_events,id',
            'status'                => 'sometimes|required|in:0,1',
        ]);

        Event::dispatch('settings.marketing.campaigns.create.before');

        $marketingCampaign = $this->campaignRepository->create($validatedData);

        Event::dispatch('settings.marketing.campaigns.create.after', $marketingCampaign);

        return new JsonResource([
            'data'    => new CampaignResource($marketingCampaign),
            'message' => trans('rest-api::app.settings.marketing.campaigns.create-success'),
        ]);
    }

    /**
     * Show the specified Resource.
     */
    public function show(int $id): CampaignResource
    {
        $campaign = $this->campaignRepository->findOrFail($id);

        return new CampaignResource($campaign);
    }

    /**
     * Update the specified marketing campaign in storage.
     */
    public function update(int $id): JsonResource
    {
        $this->findOrFailResource($this->campaignRepository, $id);

        $validatedData = $this->validate(request(), [
            'name'                  => 'required|string|max:255',
            'subject'               => 'required|string|max:255',
            'marketing_template_id' => 'required|exists:email_templates,id',
            'marketing_event_id'    => 'required|exists:marketing_events,id',
            'status'                => 'sometimes|required|in:0,1',
        ]);

        Event::dispatch('settings.marketing.campaigns.update.before', $id);

        $marketingCampaign = $this->campaignRepository->update($validatedData, $id);

        Event::dispatch('settings.marketing.campaigns.update.after', $marketingCampaign);

        return new JsonResource([
            'data'    => new CampaignResource($marketingCampaign),
            'message' => trans('rest-api::app.settings.marketing.campaigns.update-success'),
        ]);
    }

    /**
     * Remove the specified marketing campaign from storage.
     */
    public function destroy(int $id): JsonResource|JsonResponse
    {
        return $this->destroyResource(
            $this->campaignRepository,
            $id,
            'rest-api::app.settings.marketing.campaigns.destroy-success',
            'settings.marketing.campaigns',
            'rest-api::app.settings.marketing.campaigns.delete-failed',
        );
    }

    /**
     * Remove the specified marketing campaigns from storage.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResource|JsonResponse
    {
        $result = $this->massDestroyResources(
            $this->campaignRepository,
            $massDestroyRequest->input('indices', []),
            'settings.marketing.campaigns',
        );

        if ($result['deleted'] === 0) {
            return $this->respondError(trans('rest-api::app.common.nothing-to-delete'), 404);
        }

        return $this->respondSuccess(trans('rest-api::app.settings.marketing.campaigns.destroy-success'));
    }
}
