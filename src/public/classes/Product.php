<?php

class Product
{

    public function getCatalog()
    {

        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login_form.php");
            exit;
        }

        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');

        $stmt = $pdo->query('SELECT * FROM products');
        $products = $stmt->fetchAll();

        require_once './catalog/catalog_page.php';

    }


    public function getCart()
    {
        session_start();


        $cart = $_SESSION['cart'] ?? [];
        require_once '/cart_page.php';

    }

    public function clearCart()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];

        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
        $stmt = $pdo->prepare("DELETE FROM user_products WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);

        header("Location: /cart");
        exit;
    }


    public function getProductsForm()
    {
        require_once './addProduct/add_product_form.php';
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
            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
            $userId = $_SESSION['user_id'];
            $productId = $_POST['product_id'];
            $amount = $_POST['amount'];

            $stmt = $pdo->prepare('SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId');
            $stmt->execute([':productId' => $productId, ':userId' => $userId]);
            $data = $stmt->fetch();

            if ($data === false) {
                $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :productId, :amount)");
                $stmt->execute(['user_id' => $_SESSION['user_id'], 'productId' => $productId, 'amount' => $amount]);
            } else {
                $amount = $data['amount'] + $amount;

                $stmt = $pdo->prepare("UPDATE user_products SET amount = :amount WHERE user_id = :userId AND product_id = :productId");
                $stmt->execute(['amount' => $amount, 'userId' => $userId, 'productId' => $productId]);

            }

            header('Location: /cart'); // или путь к вашей странице корзины
            exit;

        }

    }

    private function validate(array $data): array
    {
        $errors = [];

        if (isset($data['product_id'])) {
            $productId = (int)$data['product_id'];

            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :productId");
            $stmt->execute([':productId' => $productId]);
            $data = $stmt->fetch();

            if ($data === false) {
                $errors['product_id'] = 'Продукт не найден';
            }

        } else {
            $errors['product_id'] = 'Укажите ID продукта';
        }

        if (isset ($data['amount'])) {
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

