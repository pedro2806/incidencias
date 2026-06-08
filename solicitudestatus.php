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
    <link href="vendor/datatables/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet">
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
                        <h1 class="h3 mb-0 text-gray-800">Mis solicitudes</h1>
                    </div>

                    <?php include 'conteo.php'; ?>

                    <!-- Content Row -->
                    <div class="card shadow mb-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-1"></div>
                                <div class="col-xl-10">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active btn-outline-warning" type="button" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Por autorizar</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn-outline-success" id="autorizadas-tab" data-toggle="tab" href="#autorizadas" role="tab" aria-controls="autorizadas" aria-selected="false">Autorizadas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn-outline-danger" id="canceladas-tab" data-toggle="tab" href="#canceladas" role="tab" aria-controls="canceladas" aria-selected="false">Canceladas</a>
                                        </li>
                                    </ul><br>
                                    <div class="tab-content" id="myTabContent">
                                        <!-- POR AUTORIZAR -->
                                        <div class="tab-pane fade show active in" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <table class="table table-sm table-striped" id="TporAutorizar" name="TporAutorizar">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">T/F Solicitud</th>
                                                        <th scope="col">No. días / Periodo</th>
                                                        <th scope="col">Comentarios</th>
                                                        <th scope="col">Estatus</th>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <!-- AUTORIZADAS -->
                                        <div class="tab-pane fade" id="autorizadas" role="tabpanel" aria-labelledby="autorizadas-tab">
                                            <table class="table table-sm table-striped" id="Tautorizadas" name="Tautorizadas">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">T. de solicitud</th>
                                                        <th scope="col">Fecha de solicitud</th>
                                                        <th scope="col">No. días</th>
                                                        <th scope="col">Periodo</th>
                                                        <th scope="col">Comentarios</th>
                                                        <th scope="col">Comentarios jefe</th>
                                                        <th scope="col">Estatus</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <!-- CANCELADAS / RECHAZADAS -->
                                        <div class="tab-pane fade" id="canceladas" role="tabpanel" aria-labelledby="canceladas-tab">
                                            <table class="table table-sm table-striped" id="Tcanceladas" name="Tcanceladas">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">T. de solicitud</th>
                                                        <th scope="col">Fecha de solicitud</th>
                                                        <th scope="col">No. días</th>
                                                        <th scope="col">Periodo</th>
                                                        <th scope="col">Comentarios</th>
                                                        <th scope="col">Comentarios jefe</th>
                                                        <th scope="col">Estatus</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
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

    <!-- Modal: movimiento de solicitud (cancelar) -->
    <div class="modal fade show" id="modalAutoriza" tabindex="-1" role="dialog" aria-labelledby="modalAutoriza" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-left-primary">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Movimiento solicitud</h4>
                    <button class="close btn-danger" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form action="autorizar_incidencia.php" method="post">
                    <div class="modal-body">
                        <b>Comentarios</b>
                        <input type="hidden" id="idSolicitud" name="idSolicitud">
                        <input type="hidden" id="estatusSol" name="estatusSol">
                        <textarea class="form-control" id="comentariosJefe" name="comentariosJefe" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" type="button" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-success">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Core JavaScript -->
    <script src="vendor/jquery/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 4 (vendor): necesario para el menú colapsable, las pestañas y el modal (data-toggle) -->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/1.13.2/js/jquery.dataTables.min.js"></script>

    <!-- Lógica de la página -->
    <script src="js/solicitudestatus.js"></script>
</body>

</html>
