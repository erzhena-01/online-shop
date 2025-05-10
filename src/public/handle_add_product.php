<?php
session_start();

// Валидация входных данных
function validateProductInput(string $productId, $amount): array {
    $errors = [];

    if (!$productId || strlen($productId) < 1) {
        $errors['product_id'] = 'Введите ID товара';
    }

    if (!is_numeric($amount) || $amount <= 0) {
        $errors['amount'] = 'Количество должно быть положительным числом';
    }

    return $errors;
}

// Добавление товара в корзину
function addToCart(string $productId, int $amount): void {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $amount;
    } else {
        $_SESSION['cart'][$productId] = $amount;
    }
}

// Получение данных из формы
$productId = $_POST['product_id'];
$amount = $_POST['amount'];

// Проверка
$errors = validateProductInput($productId, $amount);

if (!empty($errors)) {
    $_SESSION['add_errors'] = $errors;
    header("Location: /add_to_cart");
    exit;
}

// Добавляем товар в корзину
addToCart($productId, (int)$amount);

// Перенаправляем на страницу корзины
header("Location: /cart");
exit;
