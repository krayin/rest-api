<?php

namespace Webkul\RestApi\Http\Resources\V1\Contact;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\RestApi\Http\Resources\V1\Concerns\InteractsWithCustomAttributes;
use Webkul\RestApi\Http\Resources\V1\Setting\UserResource;

class PersonResource extends JsonResource
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
            'id'              => $this->id,
            'name'            => $this->name,
            'emails'          => $this->emails,
            'contact_numbers' => $this->contact_numbers,
            'organization'    => $this->when($this->organization, new OrganizationResource($this->organization)),
            'job_title'       => $this->job_title,
            'sales_owner'     => $this->when($this->user, new UserResource($this->user)),
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ]);
    }
}
