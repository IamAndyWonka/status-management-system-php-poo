<?php
// App/Models/User.php
namespace App\Models;

use App\Config\Database;
use PDO;

class User {
    private $conn;
    private $table = 'users';
    private $remember_table = 'user_tokens'; // Definición de la tabla remember_table

    public $id;
    public $nombre;
    public $email;
    public $usuario;
    public $password;
    public $role;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function register() {
        $query = 'INSERT INTO ' . $this->table . ' (nombre, email, usuario, password) VALUES (:nombre, :email, :usuario, :password)';
        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':usuario', $this->usuario);
        $stmt->bindParam(':password', $this->password);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login() {
        $query = 'SELECT id, nombre, email, usuario, password, role FROM ' . $this->table . ' WHERE usuario = :login OR email = :login';
        $stmt = $this->conn->prepare($query);

        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        $stmt->bindParam(':login', $this->usuario);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($this->password, $user['password'])) {
            $this->id = $user['id'];
            $this->nombre = $user['nombre'];
            $this->email = $user['email'];
            $this->usuario = $user['usuario'];
            $this->role = $user['role'];
            return true;
        }
        return false;
    }

    public function rememberUser($usuario, $token) {
        $query = 'INSERT INTO ' . $this->remember_table . ' (usuario, token) VALUES (:usuario, :token)
                  ON DUPLICATE KEY UPDATE token = VALUES(token)';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':token', $token);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function checkRememberedUser($token) {
        $query = 'SELECT usuario FROM ' . $this->remember_table . ' WHERE token = :token';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':token', $token);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['usuario'] : false;
    }

    public function getAllUsers() {
        $query = 'SELECT id, nombre, email, usuario FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $query = 'SELECT id, nombre, email, usuario, password FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $nombre, $email, $usuario) {
        $query = 'UPDATE ' . $this->table . ' SET nombre = :nombre, email = :email, usuario = :usuario WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function createUser($nombre, $email, $usuario, $password) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (nombre, email, usuario, password) VALUES (:nombre, :email, :usuario, :password)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':password', $passwordHash);
        return $stmt->execute();
    }

    public function countUsers() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as user_count FROM users");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['user_count'];
    }

    public function updatePassword($id, $hashedPassword) {
        $query = 'UPDATE ' . $this->table . ' SET password = :password WHERE id = :id';
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
}
?>
