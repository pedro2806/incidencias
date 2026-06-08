<!DOCTYPE html>
<html>

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
    <!-- Select2 CSS -->
    <link href="vendor/select2/css/select2.min.css" rel="stylesheet" />

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

                    <?php include 'conteo.php'; ?>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12">
                            <div class="card shadow">
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: center">
                                            <img class="sidebar-card-illustration" src="img/MESS_05_Imagotipo_1.png" width="150">
                                        </div>
                                        <div class="col-xl-4">
                                            <center>
                                                <p class="fs-5"><b>SOLICITUD DE VACACIONES</b></p>
                                            </center>
                                        </div>
                                        <div class="col-xl-4" style="text-align: center">
                                            <b>Recursos Humanos - </b>
                                            <b><?php echo date("d-m-Y"); ?></b>
                                        </div>
                                    </div>

                                    <form method="POST">
                                        <!-- encabezado.php (load handler) escribe aquí el No. de empleado de la cookie -->
                                        <input type="hidden" id="noEmpleado" name="noEmpleado">
                                        <div class="row card-footer border-left-primary">
                                            <div class="col-sm-1 mb-0">
                                                <b>Solicita:</b>
                                            </div>
                                            <div class="col-sm-3 mb-0">
                                                <h5 class="text-primary" id="NSolicita" name="NSolicita"></h5>
                                            </div>
                                            <div class="col-sm-4 mb-0">
                                                <div id="Divsolicita" name="Divsolicita">
                                                    <select id="solicita" name="solicita" class="form-select">
                                                        <option value="">Selecciona...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div id="DivsolicitaMss" name="DivsolicitaMss">
                                                    <div class="badge bg-primary text-white" role="alert" style="font-size: 12px;">
                                                        Puedes solicitar vacaciones para los colaboradores a tu cargo.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row card-footer border-left-primary">
                                            <div class="col-xl-8 mb-0">
                                                <b>Por favor selecciona que tipo de incidencia es la que se autorizará:</b>
                                            </div>
                                        </div>

                                        <div class="row card-footer border-left-primary">
                                            <div class="col-xl-1"></div>

                                            <!-- Primera opción: Vacaciones -->
                                            <div class="col-xl-3" style="box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 8px; background-color: #ecebeb;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ti1" id="ti1" onchange="tIncidencia(1);" value="1" required checked>
                                                    <label class="form-check-label" for="ti1">
                                                        <b>Vacaciones</b>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Segunda opción: Permiso sin goce -->
                                            <div class="col-xl-3" style="box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 8px; background-color: #ecebeb;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ti1" id="ti2" onchange="tIncidencia(2);" value="2" required>
                                                    <label class="form-check-label" for="ti2">
                                                        <b>Permiso sin goce</b>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Tercera opción: Permiso con goce -->
                                            <div class="col-xl-4" style="box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 8px; background-color: #ecebeb;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ti1" id="ti3" onchange="tIncidencia(3);" value="3" required>
                                                    <label class="form-check-label" for="ti3">
                                                        <b>Permiso con goce. (ver anexo: Reglamento)</b>
                                                        <input type="hidden" name="opIncidencia" id="opIncidencia" value="1">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row card-footer border-left-primary">
                                            <div class="col-xl-12"><br>
                                                <b>Favor de tomar nota de los días que el empleado estará fuera de Jornada Laboral (aplica para permiso sin goce, con goce y vacaciones):</b>
                                            </div>
                                        </div>

                                        <div class="row card-footer border-left-primary dynamic-row">
                                            <div class="col-sm-10">
                                                <div id="renglones-container">
                                                    <!-- Renglón inicial que no se puede eliminar -->
                                                    <div class="row" id="renglon-1">
                                                        <div class="col-sm-1"></div>
                                                        <div class="col-sm-4">
                                                            <div class="mb-1">
                                                                <label for="fechaInicial-1" class="form-label">Fecha de inicio</label>
                                                                <input type="date" class="form-control" id="fechaInicial-1" name="fechaInicial[]" onchange="diasEntreFechas(1);" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="mb-1">
                                                                <label for="fechaFinal-1" class="form-label">Fecha de término</label>
                                                                <input type="date" class="form-control" id="fechaFinal-1" name="fechaFinal[]" onchange="diasEntreFechas(1);" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="mb-1">
                                                                <label for="noDias-1" class="form-label">No de días</label>
                                                                <input type="number" class="form-control" id="noDias-1" name="noDias[]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="mb-1">
                                                    <label class="form-label">Opciones</label><br>
                                                    <button type="button" class="btn btn-success" onclick="agregarRenglon()"><i class="fas fa-plus"></i></button>
                                                    <button type="button" class="btn btn-danger" onclick="eliminarUltimoRenglon()"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                            <br>
                                        </div>

                                        <div class="row card-header border-left-primary">
                                            <div class="col-xl-6">
                                                <div class="mb-0">
                                                    <b class="form-label">Indicar fechas cuando los días no sean un periodo corrido</b>
                                                    <textarea class="form-control" id="notas" name="notas" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="mb-0">
                                                    <b class="form-label">Comentarios</b>
                                                    <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row card-header border-left-success">
                                            <div class="col-xl-3"></div>
                                            <div class="col-xl-6">
                                                <center>
                                                    <h4 class="text-success text-xl">Autoriza: <span id="autorizaJefe"></span></h4><br>
                                                    <button id="btnSolicitar" type="button" class="btn btn-success" onclick="generarSolicitud()">Solicitar</button><br>
                                                    <p id="mensaje" class="badge text-bg-primary"></p>
                                                </center>
                                            </div>
                                        </div>
                                    </form>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cerrar sesión</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">¿Estas seguro?</div>
                <div class="modal-footer">
                    <button class="btn btn-info" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-danger" href="logout">Salir</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery-3.7.1.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script src="vendor/datatables/1.10.8/js/jquery.dataTables.min.js" defer="defer"></script>
    <!-- Select2 JS -->
    <script src="vendor/select2/js/select2.min.js"></script>
    <script src="vendor/sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Lógica de la página -->
    <script src="js/nuevasolicitud.js"></script>
</body>

</html>
