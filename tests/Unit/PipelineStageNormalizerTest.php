<?php

namespace Webkul\RestApi\Tests\Unit;

use Webkul\RestApi\Support\PipelineStageNormalizer;
use Webkul\RestApi\Tests\TestCase;

/**
 * The pipeline `stages` normalizer is pure array logic (no DB, no facades), so
 * it is exercised directly here — the CRM-backed create/update behaviour it
 * feeds is covered by PipelinesIntegrationTest. It turns the REST client's plain
 * list of stages into the associative shape the core PipelineRepository expects:
 * an existing stage (carrying an `id`) is keyed by that id (update), a new stage
 * is keyed `stage_<n>` (create), and `id` is stripped from the value.
 */
class PipelineStageNormalizerTest extends TestCase
{
    /**
     * @param  array<int, array<string, mixed>>  $stages
     * @return array<int|string, array<string, mixed>>
     */
    private function normalize(array $stages): array
    {
        return (new PipelineStageNormalizer)->normalize($stages);
    }

    public function test_new_stages_are_keyed_for_creation_with_defaults(): void
    {
        $result = $this->normalize([
            ['name' => 'New', 'code' => 'new'],
            ['name' => 'Won', 'code' => 'won', 'probability' => 90, 'sort_order' => 5],
        ]);

        $this->assertSame(['stage_0', 'stage_1'], array_keys($result));

        // Missing probability/sort_order are defaulted (100 / index).
        $this->assertSame(
            ['name' => 'New', 'code' => 'new', 'probability' => 100, 'sort_order' => 0],
            $result['stage_0']
        );

        // Provided probability/sort_order are preserved.
        $this->assertSame(
            ['name' => 'Won', 'code' => 'won', 'probability' => 90, 'sort_order' => 5],
            $result['stage_1']
        );
    }

    public function test_existing_stages_are_keyed_by_id_and_id_is_stripped(): void
    {
        $result = $this->normalize([
            ['id' => 7, 'name' => 'Renamed', 'code' => 'renamed'],
            ['name' => 'Fresh', 'code' => 'fresh'],
        ]);

        $this->assertSame([7, 'stage_1'], array_keys($result));
        $this->assertArrayNotHasKey('id', $result[7]);
        $this->assertSame('Renamed', $result[7]['name']);
        $this->assertSame('Fresh', $result['stage_1']['name']);
    }

    public function test_empty_list_yields_empty_map(): void
    {
        $this->assertSame([], $this->normalize([]));
    }
}
