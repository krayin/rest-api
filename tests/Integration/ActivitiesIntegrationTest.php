<?php

namespace Webkul\RestApi\Tests\Integration;

/**
 * Activity endpoints against a live Krayin instance, focused on the participant
 * linking this fork fixes: a flat `participants:[<userId>]` (the natural REST
 * shape) must actually link the users — previously it silently linked nobody
 * because the core repository only understands the nested `{users, persons}`
 * shape. Also covers replacing/clearing participants and a plain scalar update.
 */
class ActivitiesIntegrationTest extends IntegrationTestCase
{
    public function test_flat_participants_array_links_the_users(): void
    {
        $userId = $this->anyUserId();

        $created = $this->post('api/v1/activities', $this->activityPayload([
            'participants' => [$userId],
        ]));

        $this->assertContains($created['status'], [200, 201], 'Create should succeed: '.json_encode($created));
        $id = $created['json']['data']['id'] ?? null;
        $this->assertNotNull($id, 'Created activity has no id: '.json_encode($created));
        $this->deleteOnTearDown("api/v1/activities/{$id}");

        // The create response already reflects the linked participant.
        $this->assertContains($userId, $this->participantUserIds($created['json']['data']));

        // And so does a fresh GET.
        $show = $this->get("api/v1/activities/{$id}");
        $this->assertSame(200, $show['status']);
        $this->assertContains($userId, $this->participantUserIds($show['json']['data']));
    }

    public function test_participants_can_be_replaced_and_cleared(): void
    {
        $userId = $this->anyUserId();

        $created = $this->post('api/v1/activities', $this->activityPayload([
            'participants' => [$userId],
        ]));
        $id = $created['json']['data']['id'] ?? null;
        $this->assertNotNull($id, 'Created activity has no id: '.json_encode($created));
        $this->deleteOnTearDown("api/v1/activities/{$id}");

        // Replace via the nested shape (panel/form-data style).
        $replaced = $this->put("api/v1/activities/{$id}", [
            'participants' => ['users' => [$userId], 'persons' => []],
        ]);
        $this->assertSame(200, $replaced['status'], 'Update should succeed: '.json_encode($replaced));
        $this->assertContains($userId, $this->participantUserIds($replaced['json']['data']));

        // Clearing all participants.
        $cleared = $this->put("api/v1/activities/{$id}", ['participants' => []]);
        $this->assertSame(200, $cleared['status']);
        $this->assertSame([], $this->participantUserIds($cleared['json']['data']));

        $show = $this->get("api/v1/activities/{$id}");
        $this->assertSame([], $this->participantUserIds($show['json']['data']));
    }

    public function test_unknown_participant_id_is_rejected_with_422(): void
    {
        $response = $this->post('api/v1/activities', $this->activityPayload([
            'participants' => [999999999],
        ]));

        $this->assertSame(422, $response['status'], 'Unknown participant must be a 422: '.json_encode($response));
        $this->assertHumanMessage($response['json']);
    }

    public function test_scalar_update_persists(): void
    {
        $created = $this->post('api/v1/activities', $this->activityPayload());
        $id = $created['json']['data']['id'] ?? null;
        $this->assertNotNull($id, 'Created activity has no id: '.json_encode($created));
        $this->deleteOnTearDown("api/v1/activities/{$id}");

        $newTitle = $this->unique('Renamed ');
        $put = $this->put("api/v1/activities/{$id}", ['title' => $newTitle]);
        $this->assertSame(200, $put['status'], 'Update should succeed: '.json_encode($put));

        $show = $this->get("api/v1/activities/{$id}");
        $this->assertSame($newTitle, $show['json']['data']['title'] ?? null);
    }

    public function test_malformed_participants_are_rejected_cleanly_never_500(): void
    {
        $garbageInputs = [
            ['participants' => 'fruta'],                    // a plain string
            ['participants' => ['users' => 'fruta']],       // nested but not a list
            ['participants' => [999999999]],                // a non-existent user id
            ['participants' => ['users' => [999999999]]],   // non-existent, nested
        ];

        foreach ($garbageInputs as $garbage) {
            $response = $this->post('api/v1/activities', $this->activityPayload($garbage));

            // Whatever we throw at it, it must answer with a clean client error
            // (422), never a 500.
            $this->assertLessThan(500, $response['status'], 'Garbage participants must not 500: '.json_encode([$garbage, $response]));
            $this->assertSame(422, $response['status'], 'Garbage participants should be a 422: '.json_encode([$garbage, $response]));

            // Clean up in the unlikely event one slipped through as a create.
            if (isset($response['json']['data']['id'])) {
                $this->deleteOnTearDown('api/v1/activities/'.$response['json']['data']['id']);
            }
        }
    }

    // -- Helpers ------------------------------------------------------------

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function activityPayload(array $overrides = []): array
    {
        return array_merge([
            'type'          => 'call',
            'title'         => $this->unique('Act '),
            'schedule_from' => '2026-07-27 09:30:00',
            'schedule_to'   => '2026-07-27 09:45:00',
            'is_done'       => 0,
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $activity
     * @return array<int, int>
     */
    private function participantUserIds(array $activity): array
    {
        return array_values(array_filter(array_map(
            fn ($participant) => $participant['user']['id'] ?? null,
            $activity['participants'] ?? []
        )));
    }

    private function anyUserId(): int
    {
        $response = $this->get('api/v1/settings/users');
        $this->assertSame(200, $response['status'], 'Could not list users: '.json_encode($response));

        $id = $response['json']['data'][0]['id'] ?? null;

        if (! $id) {
            $this->markTestSkipped('No users available to use as activity participants.');
        }

        return (int) $id;
    }
}
