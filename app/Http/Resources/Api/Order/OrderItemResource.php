<?php

namespace App\Http\Resources\Api\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "product_name" => (string)$this['product_name'] ?? '',
            "price" => (float)(round($this['price'] ?? 0, 2)),
            "quantity" => (int)($this['quantity'] ?? 0),
        ];
    }
}
