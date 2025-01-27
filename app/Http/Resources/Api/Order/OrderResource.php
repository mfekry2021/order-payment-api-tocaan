<?php

namespace App\Http\Resources\Api\Order;

use App\Enums\OrderStatus;
use App\Http\Resources\Api\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = OrderStatus::tryFrom($this->status);
        return [
            "id" => (int)$this->id,
            "status_id" => (int)$status->value,
            "status_label" => (string)$status->label(),
            "total" => (float)(round($this->total ?? 0, 2)),
            "items" => $this->items ? OrderItemResource::collection($this->items) : [],
            "user_data" => $this->user ? new UserResource($this->user->makeHidden('token')) : null
        ];
    }
}
