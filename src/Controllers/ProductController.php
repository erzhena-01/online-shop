<?php
namespace Controllers;

use Model\Product;
use Model\Review;

class ProductController extends BaseController
{

    private Product $productModel;
    private Review $reviewModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->reviewModel = new Review();
    }
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

    public function getProductPage()
    {
        if(!$this->check()) {
            header('Location: /login');
            exit;
        }

        $productId = $_POST['productId'];

        if (!$productId) {
            die('ID продукта не передан.');
        }


        $product = $this->productModel->getProductById((int)$productId);

        if (!$product) {
            die('Товар не найден.');
        }


        $reviews = $this->reviewModel->getByProductId((int)$productId);

        require_once '../Views/product_page.php';
    }

    private function validateReview(array $data): array
    {
        $errors = [];

        if (empty($data['product_id'])) {
            $errors[] = 'Некорректный ID продукта';
        }
        if (empty($data['rating'])) {
            $errors[] = 'Оценка должна быть от 1 до 5';
        }
        if (empty($data['comment'])) {
            $errors[] = 'Комментарий не может быть пустым';
        }
        return $errors;

    }
    public function addReview()
    {
        if (!$this->check()) {
            header('Location: /login');
            exit;
        }

        $data = $_POST;
        $errors = $this->validateReview($data);

        if (empty($errors)) {
            $reviewModel = new \Model\Review();
            $reviewModel->addReview(
                (int)$data['product_id'],
                $this->getCurrentUserId(),
                (int)$data['rating'],
                trim($data['comment'])
            );

            $productId = (int)$data['product_id'];

            $this->productModel = new \Model\Product();
            $product = $this->productModel->getProductById($productId);

            if (!$product) {
                die('Товар не найден.');
            }

            $reviews = $reviewModel->getByProductId($productId);


            require_once '../Views/product_page.php';
            exit;
        } else {

            print_r($errors);
        }
    }



}
