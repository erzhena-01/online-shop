<?php


class CartController
{
    public function getCart()
    {

        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }


        if(!isset($_SESSION['user_id'])) {
            header('location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];



        require_once '../Model/Cart.php';
        $cartModel = new Cart();


         $cart = $cartModel->getUserCart($userId);
        require_once '../Views/cart_page.php';

    }
    public function clearCart()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        require_once '../Model/Cart.php';
        $cartModel = new Cart();

        $userId = $_SESSION['user_id'];
        $cartModel->deleteProduct($userId);

        header("Location: /cart");
        exit;
    }


}