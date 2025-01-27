<?php

namespace App\Http\Requests\Api\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Http\Requests\Api\BaseApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PayOrderRequest extends BaseApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'payment_gateway' => ['required', new Enum(PaymentGateway::class)],
        ];
    }
}
