<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\RoleResource;
use Webkul\User\Repositories\RoleRepository;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected RoleRepository $roleRepository) {}

    /**
     * Display a listing of the resource.
     *
     * The listing is scoped to the roles the acting user created, plus their own role, so a
     * group or individual scoped user cannot enumerate roles outside their own data scope.
     */
    public function index(): JsonResource
    {
        $query = $this->roleRepository->query();

        if ($userIds = $this->authorizedUserIds()) {
            $query->where(function ($query) use ($userIds) {
                $query->whereIn('roles.created_by', $userIds)
                    ->orWhere('roles.id', auth()->guard()->user()->role_id);
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
            return RoleResource::collection($query->paginate(request()->input('limit') ?? 10));
        }

        return RoleResource::collection($query->get());
    }

    /**
     * Show resource.
     */
    public function show(int $id): RoleResource
    {
        $resource = $this->roleRepository->findOrFail($id);

        return new RoleResource($resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResource
    {
        $validated = $this->validate(request(), [
            'name'            => 'required|unique:roles,name',
            'description'     => 'required',
            'permission_type' => 'required|in:all,custom',
            'permissions'     => 'required_if:permission_type,custom|array',
        ]);

        $permissions = $validated['permission_type'] === 'custom'
            ? ($validated['permissions'] ?? [])
            : [];

        if (! $this->canManageRole($validated['permission_type'], $permissions)) {
            return new JsonResource([
                'message' => trans('admin::app.errors.role-permissions-exceed-own'),
            ], 401);
        }

        Event::dispatch('settings.role.create.before');

        $role = $this->roleRepository->create([
            'name'            => $validated['name'],
            'description'     => $validated['description'],
            'permission_type' => $validated['permission_type'],
            'permissions'     => $permissions,
            'created_by'      => auth()->guard()->user()->id,
        ]);

        Event::dispatch('settings.role.create.after', $role);

        return new JsonResource([
            'data'    => new RoleResource($role),
            'message' => trans('rest-api::app.settings.roles.create-success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): JsonResource
    {
        $validated = $this->validate(request(), [
            'name'            => 'required|unique:roles,name,'.$id,
            'description'     => 'required',
            'permission_type' => 'required|in:all,custom',
            'permissions'     => 'required_if:permission_type,custom|array',
        ]);

        $role = $this->roleRepository->findOrFail($id);

        /**
         * Changing your own role would let a user re-grant themselves permissions mid-request,
         * so it is refused outright, exactly as the admin panel does.
         */
        if (auth()->guard()->user()->role_id == $id) {
            return new JsonResource([
                'message' => trans('admin::app.settings.roles.index.current-role-edit-error'),
            ], 401);
        }

        $permissions = $validated['permission_type'] === 'custom'
            ? ($validated['permissions'] ?? [])
            : [];

        if (! $this->canManageRole($validated['permission_type'], $permissions, $role)) {
            return new JsonResource([
                'message' => trans('admin::app.errors.role-permissions-exceed-own'),
            ], 401);
        }

        Event::dispatch('settings.role.update.before', $id);

        $role = $this->roleRepository->update([
            'name'            => $validated['name'],
            'description'     => $validated['description'],
            'permission_type' => $validated['permission_type'],
            'permissions'     => $permissions,
        ], $id);

        Event::dispatch('settings.role.update.after', $role);

        return new JsonResource([
            'data'    => new RoleResource($role),
            'message' => trans('rest-api::app.settings.roles.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResource
    {
        $response = ['code' => 400];

        $role = $this->roleRepository->findOrFail($id);

        /**
         * The 2.2 role model exposes this relation as `users`. The previous `admins` lookup
         * resolved to null, so a role still assigned to users was deleted and its users
         * were left orphaned.
         */
        if ($role->users()->count() >= 1) {
            $response['message'] = trans('rest-api::app.settings.roles.being-used');
        } elseif ($this->roleRepository->count() == 1) {
            $response['message'] = trans('rest-api::app.settings.roles.last-delete-error');
        } elseif (! $this->canManageRole($role->permission_type, $role->permissions ?? [], $role)) {
            $response = [
                'code'    => 401,
                'message' => trans('admin::app.errors.role-permissions-exceed-own'),
            ];
        } else {
            try {
                Event::dispatch('settings.role.delete.before', $id);

                if (auth()->guard()->user()->role_id == $id) {
                    $response['message'] = trans('rest-api::app.settings.roles.current-role-delete-error');
                } else {
                    $this->roleRepository->delete($id);

                    Event::dispatch('settings.role.delete.after', $id);

                    $response = [
                        'code'    => 200,
                        'message' => trans('rest-api::app.settings.roles.delete-success'),
                    ];
                }
            } catch (\Exception $exception) {
                report($exception);

                $response['message'] = trans('rest-api::app.settings.roles.delete-failed');
            }
        }

        return new JsonResource(['message' => $response['message']], $response['code']);
    }

    /**
     * Determine whether the acting user may create or edit a role with the given permissions.
     *
     * Ported from the admin panel so the API enforces the same privilege ceiling.
     */
    protected function canManageRole(?string $permissionType, array $permissions, $existingRole = null): bool
    {
        $authUser = auth()->guard()->user();

        /**
         * Full administrators are trusted to manage any role.
         */
        if ($authUser->role?->permission_type === 'all') {
            return true;
        }

        $ownPermissions = $authUser->role?->permissions ?? [];

        /**
         * A non-administrator can never create or promote a role to full administrator, nor grant
         * any permission they do not personally hold.
         */
        if ($permissionType === 'all'
            || ! empty(array_diff($permissions, $ownPermissions))
        ) {
            return false;
        }

        /**
         * The role being edited must not already be broader than the acting user's own role, so a
         * full-administrator role (or any role holding extra permissions) cannot be tampered with.
         */
        if ($existingRole
            && ($existingRole->permission_type === 'all'
                || ! empty(array_diff($existingRole->permissions ?? [], $ownPermissions)))
        ) {
            return false;
        }

        return true;
    }
}
