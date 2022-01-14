<?php

namespace Webkul\RestApi\Http\Controllers\V1\Contact;

use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Http\Requests\AttributeForm;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Contact\PersonResource;

class PersonController extends Controller
{
    /**
     * Person repository instance.
     *
     * @var \Webkul\Contact\Repositories\PersonRepository
     */
    protected $personRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Contact\Repositories\PersonRepository  $personRepository
     * @return void
     */
    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;

        $this->addEntityTypeInRequest('persons');
    }

    /**
     * Display a listing of the persons.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $persons = $this->allResources($this->personRepository);

        return PersonResource::collection($persons);
    }

    /**
     * Show resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->personRepository->find($id);

        return new PersonResource($resource);
    }

    /**
     * Create the person.
     *
     * @param  \Webkul\Attribute\Http\Requests\AttributeForm  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeForm $request)
    {
        Event::dispatch('contacts.person.create.before');

        $person = $this->personRepository->create($this->sanitizeRequestedPersonData());

        Event::dispatch('contacts.person.create.after', $person);

        return response([
            'data'    => new PersonResource($person),
            'message' => __('admin::app.contacts.persons.create-success'),
        ]);
    }

    /**
     * Update the person.
     *
     * @param  \Webkul\Attribute\Http\Requests\AttributeForm  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttributeForm $request, $id)
    {
        Event::dispatch('contacts.person.update.before', $id);

        $person = $this->personRepository->update($this->sanitizeRequestedPersonData(), $id);

        Event::dispatch('contacts.person.update.after', $person);

        return response([
            'data'    => new PersonResource($person),
            'message' => __('admin::app.contacts.persons.update-success'),
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
        try {
            Event::dispatch('contacts.person.delete.before', $id);

            $this->personRepository->delete($id);

            Event::dispatch('contacts.person.delete.after', $id);

            return response([
                'message' => __('admin::app.response.destroy-success', ['name' => __('admin::app.contacts.persons.person')]),
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => __('admin::app.response.destroy-failed', ['name' => __('admin::app.contacts.persons.person')]),
            ], 500);
        }
    }

    /**
     * Mass delete the persons.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        foreach (request('rows') as $personId) {
            Event::dispatch('contact.person.delete.before', $personId);

            $this->personRepository->delete($personId);

            Event::dispatch('contact.person.delete.after', $personId);
        }

        return response([
            'message' => __('admin::app.response.destroy-success', ['name' => __('admin::app.contacts.persons.title')]),
        ]);
    }

    /**
     * Sanitize requested person data and return the clean array.
     *
     * @return array
     */
    private function sanitizeRequestedPersonData(): array
    {
        $data = request()->all();

        $data['contact_numbers'] = collect($data['contact_numbers'])->filter(function ($number) {
            return ! is_null($number['value']);
        })->toArray();

        return $data;
    }
}
