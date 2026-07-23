<?php

namespace Webkul\RestApi\Tests\Unit;

use Illuminate\Support\Facades\Validator;
use Webkul\RestApi\Http\Request\MassUpdateRequest;
use Webkul\RestApi\Tests\TestCase;

class MassUpdateRequestTest extends TestCase
{
    private function validate(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, (new MassUpdateRequest)->rules());
    }

    public function test_it_authorizes_every_request(): void
    {
        $this->assertTrue((new MassUpdateRequest)->authorize());
    }

    public function test_valid_payload_passes(): void
    {
        $this->assertTrue($this->validate([
            'indices' => [1, 2, 3],
            'value'   => 5,
        ])->passes());
    }

    public function test_missing_indices_fails(): void
    {
        $validator = $this->validate(['value' => 5]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('indices', $validator->errors()->toArray());
    }

    public function test_non_array_indices_fails(): void
    {
        $this->assertTrue($this->validate(['indices' => 'nope', 'value' => 5])->fails());
    }

    public function test_non_integer_index_entries_fail(): void
    {
        $validator = $this->validate([
            'indices' => [1, 'abc'],
            'value'   => 5,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('indices.1', $validator->errors()->toArray());
    }

    public function test_missing_value_fails(): void
    {
        $validator = $this->validate(['indices' => [1]]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('value', $validator->errors()->toArray());
    }
}
