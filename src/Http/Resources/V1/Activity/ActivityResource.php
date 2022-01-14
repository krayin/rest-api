<?php

namespace Webkul\RestApi\Http\Resources\V1\Activity;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'start'      => $this->start,
            'end'        => $this->end,
            'user_name'  => $this->user_name,
            'created_at' => $this->created_at,
        ];
    }
}
