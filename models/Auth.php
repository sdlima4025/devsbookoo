<?php
require_once 'dao/UserDaoMysql.php';
class Auth {
private $pdo;
private $base;

    public function __construct(PDO $pdo, $base) {
        $this->pdo = $pdo;
        $this->base = $base;
    }

    public function checkToken() {
        if(!empty($_SERVER['token'])) {
            $token = $_SESSION['token'];


            $userDao = new UserDaoMysql($this->pdo);
            $user = $userDao->findByToken($token);

            if($user) {
                return $user;
            }
        }

        header("Location: ".$this->base."/login.php");
        exit;
    }

    public function validateLogin($email, $password) {
        $userDao = new UserDaoMysql($this->pdo);

        $user = $userDao->findByEmail($email);
        if($user) {

            if(password_verify($password, $user->password)) {
                $token = md5(time().rand(0, 9999));

                $_SESSION['token'] = $token;
                $user->token = $token;
                $userDao->update($user);

                return true;

            }
        }

        return false;
    }

    public function emailExists($email) {
        $userDao = new UserDaoMysql($this->pdo);
            return $userDao->findByEmail($email) ? true : false;
    }

    public function registerUser($name, $email, $password, $birthdate) {
        $userDao = new UserDaoMysql($this->pdo);
            
    }

}