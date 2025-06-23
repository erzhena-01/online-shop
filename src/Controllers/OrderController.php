<?php
namespace Controllers;

use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;
use Model\Order;
use Service\AuthService;
use Service\OrderService;

class OrderController extends BaseController
{
    private UserProduct $userProductModel;
    private Product $productModel;
    private OrderProduct $orderProductModel;
    private Order $orderModel;
    private OrderService $orderService;

    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
        $this->orderModel = new Order();
        $this->authService = new AuthService();
        $this->orderService = new OrderService();

    }



    public function createOrder()
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->authService->getCurrentUserId();
        $name = $_POST['name'];
        $phone = $_POST['contact_phone'];
        $address = $_POST['address'];

        $orderId = $this->orderService->createOrder($userId, $name, $phone, $address);

        if ($orderId === null) {
            echo "Корзина пуста";
            exit;
        }

        header('Location: /my_orders');
        exit;
    }


    public function myOrders()
    {

        if (!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $orderModel = new Order();
        $orders = $orderModel->getByUserId($_SESSION['user_id']);

        require_once '../Views/my_orders.php';
    }


public function getCheckoutForm()
    {

        if (!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->authService->getCurrentUserId();

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
        if (!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $errors = $this->validateOrder($_POST);
        $userId = $this->authService->getCurrentUserId();

        if (!empty($errors)) {

            return;
        }

        $name = $_POST['name'];
        $phone = $_POST['contact_phone'];
        $address = $_POST['address'];

        $orderId = $this->orderService->createOrder($userId, $name, $phone, $address);

        if ($orderId === null) {
            echo "Корзина пуста";
            return;
        }

        header("Location: /catalog");
        exit;
    }


    public function getMyOrders()
    {

       if(!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->authService->getCurrentUserId();
        $orders = $this->orderModel->getByUserId($userId);
        $userOrders = [];

        foreach ($orders as $order) {
            $orderProducts = $this->orderProductModel->getAllByOrderId($order->getId());

            $newOrderProducts = [];
            $sum = 0;

            foreach ($orderProducts as $orderProduct) {
                $productId = $orderProduct->getProductId();
                $product = $this->productModel->getProductById($productId);

                $newOrderProducts[] = [
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'amount' => $orderProduct->getAmount(),
                    'totalPrice' => $product->getPrice() * $orderProduct->getAmount()
                ];

                $sum += $product->getPrice() * $orderProduct->getAmount();
            }

            $userOrders[] = [
                'order' => $order,
                'orderProducts' => $newOrderProducts,
                'total' => $sum
            ];
        }

        require_once '../Views/my_orders.php';
    }

}