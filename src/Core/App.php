<?php
namespace Core;
use Controllers\CartController;
use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;

class App
{
    private array $routes = [];


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


    public function addRoute(string $route, string $routeMethod, string $className, string $method)
    {
        $this->routes[$route][$routeMethod] = [
            'class' => $className,
            'method' => $method
        ];
    }

    public function get(string $route, string $className, string $method)
    {
        $this->routes[$route]['GET'] = [
            'class' => $className,
            'method' => $method
        ];
    }

    public function post(string $route, string $className, string $method)
    {
        $this->routes[$route]['POST'] = [
            'class' => $className,
            'method' => $method
        ];
    }
}
