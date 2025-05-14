<?php

$errors = [];

function IsValidateLogin(array $data): array
{
    $errors = [];

    if (!isset($data['name']) ) {
        $errors['name'] = "Имя должно быть заполнено";
    }

    if (!isset($data['password'])) {
        $errors['password'] = "Пароль должен быть заполнен";
    }

    return $errors;
}
$errors = IsValidateLogin($_POST);



if (empty($errors)) {
    $name = $_POST['name'];
    $psw = $_POST['psw'];

    $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
    $stmt = $pdo->prepare('SELECT * FROM users WHERE name = :name');
    $stmt->execute(['name' => $name]);

    $user = $stmt->fetch();

    if ($user === false) {
        $errors['name'] = 'Username or password incorrect';
    } else {
        $passwordDB = $user['password'];

        if (password_verify($password, $passwordDB)) {

            session_start();
            $_SESSION['user_id'] = $user['id'];

            header("Location: /catalog");
        } else {
            $errors['password'] = 'Username or password incorrect';
        }
    }
}


require_once './login_form.php';