<?php

if(!isset($_COOKIE['user_id'])){
  header("Location: /login_form.php");
}
$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();

print_r($products);

require_once './catalog_page.php';