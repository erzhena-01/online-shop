<?php
namespace Model;

require_once "Model.php";
class UserProduct extends Model
{

    private int $productId;
    private int $user_id;
    private int $amount;
    private int $id;
    private string $name;
    private string $description;
    private int $price;

    public function getAllByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
        $userProducts = $stmt->fetchAll();

        $products = [];

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct['product_id'];

            $stmt2 = $this->pdo->prepare("SELECT * FROM products WHERE id = :productId");
            $stmt2->execute(['productId' => $productId]);
            $product = $stmt2->fetch();

            if ($product) {
                $obj = new self();
                $obj->productId = $userProduct['product_id'];
                $obj->user_id = $userProduct['user_id'];
                $obj->amount = $userProduct['amount'];

                $obj->id = $product['id'];
                $obj->name = $product['name'];
                $obj->description = $product['description'];
                $obj->price = $product['price'];

                $products[] = $obj;
            }
        }

        return $products;
    }


    public function deleteByUserId(int $userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
    }

    protected array $items = [];

    public function getItems(int $userId): array
    {
        return $this->getAllByUserId($userId);
    }

    public function getByUserIdAndProductId(int $userId, int $productId): ?self
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute(['userId' => $userId, 'productId' => $productId]);
        $userProducts = $stmt->fetch();
        if ($userProducts) {
            $obj = new self();
            $obj->productId = $userProducts['product_id'];
            $obj->user_id = $userProducts['user_id'];
            $obj->amount = $userProducts['amount'];


            $stmt2 = $this->pdo->prepare("SELECT * FROM products WHERE id = :productId");
            $stmt2->execute(['productId' => $productId]);
            $product = $stmt2->fetch();
            if ($product) {
                $obj->productId = $product['id'];
                $obj->name = $product['name'];
                $obj->description = $product['description'];
                $obj->price = $product['price'];
            }
            return $obj;
        }
        return null;
    }

    public function increaseAmount(int $userId, int $productId)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE user_products 
                    SET amount = amount + 1 
                    WHERE user_id = :userId AND product_id = :productId"
        );
        $stmt->execute([
            'userId' => $userId,
            'productId' => $productId]);
    }
    public function decreaseAmount(int $userId, int $productId)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE user_products 
                    SET amount = amount - 1 
                    WHERE user_id = :userId AND product_id = :productId"
        );
        $stmt->execute([
            'userId' => $userId,
            'productId' => $productId]);

    }
    public function deleteByUserIdAndProductId(int $userId, int $productId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_products 
                            WHERE user_id = :userId AND product_id = :productId"
        );
        $stmt->execute([
            'userId' => $userId,
            'productId' => $productId]);

    }

    public function addToCart(int $userId, int $productId, int $amount)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO user_products (user_id, product_id, amount) 
                    VALUES (:userId, :productId, :amount)"
        );
        $stmt->execute([
            'userId' => $userId,
            'productId' => $productId,
            'amount' => $amount]);
    }


    public function clear(): void
    {
        unset($_SESSION['cart']);
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }



}
