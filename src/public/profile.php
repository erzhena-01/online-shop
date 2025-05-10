<?php
session_start();

// Проверка: авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: /login_form.php");
    exit;
}

$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');

// Получение данных пользователя
$stmt = $pdo->prepare('SELECT id, name, email FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    echo "Пользователь не найден.";
    exit;
}

require_once './profile_form.php';
?>


