<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "user_id" => $this->user_id,
            "user" => $this->user->name,
            "name" => $this->name,
            "remain_stock" => $this->remain_stock,
            "purpose" => $this->purpose,
            "expected_delivery" => $this->expected_delivery,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
