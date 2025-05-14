<?php

class User
{

    public function getRegistrate()
    {
        require_once './registration/registration_form.php';
    }

    public function registrate()
    {

        $errors = $this->IsValidateForm($_POST);


        if (empty($errors)) {
            $name = $_POST["name"];
            $email = $_POST["email"];
            $psw = $_POST["psw"];
            $pswRepeat = $_POST["psw-repeat"];
            $passwordHash = password_hash($psw, PASSWORD_DEFAULT);

            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');


            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :psw)");
            $stmt->execute([':name' => $name, ':email' => $email, ':psw' => $passwordHash]);


            $result = $pdo->prepare("SELECT * FROM users WHERE email = :email;");
            $result->execute([':email' => $email]);
            $data = $result->fetch();
            print_r($data);

        } else {

            print_r($errors);

        }


        require_once './registration/registration_form.php';

    }

    private function IsValidateForm(array $data): array
    {
        $errors = [];


        if (isset($data["name"])) {
            $name = $data["name"];

            if (strlen($name) <= 2) {
                $errors['name'] = "Имя должно быть больше 2 символов";
            }
        } else {
            $errors['name'] = "Имя должно быть заполнено";
        }

        if (isset($data["email"])) {
            $email = $data["email"];

            if (strlen($email) <= 2) {
                $errors['email'] = "email должен быть больше 2 символов";
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $errors['email'] = "email некорректный";
            } else {
                $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
                $stmt->execute([':email' => $email]);
                $count = $stmt->fetchColumn();
                if ($count > 0) {
                    $errors['email'] = "Пользователь с таким email уже существует";
                }
            }
        } else {
            $errors["email"] = "Email должен быть заполнен";
        }


        if (isset($data['psw'])) {
            $psw = $data['psw'];

            if (strlen($psw) <= 2) {
                $errors['psw'] = "Пароль должен быть больше 2 символов";
            }

            $pswRepeat = $_POST['psw-repeat'];

            if ($psw !== $pswRepeat) {
                $errors['psw-repeat'] = "Пароль не соответствует" . "\n";
            }
        } else {
            $errors['psw'] = "Пароль должен быть заполнен";
        }


        return $errors;
    }

    public function getLogin()
    {
        require_once './login/login_form.php';
    }

    public function login()
    {


        $errors = $this->IsValidateLogin($_POST);


        if (empty($errors)) {
            $name = $_POST['name'];
            $password = $_POST['password'];

            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
            $stmt = $pdo->prepare('SELECT * FROM users WHERE name = :name');
            $stmt->execute(['name' => $name]);

            $user = $stmt->fetch();

            if ($user === false) {
                $errors['name'] = 'Username or password incorrect';
            } else {
                $passwordDB = $user['password'];

                if (password_verify($password, $passwordDB)) {

                    session_start();
                    $_SESSION['user_id'] = $user['id'];

                    header("Location: /catalog");
                    exit;
                } else {
                    $errors['password'] = 'Username or password incorrect';
                }

            }


            require_once './login_form.php';
        }
    }


    private function IsValidateLogin(array $data): array
    {
        $errors = [];

        if (!isset($data['name'])) {
            $errors['name'] = "Имя должно быть заполнено";
        }

        if (!isset($data['password'])) {
            $errors['password'] = "Пароль должен быть заполнен";
        }

        return $errors;
    }


    public function getProfile()
    {
        session_start();


        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');


            $stmt = $pdo->query('SELECT * FROM users WHERE id =' . $userId);
            $user = $stmt->fetch();

            require_once './profile/profile_page.php';
        } else {
            header("Location: /login");
        }
    }


    public function getProfileForm()
    {
        require_once './editProfile/edit_profile_form.php';
    }
    public function editProfile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($errors)) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $psw = $_POST['psw'];


            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
            if (!empty($psw)) {
                $passwordHash = password_hash($psw, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $passwordHash,
                    ':id' => $_SESSION['user_id']
                ]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':id' => $_SESSION['user_id']
                ]);
            }

            header("Location: /profile");
            exit;
        }

        return $errors;


    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
    $errors = $this->handleProfileUpdate($_POST);
    }


$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
require_once './editProfile/edit_profile_form.php';
}

function handleProfileUpdate()
{

    $errors = [];

    // Проверка имени
    if (isset($data['name'])) {
        $name = trim($data['name']);
        if (strlen($name) <= 2) {
            $errors['name'] = "Имя должно быть больше 2 символов";
        }
    } else {
        $errors['name'] = "Имя должно быть заполнено";
    }

    // Проверка email
    if (isset($data['email'])) {
        $email = trim($data['email']);

        if (strlen($email) <= 2) {
            $errors['email'] = "Email должен быть больше 2 символов";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Некорректный формат email";
        } else {
            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email AND id != :id");
            $stmt->execute([':email' => $email, ':id' => $userId]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $errors['email'] = "Пользователь с таким email уже существует";
            }
        }
    } else {
        $errors['email'] = "Email должен быть заполнен";
    }

    // Проверка пароля
    if (!empty($data['psw'])) {
        $psw = $data['psw'];
        $pswRepeat = $data['psw-repeat'] ?? '';

        if (strlen($psw) <= 2) {
            $errors['psw'] = "Пароль должен быть больше 2 символов";
        } elseif ($psw !== $pswRepeat) {
            $errors['psw-repeat'] = "Пароли не совпадают";
        }
    }

    return $errors;
}











}

