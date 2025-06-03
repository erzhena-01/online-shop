<?php
namespace Controllers;

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




        $cartModel = new \Model\UserProduct();


         $cart = $cartModel->getAllByUserId($userId);
        require_once '../Views/cart_page.php';

    }
    public function clearCart()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $cartModel = new \Model\UserProduct();

        $userId = $_SESSION['user_id'];
        $cartModel->deleteByUserId($userId); // Исправили имя метода

        header("Location: /cart");
        exit;
    }



}