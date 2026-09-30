<?php

namespace Webkul\RestApi\Http\Controllers\V1\Lead;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;

class QuoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected LeadRepository $leadRepository) {}

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $leadId): JsonResponse
    {
        $this->validate(request(), [
            'quote_id' => 'required|integer|exists:quotes,id',
        ]);

        Event::dispatch('leads.quote.delete.before', $leadId);

        /**
         * A lead id that matches no record reached the relation call as null and failed with a
         * member-function error instead of reporting the lead as not found.
         */
        $lead = $this->leadRepository->findOrFail($leadId);

        $lead->quotes()->detach(request('quote_id'));

        Event::dispatch('leads.quote.delete.after', $lead);

        return response()->json([
            'message' => trans('rest-api::app.leads.view.quotes.delete-success'),
        ], 200);
    }
}
