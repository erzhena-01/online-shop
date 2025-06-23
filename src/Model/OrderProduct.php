<?php
namespace Model;

require_once 'Model.php';

class OrderProduct extends Model
{
    private int $orderId;
    private int $productId;
    private int $amount;

    protected function getTableName(): string
    {
        return 'order_products';
    }


    public function create(int $orderId, int $productId, int $amount): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->getTableName()} (order_id, product_id, amount) 
            VALUES (:orderId, :productId, :amount)"
        );

        $stmt->execute([
            ':orderId' => $orderId,
            ':productId' => $productId,
            ':amount' => $amount
        ]);
    }


    public function getAllByOrderId(int $orderId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE order_id = :orderId");
        $stmt->execute([':orderId' => $orderId]);
        $orderProductsData = $stmt->fetchAll();

        $orderProducts = [];
        foreach ($orderProductsData as $op) {
            $obj = new self();
            $obj->orderId = (int)$op['order_id'];
            $obj->productId = (int)$op['product_id'];
            $obj->amount = (int)$op['amount'];
            $orderProducts[] = $obj;
        }
        return $orderProducts;
    }


    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
