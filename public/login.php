<?php
// Public/login
session_start();
require_once '../vendor/autoload.php';

// Verificar si el usuario está conectado
if (isset($_SESSION['user_id'])) {
    // Redirigir al dashboard
    echo "<script type='text/javascript'> alert('Ya cuentas con una sesión iniciada. Serás enviado al dashboard!'); window.location.href = '../app/views/dashboard';</script>";
    exit();
}

use App\Controllers\UserController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['usuario']; // Puede ser usuario o email
    $password = $_POST['password'];
    $recordarme = isset($_POST['recordarme']);

    $controller = new UserController();
    if ($controller->login($login, $password)) {
        $_SESSION['usuario'] = $login;

        if ($recordarme) {
            // Generar un token único
            $token = bin2hex(random_bytes(16));
            // Establecer una cookie que expire en 30 días
            setcookie('recordarme', $token, time() + (86400 * 30), "/");

            // Guardar el token en la base de datos asociado con el usuario
            $controller->rememberUser($login, $token);
        }

        header('Location: ../app/views/dashboard');
        exit();
    } else {
        echo "<script type='text/javascript'> alert('Usuario o contraseña incorrectos!'); window.location.href = 'login';</script>";
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

    <title>Nellys Deluquez - Login</title>

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

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row justify-content-center align-items-center">
                            <div class="col-lg-6 d-none d-lg-block text-center">
                                <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" src="../img/logo-min.png">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bienvenido!</h1>
                                    </div>
                                    <form class="user text-center" action="login" method="post" id="LoginForm">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-solid"
                                                id="exampleInputEmail" name="usuario" aria-describedby="emailHelp"
                                                placeholder="Correo electrónico" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-solid"
                                                id="exampleInputPassword" name="password" placeholder="Contraseña" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck" name="recordarme" value="1">
                                                <label class="custom-control-label" for="customCheck">Recordarme</label>
                                            </div>
                                        </div>
                                        <a class="btn btn-danger btn-icon-split" href="#" onclick="document.getElementById('LoginForm').submit()">
                                            <span class="icon text-white-50">
                                                    <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Iniciar sesión</span>
                                        </a>
                                    </form>
                                    <hr>
                                    <!-- <div class="text-center">
                                        <a class="small" href="forgot-password.html">Olvidaste tu contraseña?</a>
                                    </div> -->
                                    <div class="text-center">
                                        <a class="small" href="register">Crear una cuenta</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="search_order">Rastrear Orden</a>
                                    </div>
                                </div>
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