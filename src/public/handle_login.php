<?php

$errors = [];

if (isset($_POST['name']) && isset($_POST['password'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
    $stmt = $pdo->prepare('SELECT * FROM users WHERE name = :name');
    $stmt->execute(['name' => $name]);

    $user = $stmt->fetch();

    if ($user === false) {
        $errors['name'] = 'username or password incorrect';
    } else {
        $passwordDb = $user['password'];

        if (password_verify($password, $passwordDb)) {
            setcookie('user_id', $user['id']);
            header("Location: /catalog.php");
            exit;
        } else {
            $errors['name'] = 'username or password incorrect';
        }
    }
}

require_once './login_form.php';
