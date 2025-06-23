<?php

namespace Model;

class Review extends Model
{

    public int $id;
    public int $productId;
    public int $userId;
    public int $rating;
    public string $comment;
    public string $createdAt;
    public string $author;

    protected function getTableName(): string
    {
        return 'reviews';
    }
    public function addReview(int $productId, int $userId, int $rating, string $comment): self
    {
        $stmt = $this->pdo->prepare("
        INSERT INTO {$this->getTableName()} (product_id, user_id, rating, comment) 
        VALUES (:productId, :userId, :rating, :comment)"
        );
        $stmt->execute([
            ':productId' => $productId,
            ':userId'    => $userId,
            ':rating'    => $rating,
            ':comment'   => $comment,
        ]);

        $reviewId = $this->pdo->lastInsertId();

        $stmt = $this->pdo->prepare("SELECT created_at, user_id FROM {$this->getTableName()} WHERE id = :id");
        $stmt->execute([':id' => $reviewId]);
        $reviewRow = $stmt->fetch();

        $stmtUser = $this->pdo->prepare('SELECT name FROM users WHERE id = :userId');
        $stmtUser->execute([':userId' => $userId]);
        $userRow = $stmtUser->fetch();

        $review = new self();
        $review->id = (int)$reviewId;
        $review->productId = $productId;
        $review->userId = $userId;
        $review->rating = $rating;
        $review->comment = $comment;
        $review->createdAt = $reviewRow['created_at'];
        $review->author = $userRow ? $userRow['name'] : 'Неизвестный';

        return $review;
    }


    public function getByProductId(int $productId): array
    {
        $stmt = $this->pdo->prepare("
        SELECT * FROM {$this->getTableName()} 
        WHERE product_id = :productId
        ORDER BY created_at DESC
    ");
        $stmt->execute(['productId' => $productId]);
        $reviewsData = $stmt->fetchAll();

        $reviews = [];

        foreach ($reviewsData as $r) {
            // Получаем имя автора
            $stmtUser = $this->pdo->prepare("SELECT name FROM users WHERE id = :userId");
            $stmtUser->execute(['userId' => $r['user_id']]);
            $user = $stmtUser->fetch();

            // Создаём объект отзыва
            $review = new self();

            $review->id = (int)$r['id'];
            $review->productId = (int)$r['product_id'];
            $review->userId = (int)$r['user_id'];
            $review->rating = (int)$r['rating'];
            $review->comment = $r['comment'];
            $review->createdAt = $r['created_at'];
            $review->author = $user ? $user['name'] : 'Неизвестный';

            $reviews[] = $review;
        }

        return $reviews;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }


}