<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");

}

function handleProfileUpdate(array $data): array
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
            $errors['email'] = "email должен быть больше 2 символов";
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = "email некорректный";
        } else {
            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email AND id != :id");
            $stmt->execute([':email' => $email, ':id' => $_SESSION['user_id']]);
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

        $pswRepeat = $data['psw-repeat'];

        if ($psw !== $pswRepeat) {
            $errors['psw-repeat'] = "Пароль не соответствует";
        }
    } else {
        $psw = '';
    }


    if (empty($errors)) {


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
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = handleProfileUpdate($_POST);
}


$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
require_once './editProfile/edit_profile_form.php';
?>