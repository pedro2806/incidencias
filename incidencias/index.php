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

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<?php
    $usuariosRegistran = array(212, 14, 42, 161, 403, 183, 521, 276, 523);

    if (in_array($_COOKIE['noEmpleado'], $usuariosRegistran)) {
        // El usuario tiene permiso para ver la página
    } else {
        header("Location: seguimiento_incidencias");        
    }
?>
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
                                    <form id="formIncidencias" name="formIncidencias">
                                        <div class="row card-footer border-left-primary">
                                            <div class="col-sm-4 mb-0">
                                                <label>Responsable</label>
                                                <div id="Divsolicita" name="Divsolicita">
                                                    <select id="slcRespoonsable" name="slcRespoonsable" class="form-select" onchange="getAreaRegion(this.value);">
                                                        <option value="">Selecciona...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-0">
                                                <label>Tipo</label>
                                                <select id="slcTIncidencia" name="slcTIncidencia" class="form-select" onchange="tIncidencia(this.value);">
                                                    <option value="">Selecciona...</option>
                                                    <option value="Operación">Operaciónes</option>
                                                    <option value="Personal">Personal</option>
                                                    <option value="Calidad">Calidad</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4 mb-0">
                                                <label>Clasificación</label>
                                                <select id="slcClasificacion" name="slcClasificacion" class="form-select">
                                                    <option value="">Selecciona...</option>
                                                </select>
                                            </div>
                                            
                                        </div>

                                        <div class="row card-footer border-left-primary">
                                            <div class="col-sm-4 mb-0">
                                                <h6>Area: <span class="badge text-bg-primary" id="lblArea"></span></h6>
                                                <h6>Región: <span class="badge text-bg-primary" id="lblRegion"></span></h6>
                                            </div>
                                            <div class="col-sm-4 mb-0">
                                                <label>Fecha incidente</label>
                                                <input type="date" class="form-control" id="fechaIncidente" name="fechaIncidente">
                                            </div>
                                            <div class="col-sm-4 mb-0">
                                                <label>Fecha planeada de cierre</label>
                                                <input type="date" class="form-control" id="fechaCierre" name="fechaCierre">
                                            </div>                                            
                                        </div>

                                        <div class="row card-header border-left-primary">                                           
                                            <div class="col-xl-12">
                                                <div class="mb-0">
                                                    <label class="form-label">Detalle</label>
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
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" defer="defer"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            empleadoSolicita();
            // Inicializa Select2 en el campo de responsable
            $('#slcRespoonsable').select2({            
                placeholder: "Seleccione...",
                width: '100%'
            });
        });

        var tIncidencia = function(ti) {
            opcion = "clasficacion";
            tipo = ti;
            $('#slcClasificacion').empty().append('<option value="">Selecciona...</option>');
            $.ajax({
                url: 'acciones_solicitud.php',
                method: 'POST',
                dataType: 'json',
                data: {opcion, tipo},
                success: function(data) {
                    var select = $('#slcClasificacion');
                    data.forEach(function(usuarios) {
                        var option = $('<option></option>').attr('value', usuarios.id).text(usuarios.clasificacion);
                        select.append(option);
                    });

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

        function generarSolicitud() {
            var opcion = "generarSolicitud";
            
            var formData = getFormData('formIncidencias');
            var responsable = formData["slcRespoonsable"];
            var tipo = formData["slcTIncidencia"];
            var clasificacion = formData["slcClasificacion"];
            var fechaIncidente = formData["fechaIncidente"];
            var fechaCierre = formData["fechaCierre"];
            var comentarios = formData["comentarios"];
                        
                $.ajax({
                    url: 'acciones_solicitud.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {opcion, responsable, tipo, clasificacion, fechaIncidente, fechaCierre, comentarios},
                    success: function(data) {
                        Swal.fire({
                            title: "La solicitúd se proceso con éxito!",
                            icon: "success",
                            draggable: true
                        });
                        enviaNotificacion(responsable, tipo);
                        window.location.href = 'seguimiento_incidencias';
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

        function getFormData(formId) {
            var formArray = $('#' + formId).serializeArray();
            var formData = {};
            formArray.forEach(function(item) {
                formData[item.name] = item.value;
            });
            return formData;
        }
        
        function getAreaRegion(noEmpleadoInc) {
            var opcion = "areaRegion";
            $.ajax({
                url: 'acciones_solicitud.php',
                method: 'POST',
                dataType: 'json',
                data: {opcion, noEmpleadoInc},
                success: function(data) {
                    $('#lblArea').text(data.area);
                    $('#lblRegion').text(data.region);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        title: "No se pudo obtener el área y región!",
                        icon: "error",
                        draggable: true
                    });
                }
            });
        }

        function enviaNotificacion(responsable, tipo) {

            urlNotificacion = '';
            if(tipo === "Personal") {
                urlNotificacion = 'enviaNotificacionPersonal.php';
            }else{
                urlNotificacion = 'enviaNotificacionCalidadOp.php';
            }
            
            $.ajax({
                url: urlNotificacion,
                method: 'POST',
                dataType: 'json',
                data: {
                    responsable,tipo
                },
                success: function(data) {
                    // Notificación opcional
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Error opcional
                }
            });
        }

        function empleadoSolicita() {
            opcion = "empleados";
            $.ajax({
                url: 'acciones_solicitud.php',
                method: 'POST',
                dataType: 'json',
                data: {opcion},
                success: function(data) {
                    var select = $('#slcRespoonsable');
                    data.forEach(function(usuarios) {
                        var option = $('<option></option>').attr('value', usuarios.noEmpleado).text(usuarios.nombre);
                        select.append(option);
                    });

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

        function getCookie(name) {
            let value = "; " + document.cookie;
            let parts = value.split("; " + name + "=");
            if (parts.length === 2) return parts.pop().split(";").shift();
        }
    </script>
</body>

</html>
