<?php

namespace App\Http\Requests\Api\Payment;

use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Http\Requests\Api\BaseApiFormRequest;
use Illuminate\Validation\Rules\Enum;

class AllPaymentsRequest extends BaseApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable', new Enum(PaymentStatus::class)],
            'order_id' => ['nullable', 'exists:orders,id'],
            'payment_gateway' => ['nullable', new Enum(PaymentGateway::class)],
        ];
    }
}
