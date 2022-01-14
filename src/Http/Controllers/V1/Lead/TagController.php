<?php

namespace Webkul\RestApi\Http\Controllers\V1\Lead;

use Illuminate\Support\Facades\Event;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Lead\LeadResource;

class TagController extends Controller
{
    /**
     * Lead repository instance.
     *
     * @var \Webkul\Lead\Repositories\LeadRepository
     */
    protected $leadRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Lead\Repositories\LeadRepository  $leadRepository
     * @return void
     */
    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    /**
     * Store a newly created tag in storage.
     *
     * @param  integer  $leadId
     * @return \Illuminate\Http\Response
     */
    public function store($leadId)
    {
        Event::dispatch('leads.tag.create.before', $leadId);

        $lead = $this->leadRepository->find($leadId);

        if (! $lead->tags->contains(request('id'))) {
            $lead->tags()->attach(request('id'));
        }

        Event::dispatch('leads.tag.create.after', $lead);

        return response([
            'data'    => new LeadResource($lead),
            'message' => __('admin::app.leads.tag-create-success'),
        ]);
    }

    /**
     * Remove the specified tag from storage.
     *
     * @param  integer  $leadId
     * @return \Illuminate\Http\Response
     */
    public function delete($leadId)
    {
        Event::dispatch('leads.tag.delete.before', $leadId);

        $lead = $this->leadRepository->find($leadId);

        $lead->tags()->detach(request('id'));

        Event::dispatch('leads.tag.delete.after', $lead);

        return response([
            'data'    => new LeadResource($lead),
            'message' => __('admin::app.leads.tag-destroy-success'),
        ]);
    }
}
