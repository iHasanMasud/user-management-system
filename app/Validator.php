<?php
// app/Validator.php
namespace App;

class Validator
{
    public static function validateUsername($username)
    {
        // Validate username (e.g., minimum length, no special characters)
        return strlen($username) >= 3 && preg_match('/^[a-zA-Z0-9]+$/', $username);
    }

    public static function validateEmail($email)
    {
        // Validate email format
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validatePassword($password)
    {
        // Validate password (e.g., minimum length, at least one uppercase, one lowercase, and one number)
        return strlen($password) >= 8 && preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/[0-9]/', $password);
    }
}