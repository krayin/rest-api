<?php

namespace Webkul\RestApi\Http\Controllers\V1\Quote;

use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Http\Requests\AttributeForm;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Quote\Repositories\QuoteRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Quote\QuoteResource;

class QuoteController extends Controller
{
    /**
     * Quote repository instance.
     *
     * @var \Webkul\Quote\Repositories\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * Lead repository instance.
     *
     * @var \Webkul\Lead\Repositories\LeadRepository
     */
    protected $leadRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Quote\Repositories\QuoteRepository  $quoteRepository
     * @param  \Webkul\Lead\Repositories\LeadRepository  $leadRepository
     * @return void
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        LeadRepository $leadRepository
    ) {
        $this->quoteRepository = $quoteRepository;

        $this->leadRepository = $leadRepository;

        $this->addEntityTypeInRequest('quotes');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotes = $this->allResources($this->quoteRepository);

        return QuoteResource::collection($quotes);
    }

    /**
     * Display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $quote = $this->quoteRepository->find($id);

        return new QuoteResource($quote);
    }

    /**
     * Store a newly created qoute in storage.
     *
     * @param  \Webkul\Attribute\Http\Requests\AttributeForm  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeForm $request)
    {
        Event::dispatch('quote.create.before');

        $quote = $this->quoteRepository->create($request->all());

        if (request('lead_id')) {
            $lead = $this->leadRepository->find(request('lead_id'));

            $lead->quotes()->attach($quote->id);
        }

        Event::dispatch('quote.create.after', $quote);

        return response([
            'data'    => new QuoteResource($quote),
            'message' => __('admin::app.quotes.create-success'),
        ]);
    }

    /**
     * Update the specified qoute in storage.
     *
     * @param  \Webkul\Attribute\Http\Requests\AttributeForm  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttributeForm $request, $id)
    {
        Event::dispatch('quote.update.before', $id);

        $quote = $this->quoteRepository->update($request->all(), $id);

        $quote->leads()->detach();

        if (request('lead_id')) {
            $lead = $this->leadRepository->find(request('lead_id'));

            $lead->quotes()->attach($quote->id);
        }

        Event::dispatch('quote.update.after', $quote);

        return response([
            'data'    => new QuoteResource($quote),
            'message' => __('admin::app.quotes.update-success'),
        ]);
    }

    /**
     * Remove the specified qoute from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->quoteRepository->findOrFail($id);

        try {
            Event::dispatch('quote.delete.before', $id);

            $this->quoteRepository->delete($id);

            Event::dispatch('quote.delete.after', $id);

            return response([
                'message' => __('admin::app.response.destroy-success', ['name' => __('admin::app.quotes.quote')]),
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => __('admin::app.response.destroy-failed', ['name' => __('admin::app.quotes.quote')]),
            ], 500);
        }
    }

    /**
     * Mass delete the specified qoutes.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        foreach (request('rows') as $quoteId) {
            Event::dispatch('quote.delete.before', $quoteId);

            $this->quoteRepository->delete($quoteId);

            Event::dispatch('quote.delete.after', $quoteId);
        }

        return response([
            'message' => __('admin::app.response.destroy-success', ['name' => __('admin::app.quotes.title')]),
        ]);
    }
}
