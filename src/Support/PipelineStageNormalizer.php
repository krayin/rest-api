<?php

namespace Webkul\RestApi\Support;

/**
 * Turns a REST client's plain list of pipeline stages into the associative shape
 * the core Krayin PipelineRepository expects.
 *
 * Each item is `{id?, name, code, probability?, sort_order?}`. A stage carrying
 * an `id` is keyed by that id (the core updates it); a stage without one is keyed
 * `stage_<index>` (the core creates it). `id` is dropped from the value, and
 * `probability`/`sort_order` are defaulted so the core never inserts nulls and
 * ordering stays deterministic. Pure (no request/DB access).
 */
class PipelineStageNormalizer
{
    /**
     * @param  array<int, array<string, mixed>>  $stages
     * @return array<int|string, array<string, mixed>>
     */
    public function normalize(array $stages): array
    {
        $normalized = [];

        foreach (array_values($stages) as $index => $stage) {
            $value = [
                'name'        => $stage['name'] ?? null,
                'code'        => $stage['code'] ?? null,
                'probability' => $stage['probability'] ?? 100,
                'sort_order'  => $stage['sort_order'] ?? $index,
            ];

            if (! empty($stage['id'])) {
                $normalized[(int) $stage['id']] = $value;
            } else {
                $normalized['stage_'.$index] = $value;
            }
        }

        return $normalized;
    }
}
