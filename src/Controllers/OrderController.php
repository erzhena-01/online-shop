<?php
namespace Controllers;

use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;
use Model\Order;

class OrderController
{
    private UserProduct $userProductModel;
    private Product $productModel;
    private OrderProduct $orderProductModel;
    private Order $orderModel;


    public function __construct()
    {
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
        $this->orderModel = new Order();
    }


    public function createOrder()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $name = $_POST['name'] ?? '';
        $phone = $_POST['contact_phone'] ?? '';
        $address = $_POST['address'] ?? '';

        $cartModel = new UserProduct();
        $items = $cartModel->getAllByUserId($userId);

        if (empty($items)) {
            echo "Корзина пуста";
            exit;
        }

        $orderModel = new Order();
        $orderModel->create($userId, $name, $phone, $address, $items);

        // очищаем корзину
        $cartModel->deleteByUserId($userId);

        // перенаправляем на страницу заказов
        header('Location: /my_orders');
        exit;
    }

    public function myOrders()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $orderModel = new Order();
        $orders = $orderModel->getByUserId($_SESSION['user_id']);

        require_once '../Views/my_orders.php';
    }


public function getCheckoutForm()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];

        $cart = new UserProduct();
        $items = $cart->getItems($userId);

        require_once '../Views/order_form.php';
    }

    private function validateOrder(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = "Имя обязательно";
        }

        if (empty($data['contact_phone'])) {
            $errors[] = "Номер телефона обязателен";
        }

        if (empty($data['address'])) {
            $errors[] = "Адрес обязателен";
        }


        return $errors;
    }

    public function handleCheckout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $errors = $this->validateOrder($_POST);

        $userId = $_SESSION['user_id'];

        $cart = new UserProduct();
        $items = $cart->getItems($userId);


        if (empty($errors)) {
            $customerName = $_POST['name'];
            $contactPhone = $_POST['contact_phone'];
            $address = $_POST['address'];
            $userId = $_SESSION['user_id'];


            $orderModel = new Order;
            $orderId = $orderModel->create($customerName, $contactPhone, $address, $userId);

            $userProductModel = new UserProduct();
            $userProducts = $userProductModel->getAllByUserId($userId);


            $orderProduct = new OrderProduct();
            foreach ($userProducts as $userProduct) {
                $productId = $userProduct['product_id'];
                $amount = $userProduct['amount'];

                $orderProduct->create($orderId, $productId, $amount);

            }

            $userProductModel->deleteByUserId($userId);

            header("Location: /catalog");
            exit;
        }

    }

    public function getMyOrders()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $userOrders = $this->orderModel->getByUserId($userId);

        $newUserOrders = [];

        foreach ($userOrders as $userOrder) {
            $orderProducts = $this->orderProductModel->getAllByOrderId($userOrder['id']);

            $newOrderProducts = [];
            $sum = 0;

            foreach ($orderProducts as $orderProduct) {
                $product = $this->productModel->getProductById($orderProduct['product_id']);
                $orderProduct['name'] = $product['name'];
                $orderProduct['price'] = $product['price'];
                $orderProduct['totalSum'] = $orderProduct['price'] * $orderProduct['amount'];

                $newOrderProducts[] = $orderProduct;
                $sum += $orderProduct['totalSum'];
            }

            $userOrder['total'] = $sum;
            $userOrder['products'] = $newOrderProducts;

            $newUserOrders[] = $userOrder;
        }

        require_once '../Views/my_orders.php';
    }



}