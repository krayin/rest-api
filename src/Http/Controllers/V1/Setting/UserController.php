<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
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
     *
     * The listing is scoped to the users the acting user created, plus their own account, so a
     * group or individual scoped user cannot enumerate accounts outside their own data scope.
     */
    public function index(): JsonResource
    {
        $query = $this->userRepository->query();

        if ($userIds = $this->authorizedUserIds()) {
            $query->where(function ($query) use ($userIds) {
                $query->whereIn('users.created_by', $userIds)
                    ->orWhere('users.id', auth()->guard()->user()->id);
            });
        }

        foreach (request()->except($this->excludeKeys) as $input => $value) {
            $query->whereIn($input, array_map('trim', explode(',', $value)));
        }

        if ($sort = request()->input('sort')) {
            $query->orderBy($sort, request()->input('order') ?? 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        if (is_null(request()->input('pagination')) || request()->input('pagination')) {
            return UserResource::collection($query->paginate(request()->input('limit') ?? 10));
        }

        return UserResource::collection($query->get());
    }

    /**
     * Show resource.
     */
    public function show(int $id): UserResource
    {
        $resource = $this->userRepository->findOrFail($id);

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
    public function store()
    {
        $validated = $this->validate(request(), [
            'email'            => 'required|email|unique:users,email',
            'name'             => 'required',
            'password'         => 'nullable',
            'confirm_password' => 'nullable|required_with:password|same:password',
            'role_id'          => 'required|integer|exists:roles,id',
            'status'           => 'boolean|in:0,1',
            'view_permission'  => 'string|in:global,group,individual',
            'groups'           => 'required_if:view_permission,group|array',
            'groups.*'         => 'integer|exists:groups,id',
        ]);

        /**
         * A non-administrator may only assign a role, and a data scope, that do not exceed their own.
         */
        if ($message = $this->unauthorizedRoleAssignment($validated['role_id'])) {
            return $this->errorResponse($message, 401);
        }

        if ($message = $this->unauthorizedScopeAssignment(request('view_permission'))) {
            return $this->errorResponse($message, 401);
        }

        /**
         * Build the payload from the validated data only; never mass-assign the raw request, which
         * would let a caller write columns such as `created_by` or `api_token` directly.
         */
        $data = Arr::only($validated, [
            'name', 'email', 'password', 'role_id', 'status', 'view_permission', 'groups',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $data['status'] = (int) ($data['status'] ?? 0);

        /**
         * Record who created the account so the listing can scope it by ownership.
         */
        $data['created_by'] = auth()->guard()->user()->id;

        Event::dispatch('settings.user.create.before');

        $admin = $this->userRepository->create($data);

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
     */
    public function update(int $id)
    {
        $validated = $this->validate(request(), [
            'email'            => 'required|email|unique:users,email,'.$id,
            'name'             => 'required',
            'password'         => 'nullable|min:6',
            'confirm_password' => 'nullable|required_with:password|same:password',
            'role_id'          => 'required|integer|exists:roles,id',
            'status'           => 'nullable|boolean|in:0,1',
            'view_permission'  => 'required|string|in:global,group,individual',
            'groups'           => 'required_if:view_permission,group|array',
            'groups.*'         => 'integer|exists:groups,id',
        ]);

        $targetUser = $this->userRepository->findOrFail($id);

        $authUser = auth()->guard()->user();

        $isAdministrator = $authUser->role?->permission_type === 'all';

        $isSelf = (int) $authUser->id === (int) $id;

        /**
         * A delegated manager may only act on users whose permissions do not exceed their own.
         */
        if (! $isSelf && ! $this->canManageUser($targetUser)) {
            return $this->errorResponse(trans('admin::app.errors.user-exceeds-own'), 401);
        }

        /**
         * A user may never escalate their own account: reject any attempt to change their own role
         * or data scope rather than silently reporting success.
         */
        if (
            ! $isAdministrator
            && $isSelf
            && ((int) $validated['role_id'] !== (int) $authUser->role_id
                || $validated['view_permission'] !== $authUser->view_permission)
        ) {
            return $this->errorResponse(trans('admin::app.errors.own-privileges'), 401);
        }

        if ($message = $this->unauthorizedRoleAssignment($validated['role_id'])) {
            return $this->errorResponse($message, 401);
        }

        if ($message = $this->unauthorizedScopeAssignment($validated['view_permission'], $targetUser->view_permission)) {
            return $this->errorResponse($message, 401);
        }

        /**
         * Whitelist writable fields. A user editing their OWN account may only touch profile fields
         * (never self-escalate); editing another user (delegated management) they may also set the
         * role, data scope, status and groups — all already constrained above to their own level.
         */
        $data = Arr::only($validated, ($isAdministrator || ! $isSelf)
            ? ['name', 'email', 'password', 'role_id', 'status', 'view_permission', 'groups']
            : ['name', 'email', 'password']
        );

        /**
         * The password key is optional on update, so it is only hashed when a new one was supplied.
         * Reading it unguarded previously raised an "Undefined array key" error on any update that
         * omitted the field.
         */
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        /**
         * The primary administrator (id 1) can never be demoted, rescoped, or disabled.
         */
        if ((int) $id === 1) {
            if (
                (int) $validated['role_id'] !== (int) $targetUser->role_id
                || $validated['view_permission'] !== $targetUser->view_permission
                || (request()->filled('status') && ! (int) $validated['status'])
            ) {
                return $this->errorResponse(trans('admin::app.errors.primary-admin-protected'), 401);
            }

            $data['status'] = 1;
        }

        if ($isSelf) {
            $data['status'] = 1;
        }

        Event::dispatch('settings.user.update.before', $id);

        $admin = $this->userRepository->update($data, $id);

        /**
         * Group membership is only writable when the role/scope fields are (an administrator, or a
         * delegated manager editing another user) and never for the primary administrator.
         */
        if (($isAdministrator || ! $isSelf) && (int) $id !== 1) {
            $admin->groups()->sync($validated['groups'] ?? []);
        }

        Event::dispatch('settings.user.update.after', $admin);

        return new JsonResource([
            'data'    => new UserResource($admin),
            'message' => trans('rest-api::app.settings.users.updated-success'),
        ]);
    }

    /**
     * Destroy specified user.
     */
    public function destroy(int $id)
    {
        if (auth()->guard()->user()->id == $id) {
            return $this->errorResponse(trans('rest-api::app.settings.users.delete-failed'), 400);
        }

        /**
         * The primary administrator is protected, and at least one user must always remain.
         */
        if ($this->userRepository->count() == 1 || (int) $id === 1) {
            return $this->errorResponse(trans('rest-api::app.settings.users.last-delete-error'), 400);
        }

        $targetUser = $this->userRepository->findOrFail($id);

        if (! $this->canManageUser($targetUser)) {
            return $this->errorResponse(trans('admin::app.errors.user-exceeds-own'), 401);
        }

        Event::dispatch('settings.user.delete.before', $id);

        try {
            $this->userRepository->delete($id);

            Event::dispatch('settings.user.delete.after', $id);

            return new JsonResource([
                'message' => trans('rest-api::app.settings.users.delete-success'),
            ]);
        } catch (\Exception $exception) {
            report($exception);

            return $this->errorResponse(trans('rest-api::app.settings.users.delete-failed'), 500);
        }
    }

    /**
     * Mass update the specified resources.
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest)
    {
        $userIds = $massUpdateRequest->input('indices');

        $count = 0;

        foreach ($userIds as $userId) {
            /**
             * Skip the acting user and the primary administrator, and any user whose permissions
             * exceed the acting user's own.
             */
            if (
                auth()->guard()->user()->id == $userId
                || (int) $userId === 1
            ) {
                continue;
            }

            $user = $this->userRepository->find($userId);

            if (! $user || ! $this->canManageUser($user)) {
                continue;
            }

            Event::dispatch('settings.user.update.before', $userId);

            $user->update(['status' => $massUpdateRequest->input('value')]);

            Event::dispatch('settings.user.update.after', $userId);

            $count++;
        }

        if (! $count) {
            return $this->errorResponse(trans('rest-api::app.settings.users.mass-update-failed'), 400);
        }

        return new JsonResource([
            'message' => trans('rest-api::app.settings.users.mass-update-success'),
        ]);
    }

    /**
     * Mass delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $userIds = $massDestroyRequest->input('indices');

        $count = 0;

        foreach ($userIds as $userId) {
            if (
                auth()->guard()->user()->id == $userId
                || (int) $userId === 1
            ) {
                continue;
            }

            $user = $this->userRepository->find($userId);

            if (! $user || ! $this->canManageUser($user)) {
                continue;
            }

            Event::dispatch('settings.user.delete.before', $userId);

            $user->delete();

            Event::dispatch('settings.user.delete.after', $userId);

            $count++;
        }

        if (! $count) {
            return $this->errorResponse(trans('rest-api::app.settings.users.mass-delete-failed'), 400);
        }

        return new JsonResource([
            'message' => trans('rest-api::app.settings.users.mass-delete-success'),
        ]);
    }

    /**
     * Determine whether the acting user may manage the given user.
     *
     * Ported from the admin panel so the API enforces the same privilege ceiling.
     */
    protected function canManageUser($targetUser): bool
    {
        $authUser = auth()->guard()->user();

        if ($authUser->role?->permission_type === 'all') {
            return true;
        }

        if ($targetUser->role?->permission_type === 'all') {
            return false;
        }

        return empty(array_diff($targetUser->role?->permissions ?? [], $authUser->role?->permissions ?? []));
    }

    /**
     * Return an error message when the acting user may not assign the given role, else null.
     */
    protected function unauthorizedRoleAssignment(?int $roleId): ?string
    {
        $authUser = auth()->guard()->user();

        /**
         * Full administrators are trusted to grant any role.
         */
        if ($authUser->role?->permission_type === 'all') {
            return null;
        }

        $role = $this->roleRepository->find($roleId);

        /**
         * A non-administrator can never grant the administrator (`all`) role, nor a role holding
         * any permission they do not personally hold.
         */
        if (
            ! $role
            || $role->permission_type === 'all'
            || ! empty(array_diff($role->permissions ?? [], $authUser->role?->permissions ?? []))
        ) {
            return trans('admin::app.errors.role-exceeds-own');
        }

        return null;
    }

    /**
     * Return an error message when the acting user may not assign the given scope, else null.
     */
    protected function unauthorizedScopeAssignment(?string $scope, ?string $currentScope = null): ?string
    {
        $authUser = auth()->guard()->user();

        if ($authUser->role?->permission_type === 'all') {
            return null;
        }

        $rank = ['individual' => 0, 'group' => 1, 'global' => 2];

        /**
         * The scope may be kept at the target's existing level, but never raised beyond the greater
         * of the acting user's own scope and the target's current scope — so no extra visibility is
         * ever granted.
         */
        $ceiling = max($rank[$authUser->view_permission] ?? 0, $rank[$currentScope] ?? 0);

        if (($rank[$scope] ?? 0) > $ceiling) {
            return trans('admin::app.errors.scope-exceeds-own');
        }

        return null;
    }
}
