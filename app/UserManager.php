<?php

namespace App;

use App\Database;
use App\User;

class UserManager
{
    private $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function createUser($username, $email, $password, $role, $currentUser)
    {
        // Check if the current user is an admin
        if ($currentUser->getRole() != 'admin') {
            return false;
        }

        if (!Validator::validateUsername($username) || !Validator::validateEmail($email) || !Validator::validatePassword($password)) {
            return false;
        }
        // Check if username or email already exists
        $existingUser = $this->getUserByUsernameOrEmail($username, $email);
        if ($existingUser) {
            return false;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $sql = "INSERT INTO users (username, email, role, password) VALUES (:username, :email, :role, :password)";
        $params = [
            ':username' => $username,
            ':email' => $email,
            ':role' => $role,
            ':password' => $hashedPassword
        ];
        $this->database->query($sql, $params);

        return true;
    }

    public function updateUser($id, $username, $email, $password, $role)
    {
        // Check if new username or email already exists for a different user
        $existingUser = $this->getUserByUsernameOrEmail($username, $email);
        if ($existingUser && $existingUser->getId() != $id) {
            return false;
        }

        // Validate the username and email
        if (!Validator::validateUsername($username) || !Validator::validateEmail($email)) {
            return false;
        }

        // Initialize the params array
        $params = [
            ':id' => $id,
            ':username' => $username,
            ':email' => $email,
            ':role' => $role
        ];

        // Prepare the SQL query
        $sql = "UPDATE users SET username = :username, email = :email, role = :role";

        // If password is not empty, validate and update it
        if (!empty($password)) {
            if (!Validator::validatePassword($password)) {
                return false;
            }

            // Hash the new password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Add password to the SQL query and params array
            $sql .= ", password = :password";
            $params[':password'] = $hashedPassword;
        }

        $sql .= " WHERE id = :id";

        // Update the user in the database
        $this->database->query($sql, $params);

        return true;
    }

    public function updateProfile($id, $username, $email, $password)
    {
        // Check if new username or email already exists for a different user
        $existingUser = $this->getUserByUsernameOrEmail($username, $email);
        if ($existingUser && $existingUser->getId() != $id) {
            return false;
        }

        // Validate the username and email
        if (!Validator::validateUsername($username) || !Validator::validateEmail($email)) {
            return false;
        }

        // Initialize the params array
        $params = [
            ':id' => $id,
            ':username' => $username,
            ':email' => $email,
        ];

        // Prepare the SQL query
        $sql = "UPDATE users SET username = :username, email = :email";

        // If password is not empty, validate and update it
        if (!empty($password)) {
            if (!Validator::validatePassword($password)) {
                return false;
            }

            // Hash the new password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Add password to the SQL query and params array
            $sql .= ", password = :password";
            $params[':password'] = $hashedPassword;
        }

        $sql .= " WHERE id = :id";

        // Update the user in the database
        $this->database->query($sql, $params);

        return true;
    }

    public function deleteUser($id, $currentUser)
    {
        // If the current user is an admin
        if ($currentUser->getRole() === 'admin') {
            // Delete the user from the database
            $sql = "DELETE FROM users WHERE id = :id";
            $params = [':id' => $id];
            $this->database->query($sql, $params);
            return true;
        }
        // If the current user is not an admin but they're trying to delete their own account
        else if ($currentUser->getId() === $id) {
            // Delete the user from the database
            $sql = "DELETE FROM users WHERE id = :id";
            $params = [':id' => $id];
            $this->database->query($sql, $params);
            // Logout the user
            session_destroy();
            return true;
        }
        // If none of the above conditions are met, the user is not allowed to delete the account
        else {
            return false;
        }
    }

    public function getAllUsers($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM users LIMIT $offset, $perPage";
        $stmt = $this->database->query($sql);
        $users = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $users[] = new User($row['id'], $row['username'], $row['email'], $row['role'], $row['password']);
        }
        return $users;
    }

    public function getTotalUsers()
    {
        $sql = "SELECT COUNT(*) AS total FROM users";
        $stmt = $this->database->query($sql);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getTotalUsersByKeyword($keyword)
    {
        $query = "SELECT COUNT(*) AS total FROM users WHERE username LIKE :keyword OR email LIKE :keyword";
        $params = [':keyword' => '%' . $keyword . '%'];
        $stmt = $this->database->query($query, $params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Search users by username or email
    public function searchUsers($keyword)
    {
        $query = "SELECT * FROM users WHERE username LIKE :keyword OR email LIKE :keyword";
        $params = [':keyword' => '%' . $keyword . '%'];
        $stmt = $this->database->query($query, $params);
        $users = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $users[] = new User($row['id'], $row['username'], $row['email'], $row['role'], $row['password']);
        }
        return $users;
    }

    public function getUserByUsernameOrEmail($username, $email)
    {
        // Fetch a user by username or email
        $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
        $params = [':username' => $username, ':email' => $email];
        $stmt = $this->database->query($sql, $params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['id'], $row['username'], $row['email'], $row['role'], $row['password']);
        }
        return null;
    }

    public function getUserById($id)
    {
        // Fetch a user by ID
        $sql = "SELECT * FROM users WHERE id = :id";
        $params = [':id' => $id];
        $stmt = $this->database->query($sql, $params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['id'], $row['username'], $row['email'], $row['role'], $row['password']);
        }
        return null;
    }
}