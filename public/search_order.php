<?php
// Public/search_order
session_start();
require_once '../vendor/autoload.php';

use App\Controllers\OrderController;

$orderController = new OrderController();
$order = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search_order'])) {
        $order_number = $_POST['order_number'];
        $order = $orderController->searchOrder($order_number);
        if (!$order) {
            $error = "No se encontró la orden con el número $order_number.";
        }
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

    <title>Nellys Deluquez - Buscar Orden</title>

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
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4 w-75" src="../img/logo-min.png">
                                        <h1 class="h4 text-gray-900 mb-4">Rastrea tu Orden</h1>
                                    </div>
                                    <form class="user text-center" action="search_order" method="post" id="SearchForm">
                                        <div class="form-group">
                                            <label for="orderNumber" class="form-label">Número de Orden</label>
                                            <input type="text" class="form-control form-control-user text-center"
                                            name="order_number" id="orderNumber" required>
                                        </div>
                                        <a class="btn btn-danger btn-icon-split" href="#" onclick="document.getElementById('SearchForm').submit()">
                                            <span class="icon text-white-50">
                                                    <i class="fas fa-search"></i>
                                            </span>
                                            <span class="text">Buscar</span>
                                        </a>
                                        <input type="hidden" name="search_order" value="1">
                                    </form>
                                    <hr>
                                    <!-- Collapsable Card Example -->
                                    <div class="card shadow mb-4">
                                        <!-- Card Header - Accordion -->
                                        <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
                                            role="button" aria-expanded="true" aria-controls="collapseCardExample">
                                            <h6 class="m-0 font-weight-bold text-danger">Detalles de tu orden</h6>
                                        </a>
                                        <!-- Card Content - Collapse -->
                                        <div class="collapse show" id="collapseCardExample">
                                            <div class="card-body">
                                                <?php if ($order): ?>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="card mb-1 py-1 border-bottom-danger">
                                                                <div class="card-body">
                                                                    Número de Orden: <?php echo htmlspecialchars($order['order_number']); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="card mb-1 py-1 border-bottom-danger">
                                                                <div class="card-body">
                                                                    Cliente: <?php echo htmlspecialchars($order['client_name']); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card mt-5 mb-1 py-1 border-bottom-danger">
                                                        <div class="card-body">
                                                            Status: <?php echo htmlspecialchars($order['status_tracking']); ?>
                                                        </div>
                                                    </div>
                                                <?php elseif ($error): ?>
                                                    <div class="alert alert-danger">
                                                        <?php echo htmlspecialchars($error); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
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