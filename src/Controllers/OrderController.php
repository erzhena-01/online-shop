<?php
namespace Controllers;

use Model\Cart;
use Model\Order;

class OrderController
{
    public function getCheckoutForm()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];

        $cart = new Cart();
        $items = $cart->getItems($userId);

        require_once '../Views/order_checkout.php';
    }

    public function submitOrder(): void
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];

        $cart = new Cart();
        $items = $cart->getItems($userId);

        $customerName = $_POST['name'];
        $address = $_POST['address'];

        if (empty($customerName) || empty($address)) {
            echo "Ошибка: имя и адрес обязательны";
            return;
        }

        $order = new Order();

        $orderId = $order->createOrder($customerName, $address, $items, $userId);

        $cart->deleteProduct($userId);


        $_SESSION['last_order_id'] = $orderId;

        header("Location: /order-success");
        exit;
    }


    public function getSuccessPage(): void
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }


        $orderId = $_SESSION['last_order_id'];

        if (!$orderId) {
            echo "Ошибка: заказ не найден";
            return;
        }

        $orderModel = new Order();

        $order = $orderModel->getOrderById($orderId);


        if (!$order) {
            echo "Ошибка: заказ не найден";
            return;
        }


        require_once '../Views/order_success.php';
    }

}
