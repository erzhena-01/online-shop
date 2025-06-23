<?php
namespace Model;

require_once 'Model.php';

class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private float $price;
    private string $image_url;

    // Свойства для user_products
    private int $user_id = 0;
    private int $amount = 0;

    protected function getTableName(): string
    {
        return 'products';
    }


    public function __construct(array $data = [])
    {
        parent::__construct();

        if (!empty($data)) {
            $this->setId((int)$data['id']);
            $this->setName($data['name']);
            $this->setDescription($data['description']);
            $this->setPrice((float)$data['price']);
            $this->setImageUrl($data['image_url']);

            $this->setUserId($data['user_id'] ?? 0);
            $this->setAmount($data['amount'] ?? 0);
        }
    }

    // Сеттеры
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setImageUrl(string $image_url): void
    {
        $this->image_url = $image_url;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    // Геттеры
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
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getImageUrl(): string
    {
        return $this->image_url;
    }
    public function getUserId(): int
    {
        return $this->user_id;
    }
    public function getAmount(): int
    {
        return $this->amount;
    }

    // Остальные методы остаются без изменений
    public function getProductsList(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->getTableName()}");
        $items = $stmt->fetchAll();

        $products = [];

        foreach ($items as $item) {
            $products[] = new self($item);
        }

        return $products;
    }

    public function getProductById(int $productId): ?self
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :productId");
        $stmt->execute([':productId' => $productId]);

        $data = $stmt->fetch();

        return $data ? new self($data) : null;
    }

    public function getProductByIds(int $productId, int $userId): ?self
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute([':productId' => $productId, ':userId' => $userId]);

        $product = $stmt->fetch();

        return $product ? new self($product) : null;
    }

    public function addUserProduct(int $productId, int $amount): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :productId, :amount)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'productId' => $productId,
            'amount' => $amount
        ]);
    }

    public function updateUserProductAmount(int $userId, int $productId, int $amount): void
    {
        $stmt = $this->pdo->prepare("UPDATE user_products SET amount = :amount WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute(['amount' => $amount, 'userId' => $userId, 'productId' => $productId]);
    }
}
