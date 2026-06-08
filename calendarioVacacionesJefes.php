<?php
include 'conn.php';
if ($_COOKIE['noEmpleado'] == '' || $_COOKIE['noEmpleado'] == null) {
    echo '<script>window.location.assign("index")</script>';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE = edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1, shrink-to-fit = no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RR HH</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/bootstrap5/css/bootstrap.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include 'menu.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include 'encabezado.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- encabezado.php (load handler) escribe aquí el No. de empleado de la cookie -->
                    <input type="hidden" id="noEmpleado" name="noEmpleado">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Solicitudes</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="card shadow mb-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="container mt-5">
                                        <h1>Calendario de Vacaciones</h1>
                                        <div id="calendar"></div>
                                    </div>
                                </div>
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
                        <span>Copyright &copy; MESS <?php echo date("Y"); ?></span>
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

    <!-- Core JavaScript -->
    <script src="vendor/jquery/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 4 (vendor): necesario para el menú colapsable -->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <!-- FullCalendar (build global: incluye daygrid e interaction) -->
    <script src="vendor/fullcalendar/index.global.min.js"></script>

    <!-- Lógica compartida (calendario + solicitudes); el calendario corre solo si existe #calendar -->
    <script src="js/solicitudestatus.js"></script>
</body>

</html>
