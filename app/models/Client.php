<?php
// App/Models/Clients.php
namespace App\Models;

use App\Config\Database;
use PDO;

class Client {
    private $conn;
    private $table = 'clients';

    public $id;
    public $nombre;
    public $email;
    public $usuario;
    public $telefono;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAllClients() {
        $query = 'SELECT id, nombre, email, telefono FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClientById($id) {
        $query = 'SELECT id, nombre, email, telefono FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateClient($id, $nombre, $email, $telefono) {
        $query = 'UPDATE ' . $this->table . ' SET nombre = :nombre, email = :email, telefono = :telefono WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteClient($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function createClient($nombre, $email, $telefono) {
        $stmt = $this->conn->prepare("INSERT INTO clients (nombre, email, telefono) VALUES (:nombre, :email, :telefono)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        return $stmt->execute();
    }

    public function countClients() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as client_count FROM clients");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['client_count'];
    }
}
?>
