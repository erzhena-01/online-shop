<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Оформление заказа</title>
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

        .form-container {
            width: 60%;
            margin: auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-radius: 8px;
        }

        label {
            display: block;
            margin-bottom: 20px;
            font-size: 16px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        .submit-btn {
            display: block;
            width: 100%;
            margin-top: 20px;
            background: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
        }

        .submit-btn:hover {
            background: #1e874b;
        }
    </style>
</head>
<body>

<h1>📦 Оформление заказа</h1>

<div class="form-container">
    <form method="POST" action="/order-checkout">
        <label>
            Имя:
            <input type="text" name="name" required>
        </label>

        <label>
            Адрес:
            <textarea name="address" required></textarea>
        </label>

        <button type="submit" class="submit-btn">Оформить</button>
    </form>
</div>

</body>
</html>
