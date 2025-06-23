<?php

use Controllers\CartController;
use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;
use Core\App;

require_once './../Core/Autoloader.php';

$path = dirname(__DIR__);
\Core\Autoloader::register($path);

$app = new \Core\App();
$app->get('/registration', UserController::class, 'getRegistrate');
$app->post('/registration', UserController::class, 'registrate');
$app->get('/login', UserController::class, 'getLogin');
$app->post('/login', UserController::class, 'login');
$app->get('/logout',  UserController::class , 'getLogout');
$app->get('/profile',  UserController::class, 'getProfile');
$app->get('/edit-profile',  UserController::class, 'getProfileForm');
$app->post('/edit-profile',  UserController::class, 'editProfile');

$app->get('/catalog',   ProductController::class, 'getCatalog');
$app->get('/add-product',  ProductController::class, 'getProductsForm');
$app->post('/add-product',  ProductController::class, 'getProduct');

$app->get('/cart',  CartController::class, 'getCart');
$app->get('/clear-cart',  CartController::class, 'clearCart');
$app->post('/clear-cart',  CartController::class, 'clearCart');


$app->post('/cart/increase', CartController::class, 'increaseProduct');
$app->post('/cart/decrease', CartController::class, 'decreaseProduct');


$app->get('/create-order',  OrderController::class, 'getCheckoutForm');
$app->post('/create-order', OrderController::class, 'handleCheckout');
$app->get('/my-orders',  OrderController::class, 'getMyOrders');

$app->post('/add-review', ProductController::class, 'addReview');
$app->post('/product', ProductController::class, 'getProductPage');


$app->run();