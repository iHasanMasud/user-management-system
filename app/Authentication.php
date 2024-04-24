<?php
// app/Authentication.php
namespace App;

use App\UserManager;

class Authentication
{
    private $userManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
    }

    public function login($username, $password)
    {
        $user = $this->userManager->getUserByUsernameOrEmail($username, $username);
        if ($user && password_verify($password, $user->getPassword())) {
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['role'] = $user->getRole();
            return true;
        }
        return false;
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public function isUser()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
    }

    public function getUser()
    {
        if ($this->isLoggedIn()) {
            return $this->userManager->getUserById($_SESSION['user_id']);
        }
        return null;
    }
}
