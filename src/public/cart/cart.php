<?php
session_start();

// Получаем корзину из сессии
$cart = $_SESSION['cart'] ?? [];
require_once '/cart_page.php';
?>


