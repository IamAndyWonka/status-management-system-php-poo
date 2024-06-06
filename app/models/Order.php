<?php
// App/Models/Order.php
namespace App\Models;

use App\Config\Database;
use PDO;

class Order {
    private $conn;
    private $table = 'orders';

    public $id;
    public $order_number;
    public $client_id;
    public $status;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAllOrders() {
        $query = 'SELECT orders.*, clients.nombre AS client_name FROM orders JOIN clients ON orders.client_id = clients.id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($id) {
        $query = 'SELECT id, order_number, client_id, status_tracking FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateOrder($id, $order_number, $client_id, $status) {
        $query = 'UPDATE ' . $this->table . ' SET order_number = :order_number, client_id = :client_id, status_tracking = :status_tracking WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_number', $order_number);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':status_tracking', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteOrder($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function createOrder($order_number, $client_id, $status) {
        $stmt = $this->conn->prepare("INSERT INTO orders (order_number, client_id, status_tracking, created_at) VALUES (:order_number, :client_id, :status_tracking, :created_at)");
        
        $this->created_at = date('Y-m-d H:i:s');

        $stmt->bindParam(':order_number', $order_number);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':status_tracking', $status);
        $stmt->bindParam(':created_at', $this->created_at);
        return $stmt->execute();
    }

    public function countOrders() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as order_count FROM orders");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['order_count'];
    }

    public function orderNumberExists($order_number) {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM orders WHERE order_number = :order_number");
            $stmt->bindParam(':order_number', $order_number);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getOrderByNumber($order_number) {
        try {
            $stmt = $this->conn->prepare("SELECT orders.*, clients.nombre as client_name FROM orders JOIN clients ON orders.client_id = clients.id WHERE orders.order_number = :order_number");
            $stmt->bindParam(':order_number', $order_number);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getOrdersByDay() {
        $query = "SELECT DATE(created_at) as date, COUNT(*) as count FROM " . $this->table . " GROUP BY DATE(created_at) ORDER BY date";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
