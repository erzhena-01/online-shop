<?php

namespace Model;
class Order extends Model
{

    public function create(
        string $customerName,
        string $contactPhone,
        string $address,
        int $userId
    )
    {
       $stmt = $this->pdo->prepare(
           "INSERT INTO orders (customer_name, contact_phone, address, user_id)
                  VALUES (:name, :contact_phone, :address, :user_id) RETURNING id"
       );

       $stmt->execute([
           'name' => $customerName,
           'contact_phone' => $contactPhone,
           'address' => $address,
           'user_id' => $userId
       ]);

       $data = $stmt->fetch();
        return (int)$data['id'];
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

}