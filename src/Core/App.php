<?php
namespace Core;
use Controllers\CartController;
use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;

class App
{
    private array $routes = [

        '/registration' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'registrate',
            ],
        ],

        '/login' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'login',
            ],
        ],

        '/logout' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getLogout',
            ]
        ],

        '/profile' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getProfile',
            ],
        ],

        '/edit-profile' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getProfileForm',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'editProfile',
            ],
        ],

        '/catalog' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'getCatalog',
            ],
        ],

        '/add-product' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'getProductsForm',
            ],
            'POST' => [
                'class' => ProductController::class,
                'method' => 'getProduct',
            ],
        ],

        '/cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'getCart',
            ],
        ],

        '/clear-cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'getCart',
            ],
            'POST' => [
                'class' => CartController::class,
                'method' => 'clearCart',
            ],
        ],

        '/create-order' => [
            'GET' => [
                'class' => OrderController::class,
                'method' => 'getCheckoutForm',
            ],
            'POST' => [
                'class' => OrderController::class,
                'method' => 'handleCheckout',
            ],
        ],


        '/my-orders' => [
            'GET' => [
                'class' => OrderController::class,
                'method' => 'getMyOrders',
            ]
        ]



    ];


    public function run()
    {


        $requestUri = ($_SERVER['REQUEST_URI']);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri])) {

            $routeMethods = $this->routes[$requestUri];
            if (isset ($routeMethods[$requestMethod])) {
                $handler = $routeMethods[$requestMethod];

                $class = $handler['class'];
                $method = $handler['method'];



                $controllerClass = $class;

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    $controller->$method();
                } else {
                    echo "Контроллер $controllerClass не найден";
                }

            } else {
                echo "$requestMethod для адреса $requestUri не поддерживается";
            }
        } else {

            http_response_code(404);
            require_once "../Views/404.php";
        }
    }
}

