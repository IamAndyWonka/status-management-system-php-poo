<?php
// middleware.php
session_start();

function checkAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        echo "<script type='text/javascript'> alert('Usted no tiene permisos para visualizar esta página!'); window.location.href = 'login';</script>";
        exit();
    }
}
?>
