<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Notifications\User\Create;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Request\MassDestroyRequest;
use Webkul\RestApi\Http\Request\MassUpdateRequest;
use Webkul\RestApi\Http\Resources\V1\Setting\UserResource;
use Webkul\User\Repositories\GroupRepository;
use Webkul\User\Repositories\RoleRepository;
use Webkul\User\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected GroupRepository $groupRepository,
        protected RoleRepository $roleRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->allResources($this->userRepository);

        return UserResource::collection($users);
    }

    /**
     * Show resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->userRepository->find($id);

        return new UserResource($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'email'            => 'required|email|unique:users,email',
            'name'             => 'required',
            'password'         => 'nullable',
            'confirm_password' => 'nullable|required_with:password|same:password',
            'role_id'          => 'required',
        ]);

        $data = request()->all();

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $data['status'] = isset($data['status']) ? 1 : 0;

        Event::dispatch('settings.user.create.before');

        $admin = $this->userRepository->create($data);

        $admin->view_permission = $data['view_permission'];

        $admin->save();

        $admin->groups()->sync($data['groups'] ?? []);

        try {
            Mail::queue(new Create($admin));
        } catch (\Exception $e) {
            report($e);
        }

        Event::dispatch('settings.user.create.after', $admin);

        return new JsonResource([
            'data'    => new UserResource($admin),
            'message' => trans('admin::app.settings.users.create-success'),
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
            'email'            => 'required|email|unique:users,email,'.$id,
            'name'             => 'required',
            'password'         => 'nullable',
            'confirm_password' => 'nullable|required_with:password|same:password',
            'role_id'          => 'required',
        ]);

        $data = request()->all();

        if (! $data['password']) {
            unset($data['password'], $data['confirm_password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        if (auth()->guard()->user()->id != $id) {
            $data['status'] = isset($data['status']) ? 1 : 0;
        }

        Event::dispatch('settings.user.update.before', $id);

        $admin = $this->userRepository->update($data, $id);

        $admin->view_permission = $data['view_permission'];

        $admin->save();

        $admin->groups()->sync($data['groups'] ?? []);

        Event::dispatch('settings.user.update.after', $admin);

        return new JsonResource([
            'data'    => new UserResource($admin),
            'message' => trans('admin::app.settings.users.update-success'),
        ]);
    }

    /**
     * Destroy specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->guard()->user()->id == $id) {
            return new JsonResource([
                'message' => trans('admin::app.settings.users.delete-failed'),
            ], 400);
        } elseif ($this->userRepository->count() == 1) {
            return new JsonResource([
                'message' => trans('admin::app.settings.users.last-delete-error'),
            ], 400);
        } else {
            Event::dispatch('settings.user.delete.before', $id);

            try {
                $this->userRepository->delete($id);

                Event::dispatch('settings.user.delete.after', $id);

                return new JsonResource([
                    'message' => trans('admin::app.settings.users.delete-success'),
                ]);
            } catch (\Exception $exception) {
                return new JsonResource([
                    'message' => $exception->getMessage(),
                ], 500);
            }
        }
    }

    /**
     * Mass update the specified resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest)
    {
        $userIds = $massUpdateRequest->input('indices');

        $count = 0;

        foreach ($userIds as $userId) {
            if (auth()->guard()->user()->id == $userId) {
                continue;
            }

            Event::dispatch('settings.user.update.before', $userId);

            $this->userRepository->update([
                'status' => request('value'),
            ], $userId);

            Event::dispatch('settings.user.update.after', $userId);

            $count++;
        }

        if (! $count) {
            return new JsonResource([
                'message' => trans('admin::app.settings.users.mass-update-failed'),
            ], 500);
        }

        return new JsonResource([
            'message' => trans('admin::app.settings.users.mass-update-success'),
        ]);
    }

    /**
     * Mass delete the specified resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $userIds = $massDestroyRequest->input('indices');

        $count = 0;

        foreach ($userIds as $userId) {
            if (auth()->guard()->user()->id == $userId) {
                continue;
            }

            Event::dispatch('settings.user.delete.before', $userId);

            $this->userRepository->delete($userId);

            Event::dispatch('settings.user.delete.after', $userId);

            $count++;
        }

        if (! $count) {
            return new JsonResource([
                'message' => trans('admin::app.settings.users.mass-delete-failed'),
            ], 500);
        }

        return new JsonResource([
            'message' => trans('admin::app.settings.users.mass-delete-success'),
        ]);
    }
}
