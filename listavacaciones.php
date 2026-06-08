<?php include 'acceso_admin.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE = edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1, shrink-to-fit = no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RRHH</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/bootstrap5/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vendor/datatables/2.1.8/css/dataTables.dataTables.css" />
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
                        <div class="card shadow mb-2 w-100">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4 col-lg-3">
                                        <label for="filtroMes" class="mr-1 mb-0">Mes:</label>
                                        <input type="month" id="filtroMes" class="form-control form-control-sm" value="<?php echo date('Y-m'); ?>" onchange="recargarListas()">
                                    </div>
                                    <div class="col-auto d-flex align-items-end">
                                        <button class="btn btn-outline-secondary btn-sm" onclick="verTodo()"><i class="fas fa-list mr-1"></i>Ver todo</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active btn-outline-warning text-dark" type="button" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Por autorizar</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn-outline-success text-dark" id="autorizadas-tab" data-toggle="tab" href="#autorizadas" role="tab" aria-controls="autorizadas" aria-selected="false" onclick="llenaTablaAutorizadas()">Autorizadas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn-outline-danger text-dark" id="canceladas-tab" data-toggle="tab" href="#canceladas" role="tab" aria-controls="canceladas" aria-selected="false" onclick="llenaTablaCanceladas()">Canceladas</a>
                                        </li>
                                    </ul><br>

                                    <div class="tab-content" id="myTabContent">
                                        <!-- POR AUTORIZAR -->
                                        <br>
                                        <div class="tab-pane border-left-warning fade show active in" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <table class="table table-sm table-striped" id="TporAutorizar" name="TporAutorizar">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th scope="col" width="24%">Empleado</th>
                                                        <th scope="col" width="12%">T. de solicitud</th>
                                                        <th scope="col" width="12%">Fecha de sol</th>
                                                        <th scope="col" width="20%">No. días / Periodo</th>
                                                        <th scope="col" width="20%">Comentarios</th>
                                                        <th scope="col" width="8%">Estatus</th>
                                                        <th scope="col" width="2%">Acciones RH</th>
                                                        <th scope="col" width="2%">Aut. Jefe</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <!-- AUTORIZADAS -->
                                        <div class="tab-pane border-left-success fade" id="autorizadas" role="tabpanel" aria-labelledby="autorizadas-tab">
                                            <table class="table table-sm table-hover table-striped" id="Tautorizadas" name="Tautorizadas">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th scope="col" width="24%">Empleado</th>
                                                        <th scope="col" width="12%">T. de solicitud</th>
                                                        <th scope="col" width="12%">Fecha de sol</th>
                                                        <th scope="col" width="20%">No. días / Periodo</th>
                                                        <th scope="col" width="20%">Comentarios</th>
                                                        <th scope="col" width="8%">Estatus</th>
                                                        <th scope="col" width="4%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <!-- CANCELADAS / RECHAZADAS -->
                                        <div class="tab-pane border-left-danger fade" id="canceladas" role="tabpanel" aria-labelledby="canceladas-tab">
                                            <table class="table table-sm table-striped" id="Tcanceladas" name="Tcanceladas">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th scope="col">Empleado</th>
                                                        <th scope="col">T. de solicitud</th>
                                                        <th scope="col">Fecha de sol</th>
                                                        <th scope="col">No. días / Periodo</th>
                                                        <th scope="col">Comentarios</th>
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

    <!-- MODAL CAMBIO FECHA SOLICITUD -->
    <div class="modal fade" id="modalEdita" tabindex="-1" aria-labelledby="modalEditaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Autorizar</h4>
                    <button class="close btn-danger btn-sm" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditar">
                        <div class="modal-body">
                            <label id="NombreEmpleado" name="NombreEmpleado" class="fs-4 text-primary fw-bold"></label>
                            <br>
                            <input type="hidden" id="idSolicitudEdit" name="idSolicitud">
                            <input type="hidden" id="noEmpleadoEdit" name="noEmpleado">
                            F. Solicitud<input type="date" id="fechaSolicitudEdit" name="fechaSolicitud" class="form-control form-control-sm">
                            D. Gozados<input type="text" id="Dgozados" name="Dgozados" class="form-control form-control-sm">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onClick="guardarCambiosFeSolicitud()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL AUTORIZA -->
    <div class="modal fade show" id="modalAutoriza" tabindex="-1" role="dialog" aria-labelledby="modalAutoriza" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-left-primary">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Autorizar</h4>
                    <button class="close btn-danger" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form action="autorizar_incidencia.php" method="post">
                    <div class="modal-body">
                        <b>Comentarios</b>
                        <input type="hidden" id="idSolicitud" name="idSolicitud">
                        <input type="hidden" id="estatusSol" name="estatusSol">
                        <input type="hidden" id="estatusRH" name="estatusRH">
                        <input type="hidden" id="accion" name="accion">
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
    <!-- Bootstrap 4 (vendor): necesario para el menú colapsable, las pestañas y los modales (data-toggle) -->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/2.1.8/js/dataTables.js"></script>
    <script src="vendor/sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Lógica de la página -->
    <script src="js/listavacaciones.js"></script>
</body>

</html>
