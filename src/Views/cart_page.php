

<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <style>

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #f8f8f8;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        tr:hover {
            background-color: #f1f7ff;
        }

        .clear-btn {
            background-color: #e74c3c;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-right: 10px;
            transition: background-color 0.3s ease;
            display: inline-block;
            text-decoration: none;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .clear-btn:hover {
            background-color: #c0392b;
        }

        a.clear-btn {
            background-color: #27ae60;
        }

        a.clear-btn:hover {
            background-color: #219150;
        }

        .empty {
            font-size: 18px;
            color: #666;
            text-align: center;
            padding: 40px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        div[style*="text-align: center;"] {
            margin-top: 20px;
        }
    </style>
</head>



<?php if (empty($cart)): ?>
    <div class="empty">Корзина пуста.</div>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>ID товара</th>
            <th>Название</th>
            <th>Описание</th>
            <th>Цена</th>
            <th>Количество</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($cart as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item->getProductId()) ?></td>
                <td><?= htmlspecialchars($item->getName()) ?></td>
                <td><?= htmlspecialchars($item->getDescription()) ?></td>
                <td><?= $item->getPrice() ?> ₽</td>
                <td>
                    <form method="post" action="/cart/decrease" style="display: inline;">
                        <input type="hidden" name="productId" value="<?= $item->getProductId() ?>">
                        <button type="submit" style="padding: 5px 10px;">−</button>
                    </form>

                    <?= $item->getAmount() ?>

                    <form method="post" action="/cart/increase" style="display: inline;">
                        <input type="hidden" name="productId" value="<?= $item->getProductId() ?>">
                        <button type="submit" style="padding: 5px 10px;">+</button>
                    </form>

                </td>
                <td><?= $item->getAmount() * $item->getPrice() ?> ₽</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div style="text-align: center;">
        <form method="post" action="/clear-cart" style="margin-bottom: 20px; display: inline-block;">
            <button type="submit" class="clear-btn">Очистить корзину</button>
        </form>

        <a href="/create-order" class="clear-btn">
            Оформить заказ
        </a>
    </div>
<?php endif; ?>
