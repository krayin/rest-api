<?php

namespace Webkul\RestApi\Tests\Integration;

/**
 * Pipeline settings endpoints against a live Krayin instance, focused on the PUT
 * this fork fixes: updating a pipeline used to 500 and leave a partial write
 * (new stages piled on top of the old ones, no sync). Now the update is atomic,
 * syncs the stage set (add / rename / delete) without duplicating, rejects a
 * missing `stages` with a 422 (not a 500), and refuses a stage id belonging to
 * another pipeline without mutating anything.
 */
class PipelinesIntegrationTest extends IntegrationTestCase
{
    public function test_create_pipeline_with_stages(): void
    {
        ['json' => $json] = $this->createPipeline();

        $this->assertCount(2, $json['data']['stages'] ?? []);
    }

    public function test_update_syncs_stages_without_duplicating_or_500(): void
    {
        ['id' => $id, 'json' => $json] = $this->createPipeline();

        $stages = $json['data']['stages'];
        $keptId = $stages[0]['id'];

        $put = $this->put("api/v1/settings/pipelines/{$id}", [
            'name'   => $this->unique('Pipe '),
            'stages' => [
                // Keep + rename the first stage (carries its id -> update in place).
                ['id' => $keptId, 'name' => 'Renamed A', 'code' => $stages[0]['code']],
                // Add a brand new stage (no id -> create).
                ['name' => 'Brand New', 'code' => 'new_'.uniqid()],
                // The second original stage is omitted -> it must be deleted.
            ],
        ]);

        $this->assertSame(200, $put['status'], 'Update should succeed: '.json_encode($put));

        $show = $this->get("api/v1/settings/pipelines/{$id}");
        $resultStages = $show['json']['data']['stages'] ?? [];

        $names = array_map(fn ($stage) => $stage['name'], $resultStages);
        $ids = array_map(fn ($stage) => $stage['id'], $resultStages);

        $this->assertCount(2, $resultStages, 'Stages must be synced, not accumulated: '.json_encode($resultStages));
        $this->assertContains('Renamed A', $names);
        $this->assertContains('Brand New', $names);
        $this->assertContains($keptId, $ids, 'The kept stage should be updated in place, not recreated.');
    }

    public function test_update_without_stages_returns_422_not_500(): void
    {
        ['id' => $id] = $this->createPipeline();

        $response = $this->put("api/v1/settings/pipelines/{$id}", [
            'name' => $this->unique('Pipe '),
        ]);

        $this->assertSame(422, $response['status'], 'Missing stages must be a 422, not a 500: '.json_encode($response));
        $this->assertHumanMessage($response['json']);
    }

    public function test_foreign_stage_id_is_rejected_and_leaves_pipeline_untouched(): void
    {
        ['id' => $id, 'json' => $json] = $this->createPipeline();
        $originalName = $json['data']['name'];

        $foreignStageId = $this->foreignStageId($id);

        if (! $foreignStageId) {
            $this->markTestSkipped('No other pipeline stage available to test cross-pipeline rejection.');
        }

        $response = $this->put("api/v1/settings/pipelines/{$id}", [
            'name'   => $this->unique('Pipe '),
            'stages' => [
                ['id' => $foreignStageId, 'name' => 'Hijack', 'code' => 'hij_'.uniqid()],
            ],
        ]);

        $this->assertSame(422, $response['status'], 'A stage id from another pipeline must be rejected: '.json_encode($response));

        // Atomicity: the pipeline name must be unchanged (no partial write).
        $show = $this->get("api/v1/settings/pipelines/{$id}");
        $this->assertSame($originalName, $show['json']['data']['name'] ?? null);
    }

    public function test_stages_of_the_wrong_type_are_rejected_not_500(): void
    {
        $response = $this->post('api/v1/settings/pipelines', [
            'name'   => $this->unique('Pipe '),
            'stages' => 'fruta',
        ]);

        $this->assertSame(422, $response['status'], 'Non-array stages must be a 422, not a 500: '.json_encode($response));
        $this->assertHumanMessage($response['json']);
    }

    public function test_stage_without_name_or_code_is_rejected(): void
    {
        $response = $this->post('api/v1/settings/pipelines', [
            'name'   => $this->unique('Pipe '),
            'stages' => [['sort_order' => 1]], // missing name + code
        ]);

        $this->assertSame(422, $response['status'], 'Incomplete stage must be a 422: '.json_encode($response));
        $this->assertHumanMessage($response['json']);
    }

    public function test_duplicate_stage_codes_are_rejected(): void
    {
        $response = $this->post('api/v1/settings/pipelines', [
            'name'   => $this->unique('Pipe '),
            'stages' => [
                ['name' => 'A', 'code' => 'dup'],
                ['name' => 'B', 'code' => 'dup'],
            ],
        ]);

        $this->assertSame(422, $response['status'], 'Duplicate stage codes must be a 422: '.json_encode($response));
        $this->assertHumanMessage($response['json']);
    }

    // -- Helpers ------------------------------------------------------------

    /**
     * Create a throwaway pipeline with two stages and register it for cleanup.
     *
     * @return array{id:int, json:array<mixed>}
     */
    private function createPipeline(): array
    {
        $response = $this->post('api/v1/settings/pipelines', [
            'name'   => $this->unique('Pipe '),
            'stages' => [
                ['name' => 'Stage A', 'code' => 'a_'.uniqid()],
                ['name' => 'Stage B', 'code' => 'b_'.uniqid()],
            ],
        ]);

        $this->assertContains($response['status'], [200, 201], 'Create pipeline should succeed: '.json_encode($response));

        $id = $response['json']['data']['id'] ?? null;
        $this->assertNotNull($id, 'Created pipeline has no id: '.json_encode($response));
        $this->deleteOnTearDown("api/v1/settings/pipelines/{$id}");

        return ['id' => (int) $id, 'json' => $response['json']];
    }

    private function foreignStageId(int $excludePipelineId): ?int
    {
        $response = $this->get('api/v1/settings/pipelines');

        foreach ($response['json']['data'] ?? [] as $pipeline) {
            if ((int) ($pipeline['id'] ?? 0) === $excludePipelineId) {
                continue;
            }

            $stageId = $pipeline['stages'][0]['id'] ?? null;

            if ($stageId) {
                return (int) $stageId;
            }
        }

        return null;
    }
}
