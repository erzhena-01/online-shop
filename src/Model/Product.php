<?php

require_once 'Model.php';
class Product extends Model
{
public function getProductsList()
   {

    $stmt = $this->pdo->query('SELECT * FROM products');
    $products = $stmt->fetchAll();

    return $products;
   }

    public function getProductByIds(int $productId, int $userId)
    {

        $stmt = $this->pdo->prepare('SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute([':productId' => $productId, ':userId' => $userId]);

        return $stmt->fetch();
    }


    public function addUserProduct(int $productId, int $amount)
    {

        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :productId, :amount)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'productId' => $productId,
            'amount' => $amount
        ]);
    }


    public function updateUserProductAmount(int $userId, int $productId, int $amount)
   {

       $stmt = $this->pdo->prepare("UPDATE user_products SET amount = :amount WHERE user_id = :userId AND product_id = :productId");
       $stmt->execute(['amount' => $amount, 'userId' => $userId, 'productId' => $productId]);
   }

   public function getProductById(int $productId)
   {
       $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :productId");

       $stmt->execute([':productId' => $productId]);
       $data = $stmt->fetch();
       return $data;
   }
}
