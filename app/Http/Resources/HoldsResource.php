<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class HoldsResource
 * @package App\Http\Resources
 */
class HoldsResource extends JsonResource
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
            'id'                       => $this->id,
            'slot_id'                 => $this->slot_id,
            'status'                   => $this->status,
            'created_at'               => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
