<?php
// Public/logout.php
session_start();
require_once '../vendor/autoload.php';

use App\Controllers\UserController;

if (isset($_SESSION['usuario'])) {
    // Limpiar la cookie y la base de datos
    if (isset($_COOKIE['recordarme'])) {
        $controller = new UserController();
        $controller->rememberUser($_SESSION['usuario'], '');
        setcookie('recordarme', '', time() - 3600, "/");
    }
    session_destroy();
}
header('Location: login.php');
exit();
?>
