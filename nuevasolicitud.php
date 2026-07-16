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
                    <form method="POST" class="bg-white p-3 rounded" style="border: 1px solid #e3e6f0;">
                        <!-- variables ocultas -->
                        <input type="hidden" id="noEmpleado" name="noEmpleado">
                        <input type="hidden" name="opIncidencia" id="opIncidencia" value="1">

                        <!-- SECCIÓN 1: Solicitante -->
                        <div class="row align-items-center mb-3 pb-2" style="border-bottom: 1px solid #eaecf4;">
                            <div class="col-md-2 col-sm-3 mb-1 mb-md-0">
                                <span class="text-muted small font-weight-bold text-uppercase" style="letter-spacing: 0.05rem;">Solicita:</span>
                            </div>
                            <div class="col-md-4 col-sm-9 mb-1 mb-md-0">
                                <h5 class="text-dark font-weight-bold m-0" id="NSolicita" name="NSolicita"></h5>
                                <div id="Divsolicita" name="Divsolicita" class="mt-1">
                                    <select id="solicita" name="solicita" class="form-control form-control-sm">
                                        <option value="">Selecciona colaborador...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 text-md-left">
                                <span class="text-primary small font-weight-normal bg-light p-1 px-2 rounded border">
                                    <i class="fas fa-info-circle mr-1"></i> Solicitudes para colaboradores a tu cargo.
                                </span>
                            </div>
                        </div>

                        <!-- SECCIÓN 2: Tipo de Incidencia -->
                        <div class="mb-4">
                            <label class="text-muted small font-weight-bold text-uppercase mb-1 d-block" style="letter-spacing: 0.05rem;">
                                Tipo de incidencia
                            </label>
                            
                            <div class="row no-gutters">
                                <!-- Opción: Vacaciones -->
                                <div class="col-lg-4 pr-lg-1 mb-1 mb-lg-0">
                                    <div class="card bg-light border-1 h-100">
                                        <div class="card-body p-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" name="ti1" id="ti1" onchange="tIncidencia(1);" value="1" required checked>
                                                <label class="custom-control-label text-dark font-weight-bold w-100 small" style="cursor: pointer;" for="ti1">
                                                    Vacaciones
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Opción: Permiso sin goce -->
                                <div class="col-lg-4 px-lg-1 mb-1 mb-lg-0">
                                    <div class="card bg-light border-1 h-100">
                                        <div class="card-body p-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" name="ti1" id="ti2" onchange="tIncidencia(2);" value="2" required>
                                                <label class="custom-control-label text-dark font-weight-bold w-100 small" style="cursor: pointer;" for="ti2">
                                                    Permiso sin goce
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Opción: Permiso con goce -->
                                <div class="col-lg-4 pl-lg-1">
                                    <div class="card bg-light border-1 h-100">
                                        <div class="card-body p-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" name="ti1" id="ti3" onchange="tIncidencia(3);" value="3" required>
                                                <label class="custom-control-label text-dark font-weight-bold w-100 small" style="cursor: pointer;" for="ti3">
                                                    Permiso con goce <span class="text-muted font-weight-normal font-italic">(Reglamento)</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- SECCIÓN 3: Fechas y Días -->
                        <div class="mb-3 pt-2" style="border-top: 1px solid #eaecf4;">
                            <label class="text-muted small font-weight-bold text-uppercase mb-2 d-block" style="letter-spacing: 0.05rem;">
                                Días fuera de jornada
                            </label>

                            <div class="row align-items-center">
                                <!-- Contenedor de renglones -->
                                <div class="col-lg-10 col-md-9 mb-0 mb-md-0">
                                    <div id="renglones-container">
                                        <!-- Renglón base bien espaciado -->
                                        <div class="row align-items-center bg-light p-2 rounded mb-2 border mx-0 item-row" id="renglon-1">
                                            <div class="col-sm-5 my-1">
                                                <input type="date" class="form-control form-control-sm bg-white" id="fechaInicial-1" name="fechaInicial[]" onchange="diasEntreFechas(1);" required>
                                            </div>
                                            <div class="col-sm-5 my-1">
                                                <input type="date" class="form-control form-control-sm bg-white" id="fechaFinal-1" name="fechaFinal[]" onchange="diasEntreFechas(1);" required>
                                            </div>
                                            <div class="col-sm-2 my-1 text-right d-flex align-items-center justify-content-end">
                                                <span class="small text-muted mr-2">Días:</span>
                                                <input type="number" class="form-control form-control-sm bg-transparent border-0 font-weight-bold p-0 text-center text-dark" id="noDias-1" name="noDias[]" readonly style="width: 40px; font-size: 1rem;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Controles laterales (Alineación corregida) -->
                                <div class="col-lg-2 col-md-3">
                                    <div class="btn-group btn-group-sm border rounded bg-white w-100">
                                        <button type="button" class="btn btn-light text-success border-0 py-2" onclick="agregarRenglon()" data-toggle="tooltip" title="Agregar Periodo">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-light text-danger border-0 py-2" onclick="eliminarUltimoRenglon()" data-toggle="tooltip" title="Remover Último">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 4: Notas y Comentarios -->
                        <div class="row mb-3 pt-1" style="border-top: 1px solid #eaecf4;">
                            <div class="col-md-6 mb-1 mb-md-0 pr-md-1">
                                <label for="notas" class="text-muted small font-weight-bold text-uppercase mb-1 d-block">Fechas no corridas / Excepciones</label>
                                <textarea class="form-control bg-light border-0 small p-2" id="notas" name="notas" rows="2" placeholder="Días intermedios no laborables..."></textarea>
                            </div>
                            <div class="col-md-6 pl-md-1">
                                <label for="comentarios" class="text-muted small font-weight-bold text-uppercase mb-1 d-block">Comentarios adicionales</label>
                                <textarea class="form-control bg-light border-0 small p-2" id="comentarios" name="comentarios" rows="2" placeholder="Motivo o aclaraciones..."></textarea>
                            </div>
                        </div>

                        <!-- SECCIÓN 5: Cierre / Envío -->
                        <div class="bg-light p-2 rounded text-center border mt-2">
                            <button id="btnSolicitar" type="button" class="btn btn-primary btn-sm px-4 font-weight-bold shadow-sm align-middle" onclick="generarSolicitud()">
                                Enviar Solicitud
                            </button>
                            <br>
                            <h6 class="text-dark m-0 d-inline-block align-middle mr-3">
                                Autoriza: <span class="font-weight-bold text-primary" id="autorizaJefe"></span>
                            </h6>
                            <br>
                            <span id="mensaje" class="text-light small ml-2 align-middle"></span>
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
