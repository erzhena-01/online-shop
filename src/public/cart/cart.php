<?php


if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


if(!isset($_SESSION['user_id'])) {
    header('location: /login');
    exit;
}

$userId = $_SESSION['user_id'];

$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');

$stmt = $pdo->query("SELECT * FROM user_products WHERE user_id = {$userId}");
$userProducts = $stmt->fetchAll();

foreach ($userProducts as $userProduct) {
    $productId = $userProduct['product_id'];

$stmt = $pdo->query("SELECT * FROM products WHERE id = {$productId}");
$product = $stmt->fetch();
$product['amount'] = $userProduct['amount'];

$products[] = $product;

}
