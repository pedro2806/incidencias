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
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family = Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php
            include 'menu.php';
        ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php
                    include '../encabezado.php';
                ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col-xl-12">
                            <div class="card shadow">
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row"> 
                                        <div class="col-xl-4" style="text-align: center">
                                            <img class="sidebar-card-illustration" src="../img/MESS_05_Imagotipo_1.png" width="160">
                                        </div>
                                        <div class="col-xl-4">
                                            <center>
                                                <p class="fs-4"><b>REGISTRO DE INCIDENCIAS</b></p>
                                            </center>
                                        </div>
                                        <div class="col-xl-4" style="text-align: center">
                                            <b>Fecha: </b>
                                            <b>
                                                <?php
                                                    print_r(date("d-m-Y")); 
                                                ?>
                                            </b>
                                        </div>
                                    </div>
                                    <form method="POST">
                                        <div class="row card-footer border-left-primary">
                                            <div class="col-sm-4 mb-0">
                                                <label for="dirigida">Responsable</label>
                                                <div id="Divsolicita" name="Divsolicita">
                                                    <select id="slcRespoonsable" name="slcRespoonsable" class="form-select">
                                                        <option value="">Selecciona...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-0">
                                                <label for="Tipo">Tipo</label>
                                                <select id="TIncidencia" name="TIncidencia" class="form-select">
                                                    <option value="">Selecciona...</option>
                                                    <option value="Operaciones">Operaciónes</option>
                                                    <option value="Personal">Personal</option>
                                                    <option value="Calidad">Calidad</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4 mb-0">
                                                <label for="Area">Clasificación</label>
                                                <select id="area" name="area" class="form-select">
                                                    <option value="">Selecciona...</option>
                                                </select>
                                            </div>
                                            
                                        </div>

                                        <div class="row card-footer border-left-primary">
                                            <div class="col-sm-4 mb-0">
                                                <label for="dirigida">Fecha incidente</label>
                                                <input type="date" class="form-control" id="fechaIncidente" name="fechaIncidente">
                                            </div>
                                            <div class="col-sm-4 mb-0">
                                                <label for="Tipo">Fecha de cierre</label>
                                                <input type="date" class="form-control" id="fechaCierre" name="fechaCierre">
                                            </div>                                            
                                        </div>

                                        <div class="row card-header border-left-primary">                                           
                                            <div class="col-xl-12">
                                                <div class="mb-0">
                                                    <label for="exampleFormControlTextarea1" class="form-label">Comentarios</label>
                                                    <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row  card-header border-left-success">
                                            <div class="col-xl-3"></div>
                                            <div class="col-xl-6">
                                                <center>
                                                    <button id="btnSolicitar" type="button" class="btn btn-success" onclick="generarSolicitud()">Registrar</button><br>
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
                        <span>Copyright &copy; MESS 2025</span>
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

    <!-- Bootstrap core JavaScript
    <script src = "vendor/jquery/jquery.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" defer="defer"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            
        });

        var tIncidencia = function(ti) {
            $('#opIncidencia').val(ti);
        }

        function generarSolicitud() {
            diasDisp = $('#diasDisponibles').val();
            if (diasDisp < 1) {
                Swal.fire({
                    title: "No tienes días disponibles para solicitar.",
                    icon: "warning",
                    draggable: true
                });
                return;
            }

            opIncidencia = $('#opIncidencia').val();
            notas = $('#notas').val();
            comentarios = $('#comentarios').val();
            solicita = $('#solicita').val();
            cuantos = renglonCounter;
            accion = 'agregaSolicitud';
            var periodos = [];

            $('.dynamic-row .row').each(function(index, row) {
                var fechaInicial = $(row).find('input[name="fechaInicial[]"]').val();
                var fechaFinal = $(row).find('input[name="fechaFinal[]"]').val();
                var noDias = $(row).find('input[name="noDias[]"]').val();

                periodos.push({
                    fechaInicial: fechaInicial,
                    fechaFinal: fechaFinal,
                    noDias: noDias
                });
            });

            if (opIncidencia == '' || opIncidencia == null) {
                Swal.fire({
                    title: "Es necesario seleccionar el tipo de incidencia!",
                    icon: "warning",
                    draggable: true
                });
            } else {
                $.ajax({
                    url: 'procesar_solicitud.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        opIncidencia,
                        notas,
                        comentarios,
                        solicita,
                        cuantos,
                        accion,
                        periodos
                    },
                    success: function(data) {
                        Swal.fire({
                            title: "La solicitúd se proceso son éxito!",
                            icon: "success",
                            draggable: true
                        });
                        enviaNotificacion(solicita);
                        window.location.href = 'solicitudestatus';
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: "La solicitúd no se pudo procesar!",
                            icon: "error",
                            draggable: true
                        });
                    }
                });
            }
        }

        function enviaNotificacion() {
            $.ajax({
                url: 'enviaNotificacion.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    solicita
                },
                success: function(data) {
                    // Notificación opcional
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Error opcional
                }
            });
        }

        function getCookie(name) {
            let value = "; " + document.cookie;
            let parts = value.split("; " + name + "=");
            if (parts.length === 2) return parts.pop().split(";").shift();
        }
    </script>
</body>

</html>
