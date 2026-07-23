<?php

namespace Webkul\RestApi\Http\Resources\V1\Contact;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\RestApi\Http\Resources\V1\Concerns\InteractsWithCustomAttributes;

class OrganizationResource extends JsonResource
{
    use InteractsWithCustomAttributes;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->customAttributes(), [
            'id'         => $this->id,
            'name'       => $this->name,
            'address'    => $this->address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
    }
}
