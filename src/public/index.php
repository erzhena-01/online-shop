<?php
$name = $_POST["name"];
$email = $_POST["email"];
$psw = $_POST["psw"];
$pswRepeat = $_POST["psw-repeat"];


function IsValidateForm(): array
{
    $errors = [];



    if (isset($_POST["name"])) {
        $name = $_POST["name"];

        if (strlen($name) <= 2) {
            $errors['name'] = "Имя должно быть больше 2 символов";
        }
    } else {
        $errors["name"] = "Имя должно быть заполнено";
    }

    if (isset($_POST["email"])) {
        $email = $_POST["email"];

        if (strlen($email) <= 2) {
            $errors['email'] = "email должен быть больше 2 символов" . "\n";
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors ['email'] = "email некорректный" . "\n";
        }
    } else {
        $errors["email"] = "Email должен быть заполнен";
    }

    if (isset($_POST["psw"])) {
        $psw = $_POST["psw"];

        if (strlen($psw) <= 2) {
            $errors['psw'] = "Пароль должен быть больше 2 символов";
        }
    } else {
        $errors["psw"] = "Пароль должен быть заполнен";
    }

    if (isset($_POST["psw-repeat"])) {
        $pswRepeat = $_POST["psw-repeat"];

        if ($psw !== $pswRepeat) {
            $errors['psw-repeat'] = "Пароль не соответствует" . "\n";
        }
    } else {
        $errors["psw-repeat"] = "Пароль должен быть заполнен";
    }

    return [$errors, ['name' => $name, 'email' => $email, 'psw' => $psw]];

}

$errors = IsValidateForm();



if(empty($errors)) {

    $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');

    password_hush($psw, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :psw)");
    $stmt->execute([':name' => $name, ':email' => $email, ':psw' => $psw]);


    $result = $pdo->prepare("SELECT * FROM users WHERE email = :email;");
    $result->execute([':email' => $email]);
    $data = $result->fetch();
    print_r($data);

}else{

    print_r($errors);

}


require_once './registration_form.php';
?>





