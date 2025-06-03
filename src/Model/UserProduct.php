<?php
namespace Model;

require_once "Model.php";
class UserProduct extends Model
{
    function getAllByUserId( int $userId): array
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
                $product['product_id'] = $userProduct['product_id'];

                $products[] = $product;
            }

        }
        return $products;
    }

    public function deleteByUserId( int $userId)
   {
       $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :userId");
       $stmt->execute(['userId' => $userId]);
   }

    protected array $items = [];

    public function getItems(int $userId): array
    {
        return $this->getAllByUserId($userId);
    }



    public function clear(): void
    {
        unset($_SESSION['cart']);
    }



}
