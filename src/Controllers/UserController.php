<?php



class UserController
{

    public function getRegistrate()
    {
        require_once '../Views/registration_form.php';
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


            require_once '../Model/User.php';
            $userModel = new User();


            $userModel->addUser($name, $email, $passwordHash);

           $result = $userModel->getByEmail($email);


            print_r($result);

        } else {

            print_r($errors);

        }


        require_once '../Views/registration_form.php';

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

                require_once '../Model/User.php';
                $userModel = new User();
                $user = $userModel->getByEmail($email);

                if ($user !== false) {
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
        require_once '../Views/login_form.php';
    }

    public function login()
    {


        $errors = $this->IsValidateLogin($_POST);


        if (empty($errors)) {
            $name = $_POST['name'];
            $password = $_POST['password'];

            require_once '../Model/User.php';
            $userModel = new User();

            $user = $userModel->getUserByName($name);

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


            require_once '../Views/login_form.php';
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

            require_once '../Model/User.php';
            $userModel = new User();

            $user = $userModel->getUserById($userId);

            require_once '../Views/profile_page.php';
        } else {
            header("Location: /login");
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
            $errors = $this->handleProfileUpdate($_POST);
            if (empty($errors)) {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $userId = $_SESSION['user_id'];

                require_once '../Model/User.php';
                $userModel = new User();
                $user = $userModel->getUserById($userId);

                if ($user['name'] !== $name) {
                    $userModel->updateNameById($name, $userId);
                }

                if ($user['email'] !== $email) {
                    $userModel->updateEmailById($email, $userId);
                }

                if (!empty($_POST['psw'])) {
                    $passwordHash = password_hash($_POST['psw'], PASSWORD_DEFAULT);
                    $userModel->updatePasswordById($passwordHash, $userId);
                }

                header("Location: /profile");
                exit;
            }
        }

        require_once '../Views/edit_profile_form.php';
    }

    function handleProfileUpdate(array $data): array
    {

        $errors = [];

        // Проверка имени
        if (isset($data['name'])) {
            $name = ($data['name']);
            if (strlen($name) <= 2) {
                $errors['name'] = "Имя должно быть больше 2 символов";
            }
        } else {
            $errors['name'] = "Имя должно быть заполнено";
        }

        // Проверка email
        if (isset($data['email'])) {
            $email = ($data['email']);

            if (strlen($email) <= 2) {
                $errors['email'] = "Email должен быть больше 2 символов";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Некорректный формат email";
            } else {

                require_once '../Model/User.php';
                $userModel = new User();

                $user = $userModel->getByEmail($email);


                $userId = $_SESSION['user_id'];
                if ($user['id'] !== $userId) {
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

    public function getLogout()
    {

        session_start();

        $__SESSION = [];

        session_destroy();

        header("Location: /login");
        exit;
    }


}

