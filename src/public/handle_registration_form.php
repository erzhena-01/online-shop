<?php

$name = $_GET["name"];
$email = $_GET["email"];
$password = $_GET["psw"];
$passwordRepeat = $_GET["psw-repeat"];




if (strlen($name) <= 2) {
    echo "Данное имя не подходит" . "\n";
}

if (strpos($email, '@') === false || strlen($email) <= 2) {
    echo "Email не подходит" . "\n";
}

if ($password !== $passwordRepeat) {
    echo "Пароль не соответствует" . "\n";
} else {
    echo "Вход успешно выполнен!" ."\n";
}


if ($name && $email && $password === $passwordRepeat && strlen($name) >= 2 && strpos($email, '@') == true) {

    $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');

    $pdo->exec("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");



    $statement = $pdo->query("SELECT * FROM users order by id desc limit 1");
    echo "<pre>";
    $data = $statement->fetch();
    print_r($data);
}