<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fbfc;
        margin: 0;
        padding: 30px;
        color: #333;
    }

    h1 {
        text-align: center;
        font-size: 32px;
        margin-bottom: 40px;
        color: #2c3e50;
    }

    h2 {
        font-size: 20px;
        margin-top: 30px;
        margin-bottom: 10px;
        color: #34495e;
    }

    p {
        font-size: 16px;
        margin: 5px 0;
    }

    strong {
        font-weight: 600;
        color: #2c3e50;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        margin-bottom: 30px;
        background-color: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    th, td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    th {
        background-color: #f0f4f8;
        color: #2d3e50;
        font-weight: 600;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background-color: #f7faff;
    }

    hr {
        border: none;
        border-top: 1px solid #ddd;
        margin: 40px 0;
    }

    @media (max-width: 768px) {
        body {
            padding: 15px;
        }

        h1 {
            font-size: 26px;
        }

        h2 {
            font-size: 18px;
        }

        table, thead, tbody, th, td, tr {
            font-size: 14px;
        }
    }
</style>


<h1>📋 Мои заказы</h1>

<?php foreach ($userOrders as $userOrder) { ?>
    <h2>Заказ #<?= $userOrder['order']->getId() ?> от <?= $userOrder['order']->getCreatedAt() ?></h2>
    <p><strong>Имя:</strong> <?= ($userOrder['order']->getCustomerName()) ?></p>
    <p><strong>Телефон:</strong> <?= ($userOrder['order']->getContactPhone()) ?></p>
    <p><strong>Адрес:</strong> <?= ($userOrder['order']->getAddress()) ?></p>
    <p><strong>Общая сумма:</strong> <?= $userOrder['total'] ?>₽</p>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Название товара</th>
            <th>Цена</th>
            <th>Количество</th>
            <th>Сумма</th>
        </tr>
        <?php foreach ($userOrder['orderProducts'] as $newOrderProduct) { ?>
            <tr>
                <td><?= ($newOrderProduct['name']) ?></td>
                <td><?= $newOrderProduct['price'] ?>₽</td>
                <td><?= $newOrderProduct['amount'] ?></td>
                <td><?= $newOrderProduct['totalPrice'] ?>₽</td>
            </tr>
        <?php } ?>
    </table>
    <hr>
<?php } ?>
