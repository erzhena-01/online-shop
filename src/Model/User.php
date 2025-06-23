<?php
namespace Model;

require_once 'Model.php';

class User extends Model
{

    private int $id;
    private string $name;
    private string $email;
    private string $password;

    protected function getTableName(): string
    {
        return 'users';
    }

    public function getByEmail(string $email): ?self
  {


    $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE email = :email;");
    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch();

      if ($user === false) {
          return null;
      }

      $obj = new self();
      $obj->id = $user['id'];
      $obj->name = $user['name'];
      $obj->email = $user['email'];
      $obj->password = $user['password'];

      return $obj;
  }


  public function updateEmailById(string $email, int $userId)
  {



      $stmt = $this->pdo->prepare("UPDATE {$this->getTableName()} SET  email = :email WHERE id = $userId");
      $stmt->execute([':email' => $email ]);
  }

  public function updateNameById(string $name, int $userId)
  {


      $stmt = $this->pdo->prepare("UPDATE {$this->getTableName()} SET name = :name WHERE id = $userId");
      $stmt->execute ([':name' => $name,]);
  }

  public function getUserById(int $userId)
  {
      $stmt = $this->pdo->query("SELECT * FROM {$this->getTableName()} WHERE id =" . $userId);
      $user = $stmt->fetch();

      if($user === false){
          return null;
      }

      $obj = new self();
      $obj->id = $user['id'];
      $obj->name = $user['name'];
      $obj->email = $user['email'];
      $obj->password = $user['password'];

      return $obj;

  }

    public function updatePasswordById(string $passwordHash, int $userId)
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->getTableName()} SET password = :password WHERE id = :userId");
        $stmt->execute([':password' => $passwordHash, ':userId' => $userId]);
    }


    public function getUserByName(string $name): ?self
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE name = :name");
        $stmt->execute([':name' => $name]);

        $user = $stmt->fetch();

        if ($user === false) {
            return null;
        }

        $obj = new self();
        $obj->id = $user['id'];
        $obj->name = $user['name'];
        $obj->email = $user['email'];
        $obj->password = $user['password'];

        return $obj;
    }


    public function addUser(string $name, string $email, string $passwordHash)
  {


      $stmt = $this->pdo->prepare("INSERT INTO {$this->getTableName()} (name, email, password) VALUES (:name, :email, :psw)");
      $stmt->execute([':name' => $name, ':email' => $email, ':psw' => $passwordHash]);

  }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }


}
