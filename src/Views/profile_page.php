
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль пользователя</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .profile-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            font-size: 18px;
        }

        .profile-item strong {
            color: #555;
        }

        .profile-value {
            color: #333;
        }

        .edit-btn {
            text-align: center;
            margin-top: 30px;
        }

        .edit-btn a {
            background-color: #007bff;
            padding: 12px 25px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .edit-btn a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Личный кабинет</h1>
    <div class="profile-item"><strong>Имя</strong> <span class="profile-value"><?= ($user['name']) ?></span></div>
    <div class="profile-item"><strong>Email</strong> <span class="profile-value"><?= ($user['email']) ?></span></div>
    <div class="profile-item"><strong>ID пользователя</strong> <span class="profile-value"><?= (int)$user['id'] ?></span></div>

    <div class="edit-btn">
        <a href="/edit-profile">Редактировать профиль</a>
    </div>
</div>
</body>
</html>
