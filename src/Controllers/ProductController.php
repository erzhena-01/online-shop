<?php
namespace Controllers;


class ProductController
{

    public function getCatalog()
    {

        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login_form.php");
            exit;
        }


        $productModel = new \Model\Product();

        $products = $productModel->getProductsList();

        require_once '../Views/catalog_page.php';

    }





    public function getProductsForm()
    {
        require_once '../Views/add_product_form.php';
    }


    public function getProduct()
    {


        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }


        $errors = $this->validate($_POST);

        if (empty($errors)) {

            $productModel = new \Model\Product();

            $data = $productModel->getProductByIds($_POST['product_id'], $_SESSION['user_id']);


            if ($data === false) {

                $productModel->addUserProduct((int)$_POST['product_id'], (int)$_POST['amount']);


            } else {
                $amount = $data['amount'] + (int)$_POST['amount'];


                $productModel->updateUserProductAmount($_SESSION['user_id'], (int)$_POST['product_id'], $amount);

            }

            header('Location: /cart');
            exit;

        }

    }

    private function validate(array $data): array
    {
        $errors = [];


        $productModel = new \Model\Product();

        if (isset($data['product_id'])) {
            $productId = (int)$data['product_id'];

            $data = $productModel->getProductById($productId);

            if ($data === false) {
                $errors['product_id'] = 'Продукт не найден';
            }

        } else {
            $errors['product_id'] = 'Укажите ID продукта';
        }

        if (isset($data['amount'])) {
            $amount = (int)$data['amount'];
            if ($amount <= 0) {
                $errors['amount'] = 'Количество должно быть положительным числом';
            } else {
                $errors['amount'] = 'Укажите количество';
            }
        }


        return $errors;
    }
}

