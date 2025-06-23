<?php
namespace Controllers;

use Model\User;

class UserController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    public function getRegistrate()
    {
        require_once '../Views/registration_form.php';
    }

    public function registrate()
    {
        $errors = $this->validateRegistrationForm($_POST);

        if (empty($errors)) {
            $name = $_POST["name"];
            $email = $_POST["email"];
            $psw = $_POST["psw"];
            $passwordHash = password_hash($psw, PASSWORD_DEFAULT);

            $this->userModel->addUser($name, $email, $passwordHash);
            $result = $this->userModel->getByEmail($email);

            print_r($result);
        } else {
            print_r($errors);
        }

        require_once '../Views/registration_form.php';
    }

    private function validateRegistrationForm(array $data): array
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
                $errors['email'] = "Email должен быть больше 2 символов";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email некорректный";
            } else {
                $user = $this->userModel->getByEmail($email);
                if ($user !== null) {
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

            $pswRepeat = $data['psw-repeat'] ?? '';
            if ($psw !== $pswRepeat) {
                $errors['psw-repeat'] = "Пароли не совпадают";
            }
        } else {
            $errors['psw'] = "Пароль должен быть заполнен";
        }

        return $errors;
    }

    public function getLogin()
    {
        require_once '../Views/login_form.php';
    }

    public function login()
    {
        $errors = $this->validateLoginForm($_POST);

        if (empty($errors)) {
            $result = $this->auth($_POST['email'], $_POST['password']);

            if ($result === true) {
                header("Location: /catalog");
                exit;
            } else {
                $errors['login'] = 'Имя пользователя или пароль неверны';
            }
        }

        require_once '../Views/login_form.php';
    }

    private function validateLoginForm(array $data): array
    {
        $errors = [];

        if (empty($data['email'])) {
            $errors['email'] = "Email должен быть заполнен";
        }

        if (empty($data['password'])) {
            $errors['password'] = "Пароль должен быть заполнен";
        }

        return $errors;
    }

    public function getProfile()
    {
        if ($this->check()) {
            $userId = $this->getCurrentUserId();
            $user = $this->userModel->getUserById($userId);

            require_once '../Views/profile_page.php';
        } else {
            header("Location: /login");
            exit;
        }
    }

    public function getProfileForm()
    {
        require_once '../Views/edit_profile_form.php';
    }

    public function editProfile()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateProfileUpdate($_POST);

            if (empty($errors)) {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $userId = $_SESSION['user_id'];

                $user = $this->userModel->getUserById($userId);

                if ($user->getName() !== $name) {
                    $this->userModel->updateNameById($name, $userId);
                }

                if ($user->getEmail() !== $email) {
                    $this->userModel->updateEmailById($email, $userId);
                }

                if (!empty($_POST['psw'])) {
                    $passwordHash = password_hash($_POST['psw'], PASSWORD_DEFAULT);
                    $this->userModel->updatePasswordById($passwordHash, $userId);
                }

                header("Location: /profile");
                exit;
            }
        }

        require_once '../Views/edit_profile_form.php';
    }

    private function validateProfileUpdate(array $data): array
    {
        $errors = [];

        if (isset($data['name'])) {
            $name = $data['name'];
            if (strlen($name) <= 2) {
                $errors['name'] = "Имя должно быть больше 2 символов";
            }
        } else {
            $errors['name'] = "Имя должно быть заполнено";
        }

        if (isset($data['email'])) {
            $email = $data['email'];
            if (strlen($email) <= 2) {
                $errors['email'] = "Email должен быть больше 2 символов";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Некорректный формат email";
            } else {
                session_start();
                $existingUser = $this->userModel->getByEmail($email);
                $userId = $_SESSION['user_id'];

                if ($existingUser && $existingUser->getId() !== $userId) {
                    $errors['email'] = "Пользователь с таким email уже существует";
                }
            }
        } else {
            $errors['email'] = "Email должен быть заполнен";
        }

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

    public function getLogout()
    {
        session_start();
        $_SESSION = [];
        session_destroy();
        header("Location: /login");
        exit;
    }
}
