<?php

require_once 'Model.php';
class Cart extends Model
{
    function getUserCart( int $userId): array
    {

        $stmt = $this->pdo->query("SELECT * FROM user_products WHERE user_id = {$userId}");
        $userProducts = $stmt->fetchAll();

        $products = [];


        foreach ($userProducts as $userProduct) {
            $productId = $userProduct['product_id'];

            $stmt2 = $this->pdo->query("SELECT * FROM products WHERE id = $productId");
            $product = $stmt2->fetch();

            if ($product) {
                $product['amount'] = $userProduct['amount'];

                $products[] = $product;
            }

        }
        return $products;
    }

    public function deleteProduct( int $userId)
   {
       $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :userId");
       $stmt->execute(['userId' => $userId]);
   }
}
