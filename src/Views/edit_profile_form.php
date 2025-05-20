<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (isset($_SESSION['user_id'])){
    $userId = $_SESSION['user_id'];

    $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
    $stmt = $pdo->query("SELECT * FROM users WHERE id = $userId");
    $user = $stmt->fetch();
} else {
    header('Location: /login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать профиль</title>
    <style>
        .container {
            padding: 20px;
            max-width: 600px;
            margin: auto;
            background-color: #f9f9f9;
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }
        input[type=text],
        input[type=email],
        input[type=password] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 20px;
            border: 1px solid #ccc;
            background: #f1f1f1;
            border-radius: 4px;
        }
        .save-btn {
            background-color: #04AA6D;
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 4px;
            font-size: 16px;
        }
        .save-btn:hover {
            background-color: #039e63;
        }
        ul.errors {
            color: red;
            list-style: inside square;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><p><a href="/edit-profile">Редактировать профиль</a></p>
    </h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= ($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <label for="name"><b>Новое имя</b></label>
        <input type="text" name="name" id="name" value = "<?php echo $user['name']; ?>">

        <label for="email"><b>Новый Email</b></label>
        <input type="email" name="email" id="email" value = "<?php echo $user['email']; ?>" >

        <label for="psw"><b>Новый пароль</b></label>
        <input type="password" name="psw" id="psw">

        <label for="psw-repeat"><b>Повтор пароля</b></label>
        <input type="password" name="psw-repeat" id="psw-repeat">

        <button type="submit" class="save-btn">Сохранить</button>
    </form>
</div>
</body>
</html>
