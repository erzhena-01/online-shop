<?php
namespace Controllers;

use Model\UserProduct;

class CartController extends BaseController
{
    public function getCart()
    {

        if(!$this->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->getCurrentUserId();

        $cartModel = new \Model\UserProduct();

         $cart = $cartModel->getAllByUserId($userId);
        require_once '../Views/cart_page.php';

    }
    public function clearCart()
    {


        if (!$this->check()) {
            header('Location: /login');
            exit;
        }

        $cartModel = new \Model\UserProduct();

        $userId = $this->getCurrentUserId();
        $cartModel->deleteByUserId($userId);

        header("Location: /cart");
        exit;
    }


    public function decreaseProduct()
    {

        if(!$this->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->getCurrentUserId();
        $productId = $_POST['productId'];

        $cartModel = new \Model\UserProduct();

        $productInCart = $cartModel->getByUserIdAndProductId($userId, $productId);

        if ($productInCart) {
            if ($productInCart->getAmount() > 1) {
                $cartModel->decreaseAmount($userId, $productId);
            } else {
                $cartModel->deleteByUserIdAndProductId($userId, $productId);
            }
        } else {
            echo "Ошибка: такой товар не найден в корзине";
            exit;
        }

        header("Location: /cart");
        exit;

    }

    public function increaseProduct()
    {

        if(!$this->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->getCurrentUserId();
        $productId = $_POST['productId'];

        $cartModel = new \Model\UserProduct();
        $productInCart = $cartModel->getByUserIdAndProductId($userId, $productId);

        if ($productInCart) {
            // если товар уже есть — увеличиваем количество
            $cartModel->increaseAmount($userId, $productId);
        } else {
            // если товара ещё нет — добавляем в корзину с количеством 1
            $cartModel->addToCart($userId, $productId, 1);
        }

        header("Location: /cart");
        exit;
    }



}