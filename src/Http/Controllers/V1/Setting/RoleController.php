<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Support\Facades\Event;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\RoleResource;
use Webkul\User\Repositories\RoleRepository;

class RoleController extends Controller
{
    /**
     * Role repository instance.
     *
     * @var \Webkul\User\Repositories\RoleRepository
     */
    protected $roleRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\User\Repositories\RoleRepository  $roleRepository
     * @return void
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->allResources($this->roleRepository);

        return RoleResource::collection($roles);
    }

    /**
     * Show resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->roleRepository->find($id);

        return new RoleResource($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name'            => 'required',
            'permission_type' => 'required',
        ]);

        Event::dispatch('settings.role.create.before');

        $roleData = request()->all();

        if ($roleData['permission_type'] == 'custom') {
            if (! isset($roleData['permissions'])) {
                $roleData['permissions'] = [];
            }
        }

        $role = $this->roleRepository->create($roleData);

        Event::dispatch('settings.role.create.after', $role);

        return response([
            'data'    => new RoleResource($role),
            'message' => __('admin::app.settings.roles.create-success'),
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
            'name'            => 'required',
            'permission_type' => 'required',
        ]);

        Event::dispatch('settings.role.update.before', $id);

        $roleData = request()->all();

        if ($roleData['permission_type'] == 'custom') {
            if (! isset($roleData['permissions'])) {
                $roleData['permissions'] = [];
            }
        }

        $role = $this->roleRepository->update($roleData, $id);

        Event::dispatch('settings.role.update.after', $role);

        return response([
            'data'    => new RoleResource($role),
            'message' => __('admin::app.settings.roles.update-success'),
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
        $response = ['code' => 400];

        $role = $this->roleRepository->findOrFail($id);

        if ($role->admins && $role->admins->count() >= 1) {
            $response['message'] = __('admin::app.settings.roles.being-used');
        } else if ($this->roleRepository->count() == 1) {
            $response['message'] = __('admin::app.settings.roles.last-delete-error');
        } else {
            try {
                Event::dispatch('settings.role.delete.before', $id);

                if (auth()->guard()->user()->role_id == $id) {
                    $response['message'] = __('admin::app.settings.roles.current-role-delete-error');
                } else {
                    $this->roleRepository->delete($id);

                    Event::dispatch('settings.role.delete.after', $id);

                    $response = [
                        'code'    => 200,
                        'message' => __('admin::app.settings.roles.delete-success'),
                    ];
                }
            } catch (\Exception $exception) {}
        }

        return response(['message' => $response['message']], $response['code']);
    }
}
