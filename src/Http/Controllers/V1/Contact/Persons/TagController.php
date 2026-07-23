<?php

namespace Webkul\RestApi\Http\Controllers\V1\Contact\Persons;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;

class TagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PersonRepository $personRepository) {}

    /**
     * Store a newly created resource in storage.
     */
    public function attach(int $id): JsonResponse
    {
        $this->validate(request(), [
            'tag_id' => 'required|exists:tags,id',
        ]);

        $person = $this->personRepository->findOrFail($id);

        Event::dispatch('persons.tag.create.before', $id);

        if (! $person->tags->contains(request()->input('tag_id'))) {
            $person->tags()->attach(request()->input('tag_id'));
        }

        Event::dispatch('persons.tag.create.after', $person);

        return response()->json([
            'message' => trans('rest-api::app.contacts.persons.view.tags.create-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function detach(int $personId): JsonResponse
    {
        $person = $this->personRepository->findOrFail($personId);

        $tagId = request()->input('tag_id');

        if (! $person->tags->contains($tagId)) {
            return response()->json([
                'message' => trans('rest-api::app.common.tag-not-attached'),
            ], 404);
        }

        Event::dispatch('persons.tag.delete.before', $personId);

        $person->tags()->detach($tagId);

        Event::dispatch('persons.tag.delete.after', $person);

        return response()->json([
            'message' => trans('rest-api::app.contacts.persons.view.tags.delete-success'),
        ]);
    }
}
