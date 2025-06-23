<?php


namespace Service;

use Model\Order;
use Model\OrderProduct;
use Model\UserProduct;

class OrderService
{
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private UserProduct $userProductModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProduct();
    }

    public function createOrder(int $userId, string $name, string $phone, string $address): ?int
    {
        $cartItems = $this->userProductModel->getAllByUserId($userId);

        if (empty($cartItems)) {
            return null; // корзина пуста
        }

        $orderId = $this->orderModel->create($name, $phone, $address, $userId);

        foreach ($cartItems as $item) {
            $this->orderProductModel->create(
                $orderId,
                $item->getProductId(),
                $item->getAmount()
            );
        }

        $this->userProductModel->deleteByUserId($userId);

        return $orderId;
    }
}
