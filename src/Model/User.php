<?php

require_once 'Model.php';

class User extends Model
{

public function getByEmail(string $email): array|false
  {


    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email;");
    $stmt->execute([':email' => $email]);

    $result = $stmt->fetch();
    return $result;

  }


  public function updateEmailById(string $email, int $userId)
  {



      $stmt = $this->pdo->prepare("UPDATE users SET  email = :email WHERE id = $userId");
      $stmt->execute([':email' => $email ]);
  }

  public function updateNameById(string $name, int $userId)
  {


      $stmt = $this->pdo->prepare("UPDATE users SET name = :name WHERE id = $userId");
      $stmt->execute ([':name' => $name,]);
  }

  public function getUserById(int $userId)
  {
      $stmt = $this->pdo->query('SELECT * FROM users WHERE id =' . $userId);
      $user = $stmt->fetch();

      return $user;

  }

  public function getUserByName(string $name)
  {
      $stmt = $this->pdo->prepare('SELECT * FROM users WHERE name = :name');
      $stmt->execute(['name' => $name]);

      $user = $stmt->fetch();
      return $user;
  }

  public function addUser(string $name, string $email, string $passwordHash)
  {


      $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :psw)");
      $stmt->execute([':name' => $name, ':email' => $email, ':psw' => $passwordHash]);

  }
}
