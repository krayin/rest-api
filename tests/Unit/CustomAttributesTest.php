<?php

namespace Webkul\RestApi\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\RestApi\Http\Resources\V1\Concerns\InteractsWithCustomAttributes;
use Webkul\RestApi\Http\Resources\V1\Contact\OrganizationResource;
use Webkul\RestApi\Tests\TestCase;

/**
 * Unit coverage for the custom-field (EAV) exposure added to the Lead /
 * Organization / Person resources. The trait leans on Krayin's CustomAttribute
 * API (`getCustomAttributes()` / `getCustomAttributeValue()` / `is_user_defined`),
 * which we stub with a fake model so the behaviour is provable without a CRM.
 */
class CustomAttributesTest extends TestCase
{
    public function test_plain_model_without_eav_support_yields_no_custom_attributes(): void
    {
        $resource = new CustomAttributesProbe((object) ['id' => 1, 'title' => 'X']);

        $this->assertSame([], $resource->exposeCustomAttributes());
    }

    public function test_only_user_defined_attribute_values_are_collected(): void
    {
        $model = new FakeEavModel(
            [
                (object) ['code' => 'title', 'is_user_defined' => 0],       // system -> skipped
                (object) ['code' => 'priority', 'is_user_defined' => 1],
                (object) ['code' => 'contract_no', 'is_user_defined' => 1],
                (object) ['code' => 'empty_custom', 'is_user_defined' => 1], // no value -> null
            ],
            [
                'title'       => 'System Title',
                'priority'    => 5,
                'contract_no' => 'ABC-123',
            ],
        );

        $this->assertSame([
            'priority'     => 5,
            'contract_no'  => 'ABC-123',
            'empty_custom' => null,
        ], (new CustomAttributesProbe($model))->exposeCustomAttributes());
    }

    public function test_custom_keys_never_overwrite_the_resource_whitelist(): void
    {
        // A user-defined attribute whose code collides with a fixed key must not
        // clobber it: the resource merges the whitelist last.
        $model = new FakeEavModel(
            [(object) ['code' => 'name', 'is_user_defined' => 1]],
            ['name' => 'custom-collides'],
        );
        $model->id = 9;
        $model->name = 'Real Name';
        $model->address = null;
        $model->created_at = null;
        $model->updated_at = null;

        $array = (new OrganizationResource($model))->toArray(Request::create('/'));

        $this->assertSame('Real Name', $array['name']);
    }
}

/**
 * A JsonResource that uses the trait and exposes its protected collector so the
 * behaviour can be asserted directly.
 */
class CustomAttributesProbe extends JsonResource
{
    use InteractsWithCustomAttributes;

    /**
     * @return array<string, mixed>
     */
    public function exposeCustomAttributes(): array
    {
        return $this->customAttributes();
    }
}

/**
 * Minimal stand-in for a Krayin model that uses the CustomAttribute trait.
 */
#[\AllowDynamicProperties]
class FakeEavModel
{
    /**
     * @param  array<int, object>  $attributes
     * @param  array<string, mixed>  $values
     */
    public function __construct(private array $attributes = [], private array $values = []) {}

    /**
     * @return array<int, object>
     */
    public function getCustomAttributes(): array
    {
        return $this->attributes;
    }

    public function getCustomAttributeValue($attribute)
    {
        return $this->values[$attribute->code] ?? null;
    }
}
