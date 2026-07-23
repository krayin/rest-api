<?php

namespace Webkul\RestApi\Tests\Unit;

use Webkul\RestApi\Support\ParticipantNormalizer;
use Webkul\RestApi\Tests\TestCase;

/**
 * The participant reshaping is pure array logic (the existence validation that
 * follows it needs a DB and is covered by ActivitiesIntegrationTest). It turns
 * whatever a client sends — a flat list of user ids, or the nested
 * `{users, persons}` shape — into the nested shape the core ActivityRepository
 * consumes, so a flat `participants:[1,2]` actually links participants.
 */
class ParticipantNormalizerTest extends TestCase
{
    /**
     * @param  mixed  $raw
     * @return array{users: array<int, int>, persons: array<int, int>}
     */
    private function normalize($raw): array
    {
        return (new ParticipantNormalizer)->normalize($raw);
    }

    public function test_flat_array_is_treated_as_user_ids(): void
    {
        $this->assertSame(
            ['users' => [1, 2, 3], 'persons' => []],
            $this->normalize([1, 2, 3])
        );
    }

    public function test_nested_shape_is_preserved(): void
    {
        $this->assertSame(
            ['users' => [1], 'persons' => [4, 5]],
            $this->normalize(['users' => [1], 'persons' => [4, 5]])
        );
    }

    public function test_partial_nested_shape_defaults_the_missing_side(): void
    {
        $this->assertSame(
            ['users' => [], 'persons' => [7]],
            $this->normalize(['persons' => [7]])
        );
    }

    public function test_blank_and_null_entries_are_filtered_and_values_cast_to_int(): void
    {
        $this->assertSame(
            ['users' => [1, 2], 'persons' => []],
            $this->normalize(['1', '', 2, null])
        );
    }

    public function test_empty_array_clears_all_participants(): void
    {
        $this->assertSame(
            ['users' => [], 'persons' => []],
            $this->normalize([])
        );
    }
}
