<?php

namespace Webkul\RestApi\Tests\Unit;

use Illuminate\Http\Request;
use Webkul\RestApi\Http\Resources\V1\Lead\LeadResource;
use Webkul\RestApi\Http\Resources\V1\Setting\RoleResource;
use Webkul\RestApi\Http\Resources\V1\Setting\SourceResource;
use Webkul\RestApi\Http\Resources\V1\Setting\TypeResource;
use Webkul\RestApi\Tests\TestCase;

/**
 * API Resources are pure presentation classes (they extend Laravel's
 * JsonResource and never touch the Krayin domain), so they can be transformed
 * over lightweight fake models. We cover the flat resources exactly and use
 * LeadResource — which composes UserResource / PersonResource / RoleResource —
 * as the template proving nested transformation works.
 */
class ResourceTransformerTest extends TestCase
{
    private function request(): Request
    {
        return Request::create('/');
    }

    public function test_flat_source_resource_maps_its_fields(): void
    {
        $model = (object) [
            'id'         => 7,
            'name'       => 'Web',
            'created_at' => '2026-01-01',
            'updated_at' => '2026-01-02',
        ];

        $this->assertSame([
            'id'         => 7,
            'name'       => 'Web',
            'created_at' => '2026-01-01',
            'updated_at' => '2026-01-02',
        ], (new SourceResource($model))->toArray($this->request()));
    }

    public function test_flat_type_resource_maps_its_fields(): void
    {
        $model = (object) ['id' => 3, 'name' => 'Person', 'created_at' => null, 'updated_at' => null];

        $this->assertSame(
            ['id' => 3, 'name' => 'Person', 'created_at' => null, 'updated_at' => null],
            (new TypeResource($model))->toArray($this->request())
        );
    }

    public function test_role_resource_maps_including_array_permissions(): void
    {
        $model = (object) [
            'id'              => 1,
            'name'            => 'Administrator',
            'description'     => 'Full access',
            'permission_type' => 'all',
            'permissions'     => ['lead.view', 'lead.create'],
            'created_at'      => null,
            'updated_at'      => null,
        ];

        $array = (new RoleResource($model))->toArray($this->request());

        $this->assertSame('Administrator', $array['name']);
        $this->assertSame('all', $array['permission_type']);
        $this->assertSame(['lead.view', 'lead.create'], $array['permissions']);
    }

    public function test_nested_lead_resource_resolves_its_object_graph(): void
    {
        $role = (object) [
            'id'              => 1,
            'name'            => 'Admin',
            'description'     => null,
            'permission_type' => 'all',
            'permissions'     => [],
            'created_at'      => null,
            'updated_at'      => null,
        ];

        $user = (object) [
            'id'              => 10,
            'name'            => 'Jane',
            'email'           => 'jane@example.com',
            'status'          => 1,
            'view_permission' => 'global',
            'role'            => $role,
            'created_at'      => null,
            'updated_at'      => null,
            'image'           => null,
            'image_url'       => null,
        ];

        $person = (object) [
            'id'              => 20,
            'name'            => 'John Client',
            'emails'          => [['value' => 'john@example.com', 'label' => 'work']],
            'contact_numbers' => [],
            'organization'    => null, // omitted via when()
            'job_title'       => 'CEO',
            'user'            => null,  // omitted via when()
            'created_at'      => null,
            'updated_at'      => null,
        ];

        $lead = (object) [
            'id'                     => 100,
            'title'                  => 'Big deal',
            'description'            => 'A promising lead',
            'lead_value'             => 5000,
            'status'                 => 1,
            'lost_reason'            => null,
            'closed_at'              => null,
            'user'                   => $user,
            'person'                 => $person,
            'products'               => [], // empty -> LeadProductResource collection is []
            'lead_source_id'         => 1,
            'lead_type_id'           => 2,
            'lead_pipeline_id'       => 3,
            'lead_pipeline_stage_id' => 4,
            'created_at'             => null,
            'updated_at'             => null,
            'expected_close_date'    => null,
        ];

        // Fully serialize (recursively resolving the nested resources) via the
        // JSON response the API would actually return.
        $data = (new LeadResource($lead))->response($this->request())->getData(true)['data'];

        $this->assertSame(100, $data['id']);
        $this->assertSame('Big deal', $data['title']);

        // Nested UserResource -> RoleResource resolved to arrays.
        $this->assertSame(10, $data['user']['id']);
        $this->assertSame('Admin', $data['user']['role']['name']);

        // Nested PersonResource resolved; its conditional relations were omitted.
        $this->assertSame(20, $data['person']['id']);
        $this->assertArrayNotHasKey('organization', $data['person']);
        $this->assertArrayNotHasKey('sales_owner', $data['person']);

        // Empty product collection resolves to an empty list, not null.
        $this->assertSame([], $data['lead_products']);
    }
}
