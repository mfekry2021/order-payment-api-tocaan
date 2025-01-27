<?php

namespace App\Http\Requests\Api\Order;

use App\Enums\OrderStatus;
use App\Http\Requests\Api\BaseApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateUpdateOrderRequest extends BaseApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'items.*.price' => ['required', 'numeric', 'min:0', 'max:9999999']
        ];
        if ($this->isMethod('put')) {
            $rules['order_id'] = ['required', 'integer', 'exists:orders,id'];
            $rules['items'][0] = 'nullable';
            $rules['status'] = ['nullable', 'integer', new Enum(OrderStatus::class)];
        }
        return $rules;
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->isMethod('put')) {
            $this->merge(['order_id' => $this->route('id')]);
        }
    }
}
