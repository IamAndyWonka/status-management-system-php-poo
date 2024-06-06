<?php
// App/Controllers/UserController.php
namespace App\Controllers;

use App\Models\User;
use PDO;

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register($nombre, $email, $usuario, $password) {
        $user = new User();
        $user->nombre = $nombre;
        $user->email = $email;
        $user->usuario = $usuario;
        $user->password = $password;
        return $user->register();
    }

    public function login($usuario, $password) {
        $user = new User();
        $user->usuario = $usuario;
        $user->password = $password;
        if ($user->login()) {
        // Almacenar la ID del usuario en la sesión
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_role'] = $user->role;
        return true;
    }

    return false;
    }

    public function rememberUser($usuario, $token) {
        $user = new User();
        return $user->rememberUser($usuario, $token);
    }

    public function checkRememberedUser($token) {
        $user = new User();
        return $user->checkRememberedUser($token);
    }

    public function getAllUsers() {
        $user = new User();
        return $user->getAllUsers();
    }

    public function getUserById($id) {
        $user = new User();
        return $user->getUserById($id);
    }

    public function updateUser($id, $nombre, $email, $usuario) {
        $user = new User();
        return $user->updateUser($id, $nombre, $email, $usuario);
    }

    public function deleteUser($id) {
        $user = new User();
        return $user->deleteUser($id);
    }

    public function createUser($nombre, $email, $usuario, $password) {
        return $this->userModel->createUser($nombre, $email, $usuario, $password);
    }

    public function getUserCount() {
        return $this->userModel->countUsers();
    }

    public function changePassword($currentPassword, $newPassword) {
        if (!isset($_SESSION['user_id'])) {
            return false; // No hay usuario logueado
        }
    
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);
    
        if ($user && password_verify($currentPassword, $user['password'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            return $this->userModel->updatePassword($userId, $hashedPassword);
        }
    
        return false;
    }
}
?>
