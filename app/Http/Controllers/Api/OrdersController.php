<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Requests\Api\BaseApiFormRequest;
use App\Http\Requests\Api\Order\CreateUpdateOrderRequest;
use App\Http\Requests\Api\Order\DeleteOrderRequest;
use App\Http\Requests\Api\Order\OrderFilterRequest;
use App\Http\Resources\Api\Order\OrderResource;
use App\Http\Resources\Api\PaginationResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrdersController extends BaseApiController
{
    public function __construct(protected OrderService $orderService)
    {
    }

    /**
     * @param OrderFilterRequest $request
     * @return JsonResponse
     */
    public function index(OrderFilterRequest $request): JsonResponse
    {
        $orders = $this->orderService->filterQuery($request->all())->paginate($request->per_page ?? 10);
        return $this->successResponse([
            "pagination" => new PaginationResource($orders),
            "orders" => OrderResource::collection($orders),
        ]);
    }

    /**
     * @param CreateUpdateOrderRequest $request
     * @return JsonResponse
     */
    public function store(CreateUpdateOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->store($request->validated(), auth()->id());
        return $this->successResponse(new OrderResource($order), 'created successfully.');

    }

    /**
     * @param CreateUpdateOrderRequest $request
     * @return JsonResponse
     */
    public function update(CreateUpdateOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->update($request->order_id, $request->validated());
        return $this->successResponse(new OrderResource($order),'updated successfully');
    }

    /**
     * @param DeleteOrderRequest $request
     * @return JsonResponse
     */
    public function destroy(DeleteOrderRequest $request): JsonResponse
    {
        if ($this->orderService->canBeDeleted($request->order_id)) {
            return $this->errorResponse('Cant delete order with payments');
        }
        $this->orderService->destroy($request->order_id);
        return $this->successResponse(message: 'deleted successfully');
    }
}
