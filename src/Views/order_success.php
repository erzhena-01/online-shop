<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказ оформлен</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f8f8f8;
        }
        .container {
            background: white;
            max-width: 600px;
            margin: 0 auto;
            padding: 30px 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-radius: 8px;
        }
        h1 {
            color: #2ecc71;
            text-align: center;
            margin-bottom: 20px;
        }
        .order-info {
            font-size: 16px;
            margin-bottom: 30px;
        }
        .order-info div {
            margin-bottom: 10px;
        }
        a {
            display: block;
            text-align: center;
            padding: 12px 24px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
        }
        a:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🎉 Спасибо за заказ!</h1>

    <div class="order-info">
        <div><strong>Номер заказа:</strong> <?= $order['id'] ?></div>
        <div><strong>Имя клиента:</strong> <?= $order['customer_name'] ?></div>
        <div><strong>Адрес доставки:</strong> <?= $order['address'] ?></div>
        <div><strong>Дата заказа:</strong> <?= $order['created_at'] ?></div>
    </div>

    <a href="/catalog">Продолжить покупки</a>
</div>
</body>
</html>
