<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/registration') {
    if ($requestMethod === 'GET') {
        require_once './registration_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './hrf.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }

} elseif ($requestUri === '/profile') {
    require_once './profile.php';

}elseif ($requestUri === '/login') {
    if ($requestMethod === 'GET') {
        require_once './login_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './handle_login.php';
    }

} elseif ($requestUri === '/catalog') {
    if ($requestMethod === 'GET') {
        require_once './catalog.php';
    }
} elseif ($requestUri === '/edit_profile') {
        require_once './edit_profile.php';

}elseif ($requestUri === '/add_product') {
    if ($requestMethod === 'GET') {
        require_once './add_product_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './handle_add_product.php';
    }

} elseif ($requestUri === '/cart') {
    if ($requestMethod === 'GET') {
        require_once './cart.php';
    }

} else {
    http_response_code(404);
    require_once './404.php';
}