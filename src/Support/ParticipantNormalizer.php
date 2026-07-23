<?php

namespace Webkul\RestApi\Support;

/**
 * Reshapes an activity's `participants` input into the nested `{users, persons}`
 * form the Krayin core ActivityRepository consumes.
 *
 * A value already carrying `users`/`persons` keys is kept as-is (each side
 * defaulting to an empty list); anything else is treated as a flat list of user
 * ids. Blank/null entries are dropped and the rest cast to ints. Pure (no
 * request/DB access) so it is unit-testable on its own.
 */
class ParticipantNormalizer
{
    /**
     * @param  mixed  $raw
     * @return array{users: array<int, int>, persons: array<int, int>}
     */
    public function normalize($raw): array
    {
        if (is_array($raw) && (array_key_exists('users', $raw) || array_key_exists('persons', $raw))) {
            $users   = $raw['users'] ?? [];
            $persons = $raw['persons'] ?? [];
        } else {
            $users   = $raw ?? [];
            $persons = [];
        }

        $normalize = fn ($ids) => array_values(array_map(
            'intval',
            array_filter((array) $ids, fn ($value) => $value !== '' && $value !== null)
        ));

        return [
            'users'   => $normalize($users),
            'persons' => $normalize($persons),
        ];
    }
}
