<?php

namespace App\Http\Resources\Api\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Http\Resources\Api\Order\OrderItemResource;
use App\Http\Resources\Api\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = PaymentStatus::tryFrom($this->status);
        $paymentGateway = PaymentGateway::tryFrom($this->payment_gateway);
        return [
            "id" => (int)$this->id,
            "order_id" => (int)$this->order_id,
            "status_id" => (int)$status->value,
            "status_label" => (string)$status->label(),
            "payment_gateway_id" => (int)$paymentGateway->value,
            "payment_gateway_label" => (string)$paymentGateway->label(),
            "total" => (float)(round($this->total ?? 0, 2)),
            "transaction_id" => (string)$this->transaction_id ?? '',
        ];
    }
}
