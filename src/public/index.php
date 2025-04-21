<?php



$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');

$pdo->exec( "INSERT INTO users (name, email, password) VALUES ('IVAN', 'ivan@mail.ru', 'qwerty')");

$statement = $pdo->query("SELECT * FROM users");
echo "<pre>";
$data = $statement->fetch();
echo "<pre>";
print_r($data);