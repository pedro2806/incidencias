<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE = edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1, shrink-to-fit = no">
    <title>RR HH</title>

 <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">    

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.dataTables.min.css" rel="stylesheet" />
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
                    include 'encabezado.php';
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
                                                <p class="fs-4"><b>SEGUIMIENTO DE INCIDENCIAS</b></p>
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
                                    <hr>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="btn-group" role="group" aria-label="Default button group" id="statusBtnGroup">
                                                <button type="button" class="btn btn-outline-warning" data-status="Abiertas" onclick="SolicitudesAbiertas()">Abiertas</button>
                                                <button type="button" class="btn btn-outline-primary" data-status="Aceptadas" onclick="SolicitudesAceptadas()">Aceptadas</button>
                                                <button type="button" class="btn btn-outline-danger" data-status="Rechazadas" onclick="SolicitudesRechazadas()">Rechazadas</button>
                                                <button type="button" class="btn btn-outline-info" data-status="En proceso" onclick="SolicitudesEnProceso()">En proceso</button>
                                                <button type="button" class="btn btn-outline-success" data-status="Cerradas" onclick="SolicitudesCerradas()">Cerradas</button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-xl-12">

                                            <!-- Representa la tabla de solicitudes Abiertas.-->
                                            <table id="TSolAbiertas" name="TSolAbiertas" class="table table-sm table-hover table-striped table-bordered" style="width:100%">
                                                <thead class="table-warning">
                                                    <tr>                                                        
                                                        <th>Solicita</th>
                                                        <th>Dirigida a</th>
                                                        <!--<th>Fecha Solicitud</th>-->
                                                        <th>Fecha Incidente</th>
                                                        <th>Fecha Cierre</th>
                                                        <th>Tipo</th>
                                                        <th>Clasificación</th>                                                        
                                                        <th>Comentarios</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>

                                            <!--Representa la tabla de solicitudes aceptadas.-->
                                            <table id="TSolAceptadas" name="TSolAceptadas" class="table table-hover table-striped table-bordered" style="width:100%">
                                                <thead class="table-primary">
                                                    <tr>                                                        
                                                        <th>Solicita</th>
                                                        <th>Dirigida a</th>
                                                        <!--<th>Fecha Solicitud</th>-->
                                                        <th>Fecha Incidente</th>
                                                        <th>Fecha Cierre</th>
                                                        <th>Tipo</th>
                                                        <th>Clasificación</th>                                                        
                                                        <th>Comentarios</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>

                                            <!--Representa la tabla de solicitudes en proceso.-->
                                            <table id="TSolEnProceso" name="TSolEnProceso" class="table table-hover table-striped table-bordered" style="width:100%">
                                                <thead class="table-info">
                                                    <tr>                                                        
                                                        <th>Solicita</th>
                                                        <th>Dirigida a</th>
                                                        <!--<th>Fecha Solicitud</th>-->
                                                        <th>Fecha Incidente</th>
                                                        <th>Fecha Cierre</th>
                                                        <th>Tipo</th>
                                                        <th>Clasificación</th>                                                        
                                                        <th>Comentarios</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>

                                            <!--Representa la tabla de solicitudes cerradas.-->
                                            <table id="TSolCerradas" name="TSolCerradas" class="table table-hover table-striped table-bordered" style="width:100%">
                                                <thead class="table-success">
                                                    <tr>                                                        
                                                        <th>Solicita</th>
                                                        <th>Dirigida a</th>
                                                        <!--<th>Fecha Solicitud</th>-->
                                                        <th>Fecha Incidente</th>
                                                        <th>Fecha Cierre</th>
                                                        <th>Tipo</th>
                                                        <th>Clasificación</th>                                                        
                                                        <th>Comentarios</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>

                                            <!--Representa la tabla de solicitudes rechazadas.-->
                                            <table id="TSolRechazadas" name="TSolRechazadas" class="table table-hover table-striped table-bordered" style="width:100%">
                                                <thead class="table-danger">
                                                    <tr>                                                        
                                                        <th>Solicita</th>
                                                        <th>Dirigida a</th>
                                                        <!--<th>Fecha Solicitud</th>-->
                                                        <th>Fecha Incidente</th>
                                                        <th>Fecha Cierre</th>
                                                        <th>Tipo</th>
                                                        <th>Clasificación</th>                                                        
                                                        <th>Comentarios</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
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

    <!-- Modal para responder incidencias -->
    <div class="modal fade" id="responderIncidenciaModal" tabindex="-1" aria-labelledby="responderIncidenciaLabel" aria-hidden="true">
        <div class="modal-dialog">
            
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="responderIncidenciaLabel">Responder Incidencia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="respuestaIncidencia" class="form-label">Respuesta</label>
                            <select class="form-select" id="respuestaIncidencia" name="respuestaIncidencia" required>
                                <option value="" selected disabled>Seleccione una opción</option>                                
                            </select>
                            <input type="text" id="idIncidencia" name="idIncidencia" hidden>
                            <input type="text" name="tipoSolicitud" id="tipoSolicitud" hidden>
                        </div>
                        <div class="mb-3">
                            <label for="comentariosIncidencia" class="form-label">Comentarios</label>
                            <textarea class="form-control" id="comentariosIncidencia" name="comentariosIncidencia" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="GuardaRespuesta()">Enviar respuesta</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            
        </div>
    </div>

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
</body>
    <!-- Bootstrap core JavaScript
    <script src = "vendor/jquery/jquery.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>    
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>    
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.min.js"></script>
                
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--FUNCNIONES JS DE INCIDENCIAS-->
    <script src="funciones_incidencias.js" defer="defer"></script>
    <script type="text/javascript">
        $(document).ready(function() {                   
                $('#statusBtnGroup .btn').on('click', function() {
                    $('#statusBtnGroup .btn').removeClass('active');
                    $(this).addClass('active');
                });
                
                // Aplica el estilo a ambas tablas
                aplicarEstiloDataTable('#TSolAbiertas', 1);
                aplicarEstiloDataTable('#TSolAceptadas', 1);
                aplicarEstiloDataTable('#TSolEnProceso', 1);
                aplicarEstiloDataTable('#TSolCerradas', 1);
                aplicarEstiloDataTable('#TSolRechazadas', 1);

                // Mostrar inicialmente las solicitudes abiertas
                SolicitudesAbiertas();

        });
        
    </script>


</html>
