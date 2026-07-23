<?php

namespace Webkul\RestApi\Tests\Integration;

/**
 * Lead endpoints against a live Krayin instance, focused on the error contracts
 * this fork hardened: a missing lead id on show/update/destroy is a JSON 404
 * (not a 500 after partially writing data), invalid input is a 422, and
 * mass-destroy handles unknown ids gracefully instead of crashing.
 */
class LeadsIntegrationTest extends IntegrationTestCase
{
    private const MISSING_ID = 999999999;

    public function test_list_leads_returns_paginated_json(): void
    {
        $response = $this->get('api/v1/leads');

        $this->assertSame(200, $response['status']);
        $this->assertArrayHasKey('data', $response['json']);
        $this->assertArrayHasKey('meta', $response['json']);
    }

    public function test_show_nonexistent_lead_returns_404_json(): void
    {
        $response = $this->get('api/v1/leads/'.self::MISSING_ID);

        $this->assertSame(404, $response['status']);
        $this->assertHumanMessage($response['json']);
    }

    public function test_update_nonexistent_lead_returns_404_json(): void
    {
        $response = $this->put('api/v1/leads/'.self::MISSING_ID, ['title' => 'nope']);

        $this->assertSame(404, $response['status']);
        $this->assertHumanMessage($response['json']);
    }

    public function test_delete_nonexistent_lead_returns_404_json(): void
    {
        $response = $this->delete('api/v1/leads/'.self::MISSING_ID);

        $this->assertSame(404, $response['status']);
        $this->assertHumanMessage($response['json']);
    }

    /**
     * Creating a lead against a pipeline stage that does not exist must surface
     * a clean JSON 404 (the stage lookup uses findOrFail), NOT a 500 after
     * partial data has been written — the lead-endpoint hardening in this fork.
     */
    public function test_create_lead_with_unknown_stage_returns_404(): void
    {
        $response = $this->post('api/v1/leads', [
            'title'                  => $this->unique('Lead '),
            'lead_pipeline_stage_id' => self::MISSING_ID,
        ]);

        $this->assertSame(404, $response['status']);
        $this->assertHumanMessage($response['json']);
    }

    /**
     * mass-destroy with only unknown ids must not blow up with a 500; it should
     * answer with a clean JSON status (mass-destroy reports the real affected
     * count rather than crashing on a partially-valid set).
     */
    public function test_mass_destroy_with_unknown_ids_is_handled_gracefully(): void
    {
        $response = $this->request('POST', 'api/v1/leads/mass-destroy', [
            'indices' => [self::MISSING_ID, self::MISSING_ID - 1],
        ]);

        $this->assertNotSame(500, $response['status'], 'mass-destroy must not 500 on unknown ids.');
        $this->assertLessThan(500, $response['status']);
        $this->assertHumanMessage($response['json']);
    }

    /**
     * Bug 2: a lead's user-defined custom fields must be returned by the API the
     * way the panel shows them — both in the list (eager-loaded) and the single
     * GET. Gated on a known custom attribute code (set KRAYIN_CUSTOM_LEAD_ATTR to
     * a user-defined attribute code on leads), mirroring the suite's env-gating.
     */
    public function test_get_lead_exposes_user_defined_custom_field_keys(): void
    {
        $attribute = getenv('KRAYIN_CUSTOM_LEAD_ATTR') ?: null;

        if (! $attribute) {
            $this->markTestSkipped('Set KRAYIN_CUSTOM_LEAD_ATTR to a user-defined lead attribute code to run this.');
        }

        $list = $this->get('api/v1/leads');
        $leadId = $list['json']['data'][0]['id'] ?? null;

        if (! $leadId) {
            $this->markTestSkipped('No leads exist to check custom-field exposure.');
        }

        $this->assertArrayHasKey($attribute, $list['json']['data'][0], 'List did not expose custom field '.$attribute);

        $show = $this->get("api/v1/leads/{$leadId}");
        $this->assertSame(200, $show['status']);
        $this->assertArrayHasKey($attribute, $show['json']['data'], 'Show did not expose custom field '.$attribute);
    }

    /**
     * Bug 3: a partial PUT must not clobber the lead's stage. The update handler
     * used to fall back to the default pipeline's first stage whenever the
     * request omitted lead_pipeline_stage_id — which silently moved the lead out
     * of its current stage and, because that fallback stage is neither "won" nor
     * "lost", made the core repository reset closed_at to null. This reproduces
     * the report: a lead parked in a "lost" stage (closed_at set) is PUT with a
     * single unrelated field, and both its stage and closed_at must survive.
     */
    public function test_partial_update_preserves_stage_and_closed_at(): void
    {
        // Find a "lost" stage on any pipeline so we can park a lead there.
        $pipelines = $this->get('api/v1/settings/pipelines');

        $lostStageId = null;
        $pipelineId = null;

        foreach ($pipelines['json']['data'] ?? [] as $pipeline) {
            foreach ($pipeline['stages'] ?? [] as $stage) {
                if (($stage['code'] ?? null) === 'lost') {
                    $lostStageId = (int) $stage['id'];
                    $pipelineId = (int) $pipeline['id'];

                    break 2;
                }
            }
        }

        if (! $lostStageId) {
            $this->markTestSkipped('No pipeline with a "lost" stage available to test stage preservation.');
        }

        // Create a throwaway lead directly in the lost stage; store() stamps
        // closed_at for won/lost stages, giving us the state to protect.
        $create = $this->post('api/v1/leads', [
            'title'                  => $this->unique('Lead '),
            'description'            => 'stage-preservation regression',
            'lead_value'             => 100,
            'lead_pipeline_id'       => $pipelineId,
            'lead_pipeline_stage_id' => $lostStageId,
            'person'                 => [
                'name'   => $this->unique('Person '),
                'emails' => [['value' => $this->unique('it').'@example.test', 'label' => 'work']],
            ],
        ]);

        $this->assertContains($create['status'], [200, 201], 'Create lead should succeed: '.json_encode($create));

        $leadId = $create['json']['data']['id'] ?? null;
        $this->assertNotNull($leadId, 'Created lead has no id: '.json_encode($create));
        $this->deleteOnTearDown("api/v1/leads/{$leadId}");

        // Precondition: the lead sits in the lost stage with closed_at set.
        $before = $this->get("api/v1/leads/{$leadId}");
        $this->assertSame($lostStageId, (int) $before['json']['data']['lead_pipeline_stage_id']);
        $this->assertNotNull(
            $before['json']['data']['closed_at'],
            'A lead in a "lost" stage should have closed_at set: '.json_encode($before)
        );
        $closedAt = $before['json']['data']['closed_at'];

        // The bug trigger: PUT a single unrelated field WITHOUT the stage.
        $put = $this->put("api/v1/leads/{$leadId}", [
            'title' => $this->unique('Renamed '),
        ]);
        $this->assertSame(200, $put['status'], 'Partial update should succeed: '.json_encode($put));

        // Both the stage and closed_at must be intact after the partial update.
        $after = $this->get("api/v1/leads/{$leadId}");
        $this->assertSame(
            $lostStageId,
            (int) $after['json']['data']['lead_pipeline_stage_id'],
            'Partial update moved the lead out of its stage: '.json_encode($after)
        );
        $this->assertNotNull(
            $after['json']['data']['closed_at'],
            'Partial update wiped closed_at: '.json_encode($after)
        );
        $this->assertSame(
            $closedAt,
            $after['json']['data']['closed_at'],
            'closed_at changed on a partial update that never touched the stage.'
        );
    }

    /**
     * Companion to the stage bug: the same partial-destructive pattern hits
     * expected_close_date, but the clobber lives in the core repository (it nulls
     * the field whenever it is empty in $data, with no isset guard). This
     * controller defends against it — a PUT that omits expected_close_date must
     * leave the lead's existing value intact.
     */
    public function test_partial_update_preserves_expected_close_date(): void
    {
        $expected = '2030-12-31';

        $create = $this->post('api/v1/leads', [
            'title'               => $this->unique('Lead '),
            'description'         => 'expected-close-date regression',
            'lead_value'          => 100,
            'expected_close_date' => $expected,
            'person'              => [
                'name'   => $this->unique('Person '),
                'emails' => [['value' => $this->unique('it').'@example.test', 'label' => 'work']],
            ],
        ]);

        $this->assertContains($create['status'], [200, 201], 'Create lead should succeed: '.json_encode($create));

        $leadId = $create['json']['data']['id'] ?? null;
        $this->assertNotNull($leadId, 'Created lead has no id: '.json_encode($create));
        $this->deleteOnTearDown("api/v1/leads/{$leadId}");

        // Precondition: the lead carries an expected_close_date.
        $before = $this->get("api/v1/leads/{$leadId}");
        $storedDate = $before['json']['data']['expected_close_date'] ?? null;
        $this->assertNotNull(
            $storedDate,
            'Lead should have been created with an expected_close_date: '.json_encode($before)
        );

        // The bug trigger: PUT a single unrelated field WITHOUT expected_close_date.
        $put = $this->put("api/v1/leads/{$leadId}", [
            'title' => $this->unique('Renamed '),
        ]);
        $this->assertSame(200, $put['status'], 'Partial update should succeed: '.json_encode($put));

        // expected_close_date must be intact after the partial update.
        $after = $this->get("api/v1/leads/{$leadId}");
        $this->assertSame(
            $storedDate,
            $after['json']['data']['expected_close_date'] ?? null,
            'Partial update wiped expected_close_date: '.json_encode($after)
        );
    }
}
