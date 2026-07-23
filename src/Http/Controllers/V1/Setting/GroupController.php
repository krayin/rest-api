<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\GroupResource;
use Webkul\User\Repositories\GroupRepository;

class GroupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected GroupRepository $groupRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = $this->allResources($this->groupRepository);

        return GroupResource::collection($groups);
    }

    /**
     * Show resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->findOrFailResource($this->groupRepository, $id);

        return new GroupResource($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name'        => 'required|unique:groups,name',
            'description' => 'required',
        ]);

        Event::dispatch('settings.group.create.before');

        $group = $this->groupRepository->create(request()->all());

        Event::dispatch('settings.group.create.after', $group);

        return new JsonResource([
            'data'    => new GroupResource($group),
            'message' => trans('rest-api::app.settings.groups.create-success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->findOrFailResource($this->groupRepository, $id);

        $this->validate(request(), [
            'name'        => 'required|unique:groups,name,'.$id,
            'description' => 'required',
        ]);

        Event::dispatch('settings.group.update.before', $id);

        $group = $this->groupRepository->update(request()->all(), $id);

        Event::dispatch('settings.group.update.after', $group);

        return new JsonResource([
            'data'    => new GroupResource($group),
            'message' => trans('rest-api::app.settings.groups.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->destroyResource(
            $this->groupRepository,
            $id,
            'rest-api::app.settings.groups.destroy-success',
            'settings.group',
            'rest-api::app.settings.groups.delete-failed',
        );
    }
}
