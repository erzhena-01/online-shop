<h1>📋 Мои заказы</h1>

<?php foreach ($newUserOrders as $order): ?>
    <h2>Заказ #<?= $order['id'] ?> от <?= $order['created_at'] ?></h2>
    <p><strong>Имя:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
    <p><strong>Телефон:</strong> <?= htmlspecialchars($order['contact_phone']) ?></p>
    <p><strong>Адрес:</strong> <?= htmlspecialchars($order['address']) ?></p>
    <p><strong>Общая сумма:</strong> <?= $order['total'] ?>₽</p>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Название товара</th>
            <th>Цена</th>
            <th>Количество</th>
            <th>Сумма</th>
        </tr>
        <?php foreach ($order['products'] as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= $product['price'] ?>₽</td>
                <td><?= $product['amount'] ?></td>
                <td><?= $product['totalSum'] ?>₽</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <hr>
<?php endforeach; ?>
