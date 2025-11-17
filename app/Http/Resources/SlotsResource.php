<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SlotsResource
 * @package App\Http\Resources
 */
class SlotsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                         => $this->id,
            'capacity'                   => $this->capacity,
            'remaining'                  => $this->remaining,
        ];
    }
}
