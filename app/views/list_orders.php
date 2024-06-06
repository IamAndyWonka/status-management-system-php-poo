<?php
// app/views/list_orders
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\OrderController;
use App\Controllers\ClientController;
use App\Controllers\UserController;

if (!isset($_SESSION['usuario'])) {
    echo "<script type='text/javascript'> alert('Debes iniciar sesión para acceder a esta página.'); window.location.href = '../../public/login';</script>";
    exit();
}

// Instanciar el controlador de órdenes
$orderController = new OrderController();
$orders = $orderController->getAllOrders();

// Instanciar el controlador de clientes para obtener la lista de clientes
$clientController = new ClientController();
$clients = $clientController->getAllClients();

// Instanciar el controlador de usuarios para obtener la lista de clientes
$userController = new UserController();
$users = $userController->getAllUsers();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_order'])) {
        // Editar orden
        $id = $_POST['id'];
        $order_number = $_POST['order_number'];
        $client_id = $_POST['client_id'];
        $status = $_POST['status'];
        if ($orderController->updateOrder($id, $order_number, $client_id, $status)) {
            $_SESSION['success_message'] = 'Orden actualizada correctamente.';
        } else {
            $error = 'Error al actualizar la orden.';
        }
        header("Location: list_orders");
        exit();
    } elseif (isset($_POST['delete_order'])) {
        // Eliminar orden
        $id = $_POST['id'];
        if ($orderController->deleteOrder($id)) {
            $_SESSION['success_message'] = 'Orden eliminada correctamente.';
        } else {
            $error = 'Error al eliminar la orden.';
        }
        header("Location: list_users");
        exit();
    } elseif (isset($_POST['create_order'])) {
        // Crear nueva orden
        // $order_number = $_POST['order_number'];
        $client_id = $_POST['client_id'];
        $status = $_POST['status'];

        if ($orderController->createOrder($client_id, $status)) {
            $_SESSION['success_message'] = 'Orden creada correctamente.';
        } else {
            $error = 'Error al crear la orden.';
        }
        header("Location: list_orders");
        exit();
    } elseif (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            $error = 'Las nuevas contraseñas no coinciden.';
        }

        if ($userController->changePassword($currentPassword, $newPassword)) {
            $_SESSION['success_message'] ='Contraseña actualizada correctamente.';
        } else {
            $error ='Error al cambiar la contraseña. Verifique su contraseña actual.';
        }
        header("Location: list_orders");
        exit();
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

    <title>Nellys Deluquez - Ordenes</title>

    <!-- Custom fonts for this template -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-secondary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="bg-gradient-light sidebar-brand d-flex align-items-center justify-content-center" href="dashboard">
                <!-- <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div> -->
                <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" src="../../img/logo-min.png">
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard">
                <img class="img-profile" src="../../img/002-dashboard.png">
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="list_clients">
                    <!-- <i class="fas fa-fw fa-chart-area"></i> -->
                    <img class="img-profile" src="../../img/001-clientes.png">
                    <span>Clientes</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="list_orders">
                    <!-- <i class="fas fa-fw fa-table"></i> -->
                    <img class="img-profile" src="../../img/004-ordenes.png">
                    <span>Ordenes</span></a>
            </li>

             <!-- Nav Item - Tables -->
             <li class="nav-item">
                <a class="nav-link" href="list_users">
                    <!-- <i class="fas fa-fw fa-table"></i> -->
                    <img class="img-profile" src="../../img/002-usuarios.png">
                    <span>Usuarios</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <img class="img-profile" src="../../img/003-bars.png">
                        </button>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['usuario']; ?></span>
                                <img class="img-profile" src="../../img/001-admin.png">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePasswordModal">
                                    <img class="img-profile" src="../../img/001-contraseña.png">
                                    Cambiar Contraseña
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <img class="img-profile" src="../../img/002-logout.png">
                                    Salir
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div id="alert-container"></div>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Ordenes</h1>
                        <a class="d-none d-sm-inline-block btn btn-danger btn-icon-split shadow-sm" href="#" data-toggle="modal" data-target="#CreateModal" onclick="openCreateModal()">
                            <span class="icon text-white-50">
                                <img class="img-profile" src="../../img/002-nueva-orden.png">
                            </span>
                            <span class="text">Crear orden</span>
                        </a>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-danger">Lista de ordenes</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Numero de orden</th>
                                            <th>Cliente</th>
                                            <th>Status</th>
                                            <th>Fecha de Creación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Numero de orden</th>
                                            <th>Cliente</th>
                                            <th>Status</th>
                                            <th>Fecha de Creación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($order['client_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($order['status_tracking'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($order['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <a class="btn" data-toggle="modal" data-target="#EditModal" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($order)); ?>)"><img class="img-profile" src="../../img/001-editar.png"></a>
                                                    <a class="btn" data-toggle="modal" data-target="#DeleteModal" onclick="openDeleteModal(<?php echo htmlspecialchars(json_encode($order)); ?>)"><img class="img-profile" src="../../img/005-eliminar.png"></a>
                                                    <a class="btn btn-copy" data-order-number="<?php echo htmlspecialchars($order['order_number']); ?>"><img class="img-profile" src="../../img/001-copiar.png"></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Listo para salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Seleccione "Salir" a continuación si está listo para finalizar su sesión actual.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="../../public/logout">Salir</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal-->
    <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Orden</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="editForm" method="POST" action="list_orders">
                    <input type="hidden" name="id" id="editId">
                    <input type="hidden" name="edit_order" value="1"> <!-- Campo oculto para identificar la edición -->
                    <div class="mb-3 row">
                        <label for="editOrderNumber" class="col-sm-3 col-form-label">Numero de orden:</label>
                        <div class="col-sm-9">
                            <input class="form-control form-control-solid" name="order_number" id="editOrderNumber" type="text" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="editClientId" class="col-sm-3 col-form-label">Cliente:</label>
                        <div class="col-sm-9">
                            <select name="client_id" id="editClientId" class="form-control" required>
                                <?php foreach ($clients as $client): ?>
                                <option value="<?php echo htmlspecialchars($client['id']); ?>"><?php echo htmlspecialchars($client['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="editStatus" class="col-sm-3 col-form-label">Status:</label>
                        <div class="col-sm-9">
                            <select name="status" id="editStatus" class="form-control" required>
                                <option value="En recolección">En recolección</option>
                                <option value="En proceso">En proceso</option>
                                <option value="En camino a ser entregado">En camino a ser entregado</option>
                                <option value="Entregado">Entregado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="javascript:void(0);" onclick="submitEditForm()">Editar</a>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal-->
    <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar Orden</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">¿Estás seguro que deseas eliminar esta orden?</div>
                <form id="deleteForm" method="POST" action="list_orders">
                    <input type="hidden" name="id" id="deleteId">
                    <input type="hidden" name="delete_order" value="1"> <!-- Campo oculto para identificar la eliminación -->
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="javascript:void(0);" onclick="submitDeleteForm()">Eliminar</a>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Modal-->
    <div class="modal fade" id="CreateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Orden</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="createForm" method="POST" action="list_orders">
                    <input type="hidden" name="create_order" value="1"> <!-- Campo oculto para identificar la creación -->
                    <!-- <div class="mb-3 row">
                        <label for="createOrderNumber" class="col-sm-3 col-form-label">Número de Orden:</label>
                        <div class="col-sm-9">
                            <input class="form-control form-control-solid" name="order_number" id="createOrderNumber" type="text" required>
                        </div>
                    </div> -->
                    <div class="mb-3 row">
                        <label for="createClientId" class="col-sm-3 col-form-label">Cliente:</label>
                        <div class="col-sm-9">
                            <select name="client_id" id="createClientId" class="form-control" required>
                                <?php foreach ($clients as $client): ?>
                                <option value="<?php echo htmlspecialchars($client['id']); ?>"><?php echo htmlspecialchars($client['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="createStatus" class="col-sm-3 col-form-label">Status:</label>
                        <div class="col-sm-9">
                            <select name="status" id="createStatus" class="form-control" required>
                                <option value="En recolección">En recolección</option>
                                <option value="En proceso">En proceso</option>
                                <option value="En camino a ser entregado">En camino a ser entregado</option>
                                <option value="Entregado">Entregado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="javascript:void(0);" onclick="submitCreateForm()">Crear</a>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal-->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Cambiar contraseña</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="changePasswordForm" method="POST" action="list_users">
                    <input type="hidden" name="change_password" value="1"> <!-- Campo oculto para identificar la edición -->
                    <div class="mb-3 row">
                        <label for="current-password" class="col-sm-3 col-form-label">Contraseña Actual:</label>
                        <div class="col-sm-9">
                            <input class="form-control form-control-solid" id="current_password" name="current_password" type="password" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="new-password" class="col-sm-3 col-form-label">Nueva Contraseña:</label>
                        <div class="col-sm-9">
                            <input class="form-control form-control-solid" id="new_password" name="new_password" type="password" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="confirm-new-password" class="col-sm-3 col-form-label">Confirmar Nueva Contraseña:</label>
                        <div class="col-sm-9">
                            <input class="form-control form-control-solid" id="confirm_password" name="confirm_password" type="password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="javascript:void(0);" onclick="submitChangePassForm()">Actualizar Contraseña</a>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>

        function openCreateModal() {
            document.getElementById('createModal').style.display = 'block';
        }
        function submitCreateForm() {
            document.getElementById('createForm').submit();
        }

        function openEditModal(order) {
            document.getElementById('editId').value = order.id;
            document.getElementById('editOrderNumber').value = order.order_number;
            document.getElementById('editClientId').value = order.client_id;
            document.getElementById('editStatus').value = order.status_tracking;
            document.getElementById('editModal').style.display = 'block';
        }
        function submitEditForm() {
            document.getElementById('editForm').submit();
        }

        function openDeleteModal(order) {
            document.getElementById('deleteId').value = order.id;
            document.getElementById('deleteModal').style.display = 'block';
        }
        function submitDeleteForm() {
            document.getElementById('deleteForm').submit();
        }

        function submitChangePassForm() {
            document.getElementById('changePasswordForm').submit();
        }

        document.querySelectorAll('.btn-copy').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevenir el comportamiento por defecto del enlace
                const orderNumber = this.getAttribute('data-order-number');
                navigator.clipboard.writeText(orderNumber).then(() => {
                    showBootstrapAlert('Número de orden copiado al portapapeles: ' + orderNumber, 'success');
                }).catch(err => {
                    console.error('Error al copiar el número de orden: ', err);
                    showBootstrapAlert('Error al copiar el número de orden', 'danger');
                });
            });
        });

        // Función para mostrar alertas de Bootstrap
        function showBootstrapAlert(message, type) {
            var alertPlaceholder = document.getElementById('alert-container');
            var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                            message +
                            '</div>';
            alertPlaceholder.innerHTML = alertHtml;

            setTimeout(function() {
                alertPlaceholder.innerHTML = ''; // Limpiar el mensaje de alerta después de 5 segundos
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['success_message'])): ?>
                showBootstrapAlert('<?php echo $_SESSION['success_message']; ?>', 'success');
                <?php unset($_SESSION['success_message']); ?>
            <?php elseif (isset($_SESSION['error_message'])): ?>
                showBootstrapAlert('<?php echo $_SESSION['error_message']; ?>', 'danger');
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../js/demo/datatables-demo.js"></script>


</body>

</html>