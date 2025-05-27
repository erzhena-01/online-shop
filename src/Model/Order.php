<?php

namespace Model;

use PDO;

class Order extends Model
{
    public function createOrder(string $customerName, string $address, array $items, int $userId): int
    {

        $stmt = $this->pdo->prepare("INSERT INTO orders (customer_name, address, user_id) VALUES (:name, :address, :user_id)");
        $stmt->execute(['name' => $customerName, 'address' => $address, 'user_id' => $userId]);

        $stmt = $this->pdo->prepare("SELECT id FROM orders WHERE user_id = :user_id ORDER BY id DESC LIMIT 1");
        $stmt->execute(['user_id' => $userId]);
        $order = $stmt->fetch();

        if (!$order) {
            echo "Не удалось получить ID заказа";
        }

        $orderId = $order['id'];

        foreach ($items as $item) {
            $stmt = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :qty)");
            $stmt->execute(['order_id' => $orderId, 'product_id' => $item['id'], 'qty' => $item['amount']]);
        }

        return $orderId;
    }

    public function getLastOrderByUserId(int $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC LIMIT 1");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }

    public function getOrderById(int $orderId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute(['id' => $orderId]);
        return $stmt->fetch();
    }
}
