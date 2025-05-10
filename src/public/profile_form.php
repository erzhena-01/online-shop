<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль пользователя</title>
    <style>
        .container {
            padding: 20px;
            max-width: 600px;
            margin: auto;
            background-color: #f9f9f9;
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        .profile-item {
            margin: 10px 0;
        }
        .edit-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
        .edit-btn a {
            background-color: #04AA6D;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Профиль пользователя</h1>
    <div class="profile-item"><strong>Имя:</strong> <?= ($user['name']) ?></div>
    <div class="profile-item"><strong>Email:</strong> <?= ($user['email']) ?></div>
    <div class="profile-item"><strong>ID:</strong> <?= ($user['id']) ?></div>
    <div class="edit-btn">
        <a href="/edit_profile">Редактировать профиль</a>
    </div>
</div>
</body>
</html>
