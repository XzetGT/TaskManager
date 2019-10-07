<?php

namespace Core;

/**
 * Auth Class
 *
 * @version 1.0.1
 */
class Auth
{
    
    public static function hashPassword($password)
    {
        return md5($password);
    }
    
    public static function checkPassword($userPassword, $dbPassword)
    {
        return self::hashPassword($userPassword) == $dbPassword;
    }
    
    public static function isLogged()
    {
        return array_key_exists('user', $_SESSION);
    }
    
    public static function login($user)
    {
        $_SESSION['user'] = $user['user_name'];
    }
    
    public static function logout()
    {
        $_SESSION = [];
        session_destroy();
    }
    
}