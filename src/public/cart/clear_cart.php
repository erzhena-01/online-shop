<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userId = $_SESSION['user_id'];

$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
$stmt = $pdo->prepare("DELETE FROM user_products WHERE user_id = :userId");
$stmt->execute(['userId' => $userId]);

header("Location: /cart");
exit;
