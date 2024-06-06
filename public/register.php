<?php
// Public/register
require_once '../vendor/autoload.php';
include '../includes/middleware.php';
checkAdmin();

use App\Controllers\UserController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $controller = new UserController();
    if ($controller->register($nombre, $email, $usuario, $password)) {
        echo "<script type='text/javascript'> alert('Usuario registrado correctamente!'); window.location.href = 'login';</script>";
    } else {
        echo "<script type='text/javascript'> alert('Ocurrió un error registrando al usuario'); window.location.href = 'register';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Nellys Deluquez - Register</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-secondary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-5 d-none d-lg-block">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" src="../img/logo-min.png">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Crear una cuenta!</h1>
                            </div>
                            <form class="user text-center" action="register" method="post" id="RegisterForm">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-solid" id="exampleFirstName"
                                            placeholder="Nombre" name="nombre" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" class="form-control form-control-solid" id="exampleInputEmail"
                                        placeholder="Email" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-solid" id="exampleUsername"
                                            placeholder="Usuario" name="usuario" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-solid"
                                            id="exampleRepeatPassword" placeholder="Contraseña" name="password" required>
                                    </div>
                                </div>
                                <a class="btn btn-danger btn-icon-split" href="#" onclick="document.getElementById('RegisterForm').submit()">
                                    <span class="icon text-white-50">
                                            <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Registrar Usuario</span>
                                </a>
                            </form>
                            <hr>
                            <!-- <div class="text-center">
                                <a class="small" href="forgot-password.html">Olvidaste tu contraseña?</a>
                            </div> -->
                            <div class="text-center">
                                <a class="small" href="login">Tenes una cuenta? Inicia sesión!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>