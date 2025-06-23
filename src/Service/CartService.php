<?php

namespace Service;

use Model\Product;
use Model\UserProduct;

class CartService
{
    private UserProduct $cartModel;

    public function __construct()
    {
        $this->cartModel = new UserProduct();
    }



    public function addProductToCart(int $userId, int $productId)
    {
        $productInCart = $this->cartModel->getByUserIdAndProductId($userId, $productId);

        if ($productInCart) {
            $this->cartModel->increaseAmount($userId, $productId);
        } else {
            $this->cartModel->addToCart($userId, $productId, 1);
        }
    }

    public function decreaseProduct(int $userId, int $productId)
    {
        $productInCart = $this->cartModel->getByUserIdAndProductId($userId, $productId);

        if ($productInCart) {
            if ($productInCart->getAmount() <= 1) {
                $this->cartModel->deleteByUserIdAndProductId($userId, $productId);
            } else {
                $this->cartModel->decreaseAmount($userId, $productId);
            }
        }
    }

}