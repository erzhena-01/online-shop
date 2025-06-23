<?php
namespace Controllers;

use Model\UserProduct;
use Service\AuthService;
use Service\CartService;

class CartController extends BaseController
{
    private UserProduct $cartModel;
    private CartService $cartService;

    public function __construct()
    {
        parent::__construct();
        $this->cartModel = new UserProduct();
        $this->authService = new AuthService();
        $this->cartService = new CartService();
    }
    public function getCart()
    {

        if(!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->authService->getCurrentUserId();

        $cartModel = new \Model\UserProduct();

         $cart = $cartModel->getAllByUserId($userId);
        require_once '../Views/cart_page.php';

    }
    public function clearCart()
    {


        if (!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $cartModel = new \Model\UserProduct();

        $userId = $this->authService->getCurrentUserId();
        $cartModel->deleteByUserId($userId);

        header("Location: /cart");
        exit;
    }


    public function decreaseProduct()
    {

        if(!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->authService->getCurrentUserId();
        $productId = $_POST['productId'];

        $this->cartService->decreaseProduct($userId, $productId);

        header("Location: /cart");
        exit;

    }

    public function increaseProduct()
    {

        if(!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $userId = $this->authService->getCurrentUserId();
        $productId = $_POST['productId'];

        $this->cartService->addProductToCart($userId, $productId);

        header("Location: /cart");
        exit;
    }



}