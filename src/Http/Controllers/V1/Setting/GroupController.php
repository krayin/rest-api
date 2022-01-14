<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Support\Facades\Event;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\GroupResource;
use Webkul\User\Repositories\GroupRepository;

class GroupController extends Controller
{
    /**
     * Group repository instance.
     *
     * @var \Webkul\User\Repositories\GroupRepository
     */
    protected $groupRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\User\Repositories\GroupRepository  $groupRepository
     * @return void
     */
    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->groupRepository->find($id);

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
            'name' => 'required|unique:groups,name',
        ]);

        Event::dispatch('settings.group.create.before');

        $group = $this->groupRepository->create(request()->all());

        Event::dispatch('settings.group.create.after', $group);

        return response([
            'data'    => new GroupResource($group),
            'message' => __('admin::app.settings.groups.create-success'),
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
        $this->validate(request(), [
            'name' => 'required|unique:groups,name,' . $id,
        ]);

        Event::dispatch('settings.group.update.before', $id);

        $group = $this->groupRepository->update(request()->all(), $id);

        Event::dispatch('settings.group.update.after', $group);

        return response([
            'data'    => new GroupResource($group),
            'message' => __('admin::app.settings.groups.update-success'),
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
        try {
            Event::dispatch('settings.group.delete.before', $id);

            $this->groupRepository->delete($id);

            Event::dispatch('settings.group.delete.after', $id);

            return response([
                'message' => __('admin::app.settings.groups.destroy-success'),
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => __('admin::app.settings.groups.delete-failed'),
            ], 500);
        }
    }
}
