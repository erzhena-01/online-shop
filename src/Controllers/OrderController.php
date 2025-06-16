<?php
namespace Controllers;

use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;
use Model\Order;

class OrderController extends BaseController
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

        if ($this->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->getCurrentUserId();
        $name = $_POST['name'] ;
        $phone = $_POST['contact_phone'];
        $address = $_POST['address'];

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

        if ($this->check()) {
            header('Location: /login');
            exit;
        }

        $orderModel = new Order();
        $orders = $orderModel->getByUserId($_SESSION['user_id']);

        require_once '../Views/my_orders.php';
    }


public function getCheckoutForm()
    {

        if ($this->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->getCurrentUserId();

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

        if ($this->check()) {
            header('Location: /login');
            exit;
        }

        $errors = $this->validateOrder($_POST);

        $userId = $this->getCurrentUserId();

        $cart = new UserProduct();
        $items = $cart->getItems($userId);

        if (empty($errors)) {
            $customerName = $_POST['name'];
            $contactPhone = $_POST['contact_phone'];
            $address = $_POST['address'];

            $orderModel = new Order();
            $orderId = $orderModel->create($customerName, $contactPhone, $address, $userId);

            $userProductModel = new UserProduct();
            $userProducts = $userProductModel->getAllByUserId($userId);

            $orderProduct = new OrderProduct();
            foreach ($userProducts as $userProduct) {

                $productId = $userProduct->getProductId();
                $amount = $userProduct->getAmount();

                $orderProduct->create($orderId, $productId, $amount);
            }

            $userProductModel->deleteByUserId($userId);

            header("Location: /catalog");
            exit;
        }
    }

    public function getMyOrders()
    {

       if($this->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->getCurrentUserId();
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