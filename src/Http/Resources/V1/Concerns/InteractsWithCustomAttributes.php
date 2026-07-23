<?php

namespace Webkul\RestApi\Http\Resources\V1\Concerns;

trait InteractsWithCustomAttributes
{
    /**
     * Collect the entity's user-defined (custom) EAV attribute values as a
     * `code => value` map, so REST resources can expose custom fields the same
     * way the Krayin panel does.
     *
     * Only attributes flagged `is_user_defined` are returned: system attributes
     * are already exposed as the resource's fixed keys, and skipping them means
     * a custom code can never collide with (or overwrite) an existing response
     * key. Values are the raw EAV values (e.g. `select`/`lookup` return the
     * option/record id), identical to what the core `attributesToArray()` emits.
     *
     * Returns an empty array for models that do not use Krayin's CustomAttribute
     * trait, keeping the calling resource safe for plain models and unit tests.
     *
     * @return array<string, mixed>
     */
    protected function customAttributes(): array
    {
        $model = $this->resource;

        if (! is_object($model) || ! method_exists($model, 'getCustomAttributes')) {
            return [];
        }

        $values = [];

        foreach ($model->getCustomAttributes() as $attribute) {
            if (! $attribute->is_user_defined) {
                continue;
            }

            $values[$attribute->code] = $model->getCustomAttributeValue($attribute);
        }

        return $values;
    }
}
