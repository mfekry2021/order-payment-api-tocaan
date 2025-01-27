<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Http\Requests\Api\Order\CreateUpdateOrderRequest;
use App\Http\Requests\Api\Order\DeleteOrderRequest;
use App\Http\Requests\Api\Order\OrderFilterRequest;
use App\Http\Requests\Api\Payment\AllPaymentsRequest;
use App\Http\Requests\Api\Payment\PayOrderRequest;
use App\Http\Resources\Api\Order\OrderResource;
use App\Http\Resources\Api\PaginationResource;
use App\Http\Resources\Api\Payment\PaymentResource;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentsController extends BaseApiController
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    /**
     * @param PayOrderRequest $request
     * @param OrderService $orderService
     * @return JsonResponse
     */
    public function checkout(
        PayOrderRequest $request,
        OrderService    $orderService
    ): JsonResponse
    {
        $order = $orderService->findById($request->order_id);

        if ($order->status != OrderStatus::CONFIRMED->value) {
            return $this->errorResponse('Order Cant be Paid In Current Status');
        }
        //check if order is paid
        if ($order->isPaid()) {
            return $this->errorResponse('Order Already Paid');
        }
        $checkoutUrl = $this->paymentService->pay($request->order_id, $request->payment_gateway);

        //save payment result (for testing)
        $this->paymentService->savePaymentTransaction($order, $request->payment_gateway);

        return $this->successResponse(["checkout_url" => $checkoutUrl]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function callback(Request $request): JsonResponse
    {
        //myfatora callback endpoint (testing purposes)
        $this->paymentService->callback(PaymentGateway::MY_FATORA->value, $request->all());
        return $this->successResponse();
    }

    /**
     * @param AllPaymentsRequest $request
     * @return JsonResponse
     */
    public function all(AllPaymentsRequest $request): JsonResponse
    {
        $payments = $this->paymentService->filterQuery($request->validated())->paginate($request->per_page ?? 10);
        return $this->successResponse([
            "pagination" => new PaginationResource($payments),
            "orders" => PaymentResource::collection($payments),
        ]);
    }
}
