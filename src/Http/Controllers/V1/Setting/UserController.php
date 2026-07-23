<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Prettus\Repository\Criteria\RequestCriteria;
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
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $users = $this->allResources($this->userRepository);

        return UserResource::collection($users);
    }

    /**
     * Show resource.
     */
    public function show(int $id): UserResource
    {
        $resource = $this->findOrFailResource($this->userRepository, $id);

        return new UserResource($resource);
    }

    /**
     * Search user results.
     */
    public function search(): JsonResource
    {
        $users = $this->userRepository
            ->pushCriteria(app(RequestCriteria::class))
            ->limit(request()->input('limit') ?? 10)
            ->all();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResource
    {
        $this->validate(request(), [
            'email'            => 'required|email|unique:users,email',
            'name'             => 'required',
            'password'         => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'role_id'          => 'required|exists:roles,id',
            'view_permission'  => 'required|in:global,group,individual',
            'status'           => 'sometimes|boolean',
            'groups'           => 'sometimes|array',
            'groups.*'         => 'integer|exists:groups,id',
        ]);

        $data = request()->all();

        $data['password'] = bcrypt($data['password']);

        $data['status'] = request()->boolean('status') ? 1 : 0;

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
            'message' => trans('rest-api::app.settings.users.create-success'),
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
        $this->findOrFailResource($this->userRepository, $id);

        $this->validate(request(), [
            'email'            => 'required|email|unique:users,email,'.$id,
            'name'             => 'required',
            'password'         => 'nullable|min:6',
            'confirm_password' => 'nullable|required_with:password|same:password',
            'role_id'          => 'required|exists:roles,id',
            'status'           => 'required|in:0,1',
            'view_permission'  => 'required|in:global,group,individual',
            'groups'           => 'sometimes|array',
            'groups.*'         => 'integer|exists:groups,id',
        ]);

        $data = request()->all();

        if (empty($data['password'])) {
            unset($data['password'], $data['confirm_password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        if (auth()->guard()->user()->id != $id) {
            $data['status'] = isset($data['status']) ? $data['status'] : 0;
        }

        Event::dispatch('settings.user.update.before', $id);

        $admin = $this->userRepository->update($data, $id);

        $admin->view_permission = $data['view_permission'];

        $admin->save();

        $admin->groups()->sync($data['groups'] ?? []);

        Event::dispatch('settings.user.update.after', $admin);

        return new JsonResource([
            'data'    => new UserResource($admin),
            'message' => trans('rest-api::app.settings.users.updated-success'),
        ]);
    }

    /**
     * Destroy specified user.
     */
    public function destroy(int $id): JsonResource|JsonResponse
    {
        $this->findOrFailResource($this->userRepository, $id);

        if (auth()->guard()->user()->id == $id) {
            return $this->respondError(trans('rest-api::app.settings.users.delete-failed'), 400);
        }

        if ($this->userRepository->count() == 1) {
            return $this->respondError(trans('rest-api::app.settings.users.last-delete-error'), 400);
        }

        Event::dispatch('settings.user.delete.before', $id);

        try {
            $this->userRepository->delete($id);

            Event::dispatch('settings.user.delete.after', $id);

            return $this->respondSuccess(trans('rest-api::app.settings.users.delete-success'));
        } catch (\Exception $exception) {
            return $this->respondError(trans('rest-api::app.settings.users.delete-failed'), 500);
        }
    }

    /**
     * Mass update the specified resources.
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResource|JsonResponse
    {
        $this->validate(request(), [
            'value' => 'required|in:0,1',
        ]);

        $userIds = $massUpdateRequest->input('indices', []);

        $count = 0;

        foreach ($userIds as $userId) {
            if (auth()->guard()->user()->id == $userId) {
                continue;
            }

            $user = $this->userRepository->find($userId);

            if (! $user) {
                continue;
            }

            Event::dispatch('settings.user.update.before', $userId);

            $user->update(['status' => $massUpdateRequest->input('value')]);

            Event::dispatch('settings.user.update.after', $userId);

            $count++;
        }

        if (! $count) {
            return $this->respondError(trans('rest-api::app.settings.users.mass-update-failed'), 404);
        }

        return $this->respondSuccess(trans('rest-api::app.settings.users.mass-update-success'));
    }

    /**
     * Mass delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResource|JsonResponse
    {
        $userIds = $massDestroyRequest->input('indices', []);

        $count = 0;

        foreach ($userIds as $userId) {
            if (auth()->guard()->user()->id == $userId) {
                continue;
            }

            $user = $this->userRepository->find($userId);

            if (! $user) {
                continue;
            }

            Event::dispatch('settings.user.delete.before', $userId);

            $user->delete();

            Event::dispatch('settings.user.delete.after', $userId);

            $count++;
        }

        if (! $count) {
            return $this->respondError(trans('rest-api::app.settings.users.mass-delete-failed'), 404);
        }

        return $this->respondSuccess(trans('rest-api::app.settings.users.mass-delete-success'));
    }
}
