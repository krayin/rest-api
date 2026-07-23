<?php

namespace Webkul\RestApi\Http\Controllers\V1\Concerns;

/**
 * Resolves the set of user ids the authenticated user is allowed to see records
 * for, honoring their `view_permission` (global / group / individual).
 *
 * Expects the consuming controller to expose a `$userRepository`
 * (Webkul\User\Repositories\UserRepository) for the group case.
 */
trait ResolvesAuthorizedUserIds
{
    /**
     * @return array<int, int>|null  null = global visibility (no filtering)
     */
    protected function getAuthorizedUserIds(): ?array
    {
        $user = auth()->user();

        if ($user->view_permission == 'global') {
            return null;
        }

        if ($user->view_permission == 'group') {
            return $this->userRepository->getCurrentUserGroupsUserIds();
        }

        return [$user->id];
    }
}
