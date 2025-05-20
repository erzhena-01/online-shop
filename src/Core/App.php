<?php

class App
{
    private array $routes = [

        '/registration' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'registrate',
            ],
        ],

        '/login' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'login',
            ],
        ],

        '/logout' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getLogout',
            ]
        ],

        '/profile' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getProfile',
            ],
        ],

        '/edit-profile' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getProfileForm',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'editProfile',
            ],
        ],

        '/catalog' => [
            'GET' => [
                'class' => 'ProductController',
                'method' => 'getCatalog',
            ],
        ],

        '/add-product' => [
            'GET' => [
                'class' => 'ProductController',
                'method' => 'getProductsForm',
            ],
            'POST' => [
                'class' => 'ProductController',
                'method' => 'getProduct',
            ],
        ],

        '/cart' => [
            'GET' => [
                'class' => 'CartController',
                'method' => 'getCart',
            ],
        ],

        '/clear-cart' => [
            'GET' => [
                'class' => 'CartController',
                'method' => 'getCart',
            ],
            'POST' => [
                'class' => 'CartController',
                'method' => 'clearCart',
            ],
        ],
    ];


    public function run()
    {


        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri])) {

            $routeMethods = $this->routes[$requestUri];
            if (isset ($routeMethods[$requestMethod])) {
                $handler = $routeMethods[$requestMethod];

                $class = $handler['class'];
                $method = $handler['method'];

                require_once "../Controllers/{$class}.php";

                $controller = new $class();
                $controller->$method();
            } else {
                echo "$requestMethod для адреса $requestUri не поддерживается";

            }
        } else {

            http_response_code(404);
            require_once "../Views/404.php";
        }
    }
}

