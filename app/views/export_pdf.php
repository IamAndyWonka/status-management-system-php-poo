<?php
// Views/export_excel
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\OrderController;

$orderController = new OrderController();
$orderController->exportOrdersToPDF();
?>