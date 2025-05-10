<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login_form.php");

}

function handleProfileUpdate(array $data): array
{
    $errors = [];

    if (isset($data["name"])) {
        $name = $data["name"];

        if (strlen($name) <= 2) {
            $errors['name'] = "Имя должно быть больше 2 символов";
        }
    } else {
        $errors['name'] = "Имя должно быть заполнено";
    }

    if (isset($data["email"])) {
        $email = $data["email"];

        if (strlen($email) <= 2) {
            $errors['email'] = "email должен быть больше 2 символов";
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = "email некорректный";
        } else {
            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email AND id != :id");
            $stmt->execute([':email' => $email, ':id' => $_SESSION['user_id']]);
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                $errors['email'] = "Пользователь с таким email уже существует";
            }
        }
    } else {
        $errors["email"] = "Email должен быть заполнен";
    }

    if (isset($data['psw'])) {
        $psw = $data['psw'];

        if (strlen($psw) <= 2) {
            $errors['psw'] = "Пароль должен быть больше 2 символов";
        }

        $pswRepeat = $data['psw-repeat'] ?? '';

        if ($psw !== $pswRepeat) {
            $errors['psw-repeat'] = "Пароль не соответствует";
        }
    } else {
        $psw = '';
    }

    // Обновление данных, если нет ошибок
    if (empty($errors)) {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
        if (!empty($psw)) {
            $passwordHash = password_hash($psw, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $passwordHash,
                ':id' => $_SESSION['user_id']
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':id' => $_SESSION['user_id']
            ]);
        }

        header("Location: /profile");
        exit;
    }

    return $errors;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = handleProfileUpdate($_POST);
}

// Получение текущих данных пользователя
$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
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
    <h1><p><a href="/profile">Редактировать профиль</a></p>
    </h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <label for="name"><b>Имя</b></label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label for="email"><b>Email</b></label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label for="psw"><b>Новый пароль</b></label>
        <input type="password" name="psw" id="psw">

        <label for="psw-repeat"><b>Повтор пароля</b></label>
        <input type="password" name="psw-repeat" id="psw-repeat">

        <button type="submit" class="save-btn">Сохранить</button>
    </form>
</div>
</body>
</html>