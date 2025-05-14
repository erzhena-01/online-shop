<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/registration') {
    require_once './classes/User.php';
    $user = new User();
    if ($requestMethod === 'GET') {

        $user->getRegistrate();;

    } elseif ($requestMethod === 'POST') {

       $user->registrate();

    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }



} elseif ($requestUri === '/profile') {

    require_once './classes/User.php';
    $user = new User();

    if ($requestMethod === 'GET') {
        $user->getProfile();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }


} elseif ($requestUri === '/edit-profile') {

    require_once './classes/User.php';
    $user = new User();

    if ($requestMethod === 'GET') {
        $user->getProfileForm();

    } elseif ($requestMethod === 'POST') {
       $user->editProfile();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }



}elseif ($requestUri === '/login') {

    require_once './classes/User.php';
    $user = new User();

    if ($requestMethod === 'GET') {
        $user->getLogin();
    } elseif ($requestMethod === 'POST') {
       $user->login();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }



} elseif ($requestUri === '/catalog') {

    require_once './classes/Product.php';
    $product = new Product();

    if ($requestMethod === 'GET') {
        $product->getCatalog();

    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }




}elseif ($requestUri === '/add-product') {

    require_once './classes/Product.php';
    $product = new Product();

    if ($requestMethod === 'GET') {
        $product->getProductsForm();
    } elseif ($requestMethod === 'POST') {
        $product->getProduct();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }



} elseif ($requestUri === '/cart') {

    require_once './classes/Product.php';
    $product = new Product();

    if ($requestMethod === 'GET') {
        require_once './cart/cart_page.php';

    } elseif ($requestMethod === 'POST') {
       $product->getCart();

    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }


} elseif ($requestUri === '/clear-cart') {

    require_once './classes/Product.php';
    $product = new Product();

    if ($requestMethod === 'GET') {
        $product->getCart();

    } elseif ($requestMethod === 'POST') {
        $product->clearCart();

    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }



} else {
    http_response_code(404);
    require_once './404.php';
}