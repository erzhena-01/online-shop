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

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 60%;
            border-collapse: collapse;
            margin: auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        th, td {
            border: 1px solid #eee;
            padding: 15px;
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
            margin: 30px auto;
            background: #e74c3c;
            color: white;
            padding: 12px 24px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
        }

        .clear-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

<h1>🛒 Ваша корзина</h1>

<?php if (empty($cart)): ?>
    <div class="empty">Корзина пуста.</div>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>ID товара</th>
            <th>Количество</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($cart as $item): ?>
            <tr>
                <td><?= ($item['id']) ?></td>
                <td><?= (int)$item['amount'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div style="text-align: center;">
        <form method="post" action="/clear-cart" style="margin-bottom: 20px;">
            <button type="submit" class="clear-btn">Очистить корзину</button>
        </form>

        <a href="/create-order"
           class="clear-btn"
           style="background: #27ae60; text-align: center; display: inline-block; text-decoration: none;">
            Оформить заказ
        </a>
    </div>

<?php endif; ?>

</body>
</html>
