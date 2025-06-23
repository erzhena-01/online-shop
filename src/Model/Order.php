<?php
namespace Model;

require_once 'Model.php';

class Order extends Model
{
    private int $id;
    private string $customerName;
    private string $contactPhone;
    private string $address;
    private int $userId;
    private string $createdAt;

    protected function getTableName(): string
    {
        return 'orders';
    }


    public function create(
        string $customerName,
        string $contactPhone,
        string $address,
        int $userId
    ): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->getTableName()} (customer_name, contact_phone, address, user_id)
            VALUES (:name, :contact_phone, :address, :user_id) RETURNING id"
        );

        $stmt->execute([
            ':name' => $customerName,
            ':contact_phone' => $contactPhone,
            ':address' => $address,
            ':user_id' => $userId
        ]);

        $data = $stmt->fetch();
        return (int)$data['id'];
    }


    public function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute([':user_id' => $userId]);
        $ordersData = $stmt->fetchAll();

        $orders = [];
        foreach ($ordersData as $order) {
            $obj = new self();
            $obj->id = (int)$order['id'];
            $obj->customerName = $order['customer_name'];
            $obj->contactPhone = $order['contact_phone'];
            $obj->address = $order['address'];
            $obj->userId = (int)$order['user_id'];
            $obj->createdAt = $order['created_at'];
            $orders[] = $obj;
        }

        return $orders;
    }



    public function getId(): int
    {
        return $this->id;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getContactPhone(): string
    {
        return $this->contactPhone;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
