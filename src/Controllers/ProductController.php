<?php
namespace Controllers;

use Model\Product;

class ProductController extends BaseController
{
    public function getCatalog()
    {
        if (!$this->check()) {
            header("Location: /login_form.php");
            exit;
        }

        $productModel = new Product();
        $products = $productModel->getProductsList();

        require_once '../Views/catalog_page.php';
    }

    public function getProductsForm()
    {
        require_once '../Views/add_product_form.php';
    }

    public function getProduct()
    {
        if (!$this->check()) {
            header('Location: /login');
            exit;
        }

        $errors = $this->validate($_POST);

        if (empty($errors)) {
            $productModel = new Product();

            $product = $productModel->getProductByIds((int)$_POST['product_id'], $_SESSION['user_id']);

            if ($product === null) {
                $productModel->addUserProduct((int)$_POST['product_id'], (int)$_POST['amount']);
            } else {
                $newAmount = $product->getAmount() + (int)$_POST['amount'];
                $productModel->updateUserProductAmount($_SESSION['user_id'], (int)$_POST['product_id'], $newAmount);
            }

            header('Location: /cart');
            exit;
        }

    }

    private function validate(array $data): array
    {
        $errors = [];

        $productModel = new Product();

        if (isset($data['product_id'])) {
            $productId = (int)$data['product_id'];

            $product = $productModel->getProductById($productId);
            if ($product === null) {
                $errors['product_id'] = 'Продукт не найден';
            }
        } else {
            $errors['product_id'] = 'Укажите ID продукта';
        }

        if (isset($data['amount'])) {
            $amount = (int)$data['amount'];
            if ($amount <= 0) {
                $errors['amount'] = 'Количество должно быть положительным числом';
            }
        } else {
            $errors['amount'] = 'Укажите количество';
        }

        return $errors;
    }
}
