<?php

namespace Controller\Base;

class Auth {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function login($username, $password) {
        $user = $this->db->select('users', ['username' => $username]);
        if (!empty($user)) {
            if (password_verify($password, $user[0]['password'])) {
                session_start();
                $_SESSION['user'] = $user;
                return true;
            }
        }
        return false;
    }

    public function logout() {
        // Destroy session or invalidate authentication token
    }

    public function register($username, $password, $email) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $data = ['username' => $username, 'password' => $hashedPassword, 'email' => $email];
        return $this->db->insert('users', $data);
    }

    public function resetPassword($username, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $data = ['password' => $hashedPassword];
        $where = ['username' => $username];
        return $this->db->update('users', $data, $where);
    }

    public function get($id) {
        $user = $this->db->select('users', ['id' => $id]);
        if (!empty($user)) {
            return (object)$user;
        }
        return false;
    }

    public function user() {
        $user = [];
        if(!empty($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }
        return (object)$user;
    }
}
