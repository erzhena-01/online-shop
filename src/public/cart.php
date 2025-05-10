<?php
session_start();

// Получаем корзину из сессии
$cart = $_SESSION['cart'] ?? [];
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
        <?php foreach ($cart as $productId => $amount): ?>
            <tr>
                <td><?= $productId ?></td>
                <td><?= (int)$amount ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <?php endif; ?>
</body>
</html>

