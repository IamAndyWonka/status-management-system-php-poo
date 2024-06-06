<?php
// App/Controllers/ClientController.php
namespace App\Controllers;

use App\Models\Client;
use PDO;

class ClientController {
    private $clientModel;

    public function __construct() {
        $this->clientModel = new Client();
    }

    public function getAllClients() {
        $client = new Client();
        return $client->getAllClients();
    }

    public function getClientById($id) {
        $client = new Client();
        return $client->getClientById($id);
    }

    public function updateClient($id, $nombre, $email, $telefono) {
        $client = new Client();
        return $client->updateClient($id, $nombre, $email, $telefono);
    }

    public function deleteClient($id) {
        $client = new Client();
        return $client->deleteClient($id);
    }

    public function createClient($nombre, $email, $telefono) {
        return $this->clientModel->createClient($nombre, $email, $telefono);
    }

    public function getClientCount() {
        return $this->clientModel->countClients();
    }
}
?>
