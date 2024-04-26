<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

use App\UserManager;
use App\Authentication;

session_start();

$userManager = new UserManager();
$authentication = new Authentication();

// Handle routing and user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $result = $authentication->login($_POST['username'], $_POST['password']);
        if ($result) {
            $_SESSION['message'] = 'Login successful';
            // Check the role of the user and redirect accordingly
            if ($authentication->getUser()->getRole() === 'admin') {
                header('Location: ' . 'index.php?action=users');
            } else {
                header('Location: ' . 'index.php?action=profile');
            }
            exit;
        } else {
            $_SESSION['message'] = 'Login failed';
        }
    } elseif (isset($_POST['create'])) {
        $result = $userManager->createUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role'], $authentication->getUser());
        if ($result) {
            $_SESSION['message'] = 'User created successfully';
        } else {
            $_SESSION['message'] = 'User not created';
        }
    } elseif (isset($_POST['update'])) {
        $result = $userManager->updateUser($_POST['id'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['role']);
        if ($result) {
            $_SESSION['message'] = 'User updated successfully';
        } else {
            $_SESSION['message'] = 'User not updated';
        }
    } elseif (isset($_POST['update-profile'])) {
        $result = $userManager->updateProfile($_POST['id'], $_POST['username'], $_POST['email'], $_POST['password']);
        if ($result) {
            $_SESSION['message'] = 'Profile updated successfully';
            header('Location: ' . 'index.php');
            exit;
        } else {
            $_SESSION['message'] = 'Profile not updated';
        }
    } elseif (isset($_POST['delete'])) {
        $result = $userManager->deleteUser($_POST['id'], $authentication->getUser());
        if ($result) {
            $_SESSION['message'] = 'User deleted successfully';
            header('Location: ' . 'index.php');
            exit;
        } else {
            $_SESSION['message'] = 'User not deleted';
        }
    }
    exit;
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'logout':
            $authentication->logout();
            header('Location: index.php');
            exit;
        case 'create':
            $content = 'views/users/create.php';
            break;
        case 'users':
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $users = $userManager->getAllUsers($page);
            $content = 'views/users/index.php';
            break;
        case 'search':
            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
            $users = $userManager->searchUsers($keyword);
            $content = 'views/users/index.php';
            break;
        case 'edit':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if ($id) {
                $user = $userManager->getUserById($id);
                $content = 'views/users/edit.php';
            }
            break;
        case 'delete':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if ($id) {
                $result = $userManager->deleteUser($id, $authentication->getUser());
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $users = $userManager->getAllUsers($page);
                $content = 'views/users/index.php';
            }
            break;
        case 'profile':
            $user = $authentication->getUser();
            $content = 'views/users/profile.php';
            break;

        default:
            $content = 'views/404.php';
            break;
    }
}

// Render the appropriate view
if (!isset($content)) {
    if ($authentication->isLoggedIn()) {
        if ($authentication->isAdmin()) {
            $users = $userManager->getAllUsers();
            $content= 'views/users/index.php';
        } else {
            $user = $userManager->getUserById($_SESSION['user_id']);
            $content= 'views/users/profile.php';
        }
    } else {
        $content= 'views/login.php';
    }
}

require_once 'views/layout.php';