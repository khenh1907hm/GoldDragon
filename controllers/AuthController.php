<?php
require_once '../models/User.php';
require_once '../config/database.php';

class AuthController {
    private $user;
    private $db;    public function __construct() {
        try {
            $database = Database::getInstance();
            $this->db = $database->connect();
            if (!$this->db) {
                throw new Exception('Database connection failed');
            }
            $this->user = new User($this->db);
        } catch (Exception $e) {
            error_log('AuthController initialization error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function login($username, $password) {
        try {
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Username and password are required';
                return false;
            }

            if ($this->user->findByUsername($username)) {
                if ($this->user->verifyPassword($password)) {
                    $_SESSION['user_id'] = $this->user->id;
                    $_SESSION['username'] = $this->user->username;
                    $_SESSION['token'] = bin2hex(random_bytes(32));
                    $_SESSION['last_activity'] = time();
                    return true;
                }
            }
            $_SESSION['error'] = 'Invalid username or password';
            return false;
        } catch (Exception $e) {
            $_SESSION['error'] = 'An error occurred during login';
            return false;
        }
    }

    public function logout() {
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        session_destroy();
        return true;
    }

    public function isLoggedIn() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['token']) || !isset($_SESSION['last_activity'])) {
            return false;
        }

        // Check for session timeout (30 minutes)
        if (time() - $_SESSION['last_activity'] > 1800) {
            $this->logout();
            return false;
        }

        $_SESSION['last_activity'] = time();
        return true;
    }

    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username']
            ];
        }
        return null;
    }
}
