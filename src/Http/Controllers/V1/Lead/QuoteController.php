<?php

namespace Webkul\RestApi\Http\Controllers\V1\Lead;

use Illuminate\Support\Facades\Event;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Quote\Repositories\QuoteRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Lead\LeadResource;

class QuoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected LeadRepository $leadRepository,
        protected QuoteRepository $quoteRepository
    ) {
    }

    /**
     * Store a newly created qoute in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        Event::dispatch('leads.quote.create.before');

        $lead = $this->leadRepository->find($id);

        if (! $lead->quotes->contains(request('id'))) {
            $lead->quotes()->attach(request('id'));
        }

        Event::dispatch('leads.quote.create.after', $lead);

        return response([
            'data'    => new LeadResource($lead),
            'message' => __('admin::app.leads.quote-create-success'),
        ]);
    }

    /**
     * Remove the specified qoute from storage.
     *
     * @param  int  $leadId
     * @param  int  $tagId
     * @return \Illuminate\Http\Response
     */
    public function delete($leadId)
    {
        Event::dispatch('leads.quote.delete.before', $leadId);

        $lead = $this->leadRepository->find($leadId);

        $lead->quotes()->detach(request('id'));

        Event::dispatch('leads.quote.delete.after', $lead);

        return response([
            'data'    => new LeadResource($lead),
            'message' => __('admin::app.leads.quote-destroy-success'),
        ]);
    }
}
