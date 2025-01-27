<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class OrderService
{
    /**
     * @param array $data
     * @param int $userId
     * @return mixed
     */
    public function store(array $data, int $userId)
    {
        $data['user_id'] = $userId;
        $data['total'] = $this->recalculate($data['items']);
        $data['status'] = OrderStatus::PENDING->value;
        return Order::create($data);
    }

    /**
     * @param int $orderId
     * @param array $data
     * @return Order|null
     */
    public function update(int $orderId, array $data)
    {
        $order = $this->findById($orderId);
        if (!empty($data['items'])) {
            $data['total'] = $this->recalculate($data['items']);
        }
        $order->update($data);
        $order->refresh();
        return $order;
    }

    /**
     * @param int $orderId
     * @return void
     */
    public function destroy(int $orderId)
    {
        $order = $this->findById($orderId);
        $order->delete();
    }

    /**
     * @param array $items
     * @return float|int
     */
    private function recalculate(array $items): float|int
    {
        return array_sum(array_map(fn($item) => $item['quantity'] * $item['price'], $items));
    }

    /**
     * @param int $id
     * @return Order|null
     */
    public function findById(int $id): ?Order
    {
        return Order::with('payments')->find($id);
    }

    /**
     * @param array $data
     * @return Builder
     */
    public function filterQuery(array $data = []): Builder
    {
        return Order::query()
            ->when(!empty($data['status']), fn($q) => $q->whereStatus($data['status']));
    }

    /**
     * @param int $orderId
     * @return bool
     */
    public function canBeDeleted(int $orderId): bool
    {
        $order = $this->findById($orderId);
        $order->load('payments');
        return !count($order->paymets ?? []);
    }
}
