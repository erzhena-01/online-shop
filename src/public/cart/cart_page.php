<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userId = $_SESSION['user_id'];

function getCart(PDO $pdo, int $userId): array {
    $stmt = $pdo->prepare(" SELECT product_id, amount  FROM user_products WHERE user_id = :userId");

    $stmt->execute(['userId' => $userId]);
    return $stmt->fetchAll();
}
$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');

$cart = getCart($pdo, $_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f8f8f8;
        }

        table {
            width: 60%;
            border-collapse: collapse;
            margin: auto;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .empty {
            text-align: center;
            margin-top: 50px;
            font-size: 20px;
        }

        .clear-btn {
            display: block;
            margin: 20px auto;
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }

        .clear-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

<h1 style="text-align:center;">Ваша корзина</h1>

<?php if (empty($cart)): ?>
    <div class="empty">Корзина пуста.</div>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Product ID</th>
            <th>Количество</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($cart as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_id']) ?></td>
                <td><?= (int)$item['amount'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <form method="post" action="/clear-cart">
        <button type="submit" class="clear-btn">Очистить корзину</button>
    </form>
<?php endif; ?>

</body>
</html>
