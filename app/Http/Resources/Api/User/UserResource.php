<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => (int)$this->id,
            "name" => (string)$this->name,
            "email" => (string)$this->email,
            "token" => $this->when($this->jwt_token, (string)$this->jwt_token),
        ];
    }
}
