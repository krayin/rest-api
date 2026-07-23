<?php

namespace Webkul\RestApi\Tests\Unit;

use Illuminate\Support\Facades\Validator;
use Webkul\RestApi\Http\Request\MassDestroyRequest;
use Webkul\RestApi\Tests\TestCase;

class MassDestroyRequestTest extends TestCase
{
    private function validate(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, (new MassDestroyRequest)->rules());
    }

    public function test_it_authorizes_every_request(): void
    {
        $this->assertTrue((new MassDestroyRequest)->authorize());
    }

    public function test_valid_payload_passes(): void
    {
        $this->assertTrue($this->validate(['indices' => [1, 2, 3]])->passes());
    }

    public function test_missing_indices_fails(): void
    {
        $validator = $this->validate([]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('indices', $validator->errors()->toArray());
    }

    public function test_non_array_indices_fails(): void
    {
        $this->assertTrue($this->validate(['indices' => 7])->fails());
    }

    public function test_non_integer_index_entries_fail(): void
    {
        $validator = $this->validate(['indices' => ['x']]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('indices.0', $validator->errors()->toArray());
    }

    public function test_value_is_not_required(): void
    {
        // Unlike MassUpdateRequest, destroy has no `value` rule.
        $this->assertArrayNotHasKey('value', (new MassDestroyRequest)->rules());
    }
}
