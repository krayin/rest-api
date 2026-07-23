<?php

namespace Webkul\RestApi\Http\Controllers\V1\Contact\Persons;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Prettus\Repository\Criteria\RequestCriteria;
use Webkul\Admin\Http\Requests\AttributeForm;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\RestApi\Http\Controllers\V1\Concerns\ResolvesAuthorizedUserIds;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Request\MassDestroyRequest;
use Webkul\RestApi\Http\Resources\V1\Contact\PersonResource;
use Webkul\User\Repositories\UserRepository;

class PersonController extends Controller
{
    use ResolvesAuthorizedUserIds;

    /**
     * Relations eager-loaded for list/search so rendering PersonResource (and
     * its nested organization/sales-owner resources) does not N+1 per row.
     */
    const LIST_RELATIONS = ['attribute_values', 'organization.attribute_values', 'user'];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected PersonRepository $personRepository,
        protected UserRepository $userRepository,
    ) {
        $this->addEntityTypeInRequest('persons');
    }

    /**
     * Display a listing of the persons.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $persons = $this->allResources($this->personRepository, self::LIST_RELATIONS);

        return PersonResource::collection($persons);
    }

    /**
     * Show resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->findOrFailResource($this->personRepository, $id);

        return new PersonResource($resource);
    }

    /**
     * Search person results.
     */
    public function search(): JsonResource
    {
        if ($userIds = $this->getAuthorizedUserIds()) {
            $persons = $this->personRepository
                ->pushCriteria(app(RequestCriteria::class))
                ->with(self::LIST_RELATIONS)
                ->limit(request()->input('limit') ?? self::DEFAULT_PER_PAGE)
                ->findWhereIn('user_id', $userIds);
        } else {
            $persons = $this->personRepository
                ->pushCriteria(app(RequestCriteria::class))
                ->with(self::LIST_RELATIONS)
                ->limit(request()->input('limit') ?? self::DEFAULT_PER_PAGE)
                ->all();
        }

        return PersonResource::collection($persons);
    }

    /**
     * Create the person.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeForm $request)
    {
        Event::dispatch('contacts.person.create.before');

        $person = $this->personRepository->create($this->sanitizeRequestedPersonData());

        Event::dispatch('contacts.person.create.after', $person);

        return new JsonResponse([
            'data'    => new PersonResource($person),
            'message' => trans('rest-api::app.contacts.persons.create-success'),
        ]);
    }

    /**
     * Update the person.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttributeForm $request, $id)
    {
        $this->findOrFailResource($this->personRepository, $id);

        Event::dispatch('contacts.person.update.before', $id);

        $person = $this->personRepository->update($this->sanitizeRequestedPersonData(), $id);

        Event::dispatch('contacts.person.update.after', $person);

        return new JsonResponse([
            'data'    => new PersonResource($person),
            'message' => trans('rest-api::app.contacts.persons.update-success'),
        ]);
    }

    /**
     * Remove the person.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->destroyResource(
            $this->personRepository,
            $id,
            'rest-api::app.contacts.persons.delete-success',
            'contacts.person',
            'rest-api::app.contacts.persons.delete-failed',
        );
    }

    /**
     * Mass delete the persons.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $result = $this->massDestroyResources(
            $this->personRepository,
            $massDestroyRequest->input('indices', []),
            'contact.person',
        );

        if ($result['deleted'] === 0) {
            return $this->respondError(trans('rest-api::app.common.nothing-to-delete'), 404);
        }

        return $this->respondSuccess(trans('rest-api::app.contacts.persons.delete-success'));
    }

    /**
     * Sanitize requested person data and return the clean array.
     */
    private function sanitizeRequestedPersonData(): array
    {
        $data = request()->all();

        // `contact_numbers` is optional: a person can be created with just an
        // email and no phone. Krayin's PersonRepository indexes
        // `contact_numbers[0]['value']` whenever the key is present, so passing
        // an empty array (or omitting a `value`) makes core crash with an
        // "Undefined array key" that, under production error handling, becomes a
        // 500 instead of simply persisting a person without a phone. So keep the
        // key only when there is at least one real number, and drop it entirely
        // otherwise.
        $numbers = collect($data['contact_numbers'] ?? [])
            ->filter(fn ($number) => ! is_null($number['value'] ?? null))
            ->values()
            ->toArray();

        if ($numbers) {
            $data['contact_numbers'] = $numbers;
        } else {
            unset($data['contact_numbers']);
        }

        return $data;
    }

}
