<?php

namespace Controller;

use PDO;
use Constants\CssConstants;

class AuthController
{
    private $pdo;
    private $cssConstants;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->cssConstants = new CssConstants();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $this->pdo->prepare('SELECT * FROM credentials WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Start session and set user data
                session_start();
                $_SESSION['userId'] = $user['userId'];
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'];
                
                header('Location: /admin/dashboard'); // Redirect to admin dashboard
                exit;
            } else {
                $error = "Invalid username or password.";
                return [
                    'error' => $error,
                    'css' => $this->cssConstants, 
                    'currentPage' => 'login'
                ];
            }
        }
        
        // Render login view with error if exists
        return [
            'css' => $this->cssConstants, 
            'currentPage' => 'login'
        ];
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /admin/login'); // Redirect to login page
        exit;
    }
}
