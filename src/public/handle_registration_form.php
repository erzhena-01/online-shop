<?php


// Получение данных формы
$name = $_GET["name"];
$email = $_GET["email"];
$password = $_GET["psw"];
$passwordRepeat = $_GET["psw-repeat"];

// Валидация
if (strlen($name) <= 2) {
    echo "Данное имя не подходит" . "\n";
}

if (strpos($email, '@') !== false && strlen($email) <= 2) {
    echo "Email не подходит" . "\n";
}

if ($password !== $passwordRepeat) {
    echo "Пароль не соответствует" . "\n";
} else {
    echo "Вход успешно выполнен!" ."\n";
}

// Добавление в базу (если всё ок)
if ($name && $email && $password === $passwordRepeat && strlen($name) >= 2 && strpos($email, '@') == true) {

    $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');

    $pdo->exec("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");

}

// Вывод пользователя
$statement = $pdo->query("SELECT * FROM users");
echo "<pre>";
$data = $statement->fetch(PDO::FETCH_ASSOC);
print_r($data);
