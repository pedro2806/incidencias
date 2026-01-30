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
    <style>
        /* Estilos para el contenedor de la pregunta */
        .pregunta-con-nota {
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        /* Regla clave para poner los elementos en la misma línea */
        .d-flex {
            display: flex;
        }
        .justify-content-between {
            justify-content: space-between; /* Mueve la pregunta a un extremo y las opciones al otro */
        }
        .align-items-center {
            align-items: center; /* Alinea verticalmente los checks y el texto */
        }

        /* Estilo para la nota de aclaración (ul) */
        .aclaracion-riesgo {
            margin-top: 5px;
            padding-left: 15px;
            border-left: 3px solid #dc3545; /* Línea de color rojo a la izquierda */
        }
        .aclaracion-riesgo ul {
            list-style: none; /* Quita la viñeta de la lista */
            padding-left: 0;
            margin-bottom: 0;
            font-size: 0.9em;
            font-style: italic;
            color: #dc3545; /* Hace la nota de riesgo roja */
        }
    </style>

<?php
    $usuariosRegistran = array(212, 14, 42, 161, 403, 183, 521, 276, 523, 71, 5, 360, 487, 19);

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
                                            <img class="sidebar-card-illustration" src="../img/MESS_05_Imagotipo_1.png" width="140">
                                        </div>
                                        <div class="col-xl-4">
                                            <center>
                                                <p class="fs-5"><b>REGISTRO DE INCIDENCIAS</b></p>
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
                                    <form id="formIncidencias" name="formIncidencias">
                                    <div class="row card-footer border-left-success">
                                        <div class="col-sm-4 mb-0"></div>
                                        <div class="col-sm-4 mb-0">
                                            <label><b>Tipo de incidencia</b></label>
                                            <select id="slcTIncidencia" name="slcTIncidencia" class="form-select" onchange="tIncidencia(this.value);">
                                                <option value="">Selecciona...</option>
                                                <option value="Operación">Operaciónes</option>
                                                <option value="Personal">Personal</option>
                                                <option value="Calidad">Calidad</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row card-footer border-left-success"  id="formFiltro" name="formFiltro" style="display: none;">
                                        <h4>Impacto de la Incidencia</h4>
                                        
                                        <div class="col-sm-12 mb-0">                                            
                                            <div class="form-check pregunta-con-nota p-3 border rounded mb-1">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    
                                                    <label class="form-label fw-bold me-4 w-75" for="economico-si">
                                                        1. ¿Tiene un impacto económico (reprogramación de servicio, pago de viáticos)?
                                                    </label>
                                                    
                                                    <div class="d-flex gap-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="economico" id="economico-si" value="si"> 
                                                            <label class="form-check-label" for="economico-si">Sí</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="economico" id="economico-no" value="no"> 
                                                            <label class="form-check-label" for="economico-no">No</label>
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <div class="aclaracion-riesgo ps-3 border-start border-secondary">
                                                    <ul class="mb-0 small fst-italic text-muted">
                                                        <li>Cualquier costo o gasto adicional generado de la ejecución del servicio incluyendo el proceso administrativo.</li>
                                                        <li>(ejemplos: Viáticos sin justificación, tiempo de operación excedido, cambios fuera de tiempo en la documentación)</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="form-check pregunta-con-nota p-3 border rounded mb-1">                                                
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    
                                                    <label class="form-label fw-bold me-4 w-75" for="repetitiva-si">
                                                        2. ¿Es una incidencia repetitiva?
                                                    </label>
                                                    
                                                    <div class="d-flex gap-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="repetitiva" id="repetitiva-si" value="si"> 
                                                            <label class="form-check-label" for="repetitiva-si">Sí</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="repetitiva" id="repetitiva-no" value="no"> 
                                                            <label class="form-check-label" for="repetitiva-no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="aclaracion-riesgo ps-3 border-start border-secondary">
                                                    <ul class="mb-0 small fst-italic text-muted">
                                                        <li>Problemas en la ejecución de los servicios que se han presentado mas de 2 ocasiones aun cuando estos incidentes no hayan sido reportados, puede ser por el tipo de problema o la persona que ejecuta</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="form-check pregunta-con-nota p-3 border rounded mb-1">                                                
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    
                                                    <label class="form-label fw-bold me-4 w-75" for="cliente-si">
                                                        3. ¿Afecta la relación con el cliente?
                                                    </label>
                                                    
                                                    <div class="d-flex gap-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="cliente" id="cliente-si" value="si"> 
                                                            <label class="form-check-label" for="cliente-si">Sí</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="cliente" id="cliente-no" value="no"> 
                                                            <label class="form-check-label" for="cliente-no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="aclaracion-riesgo ps-3 border-start border-secondary">
                                                    <ul class="mb-0 small fst-italic text-muted">
                                                        <li>La ejecución o no ejecución del servicio traerá consecuencias negativas en la relación con el cliente</li>
                                                        <li>(ejemplos: pérdida de negocios, cancelación de servicio, evaluación insatisfactoria)</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="form-check pregunta-con-nota p-3 border rounded mb-1"> 
    
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    
                                                    <label class="form-label fw-bold me-4 w-75" for="acreditacion-si">
                                                        4. ¿Podría afectar para mantener alguna acreditación?
                                                    </label>
                                                    
                                                    <div class="d-flex gap-4"> 
                                                        
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="acreditacion" id="acreditacion-si" value="si" required> 
                                                            <label class="form-check-label" for="acreditacion-si">Sí</label>
                                                        </div>
                                                        
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="acreditacion" id="acreditacion-no" value="no"> 
                                                            <label class="form-check-label" for="acreditacion-no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="aclaracion-riesgo ps-3 border-start border-secondary">
                                                    <ul class="mb-0 small fst-italic text-muted">
                                                        <li>Problemas que ponen en riesgo el sistema de gestión de calidad o las acreditaciones de mess</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-primary" onclick="evaluarIncidencia()">Evaluar Incidencia</button>
                                        </div>                                        
                                    </div>
                                    <div  id="divformIncidencias" name="divformIncidencias" style="display: none;">                                    
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
                                                <h6>Area: <span class="badge text-bg-primary" id="lblArea"></span></h6>
                                                <h6>Región: <span class="badge text-bg-primary" id="lblRegion"></span></h6>
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
                                                <label>Fecha incidente</label>
                                                <input type="date" class="form-control" id="fechaIncidente" name="fechaIncidente">
                                            </div>
                                            <div class="col-sm-4 mb-0">
                                                <label>Fecha planeada de cierre</label>
                                                <input type="date" class="form-control" id="fechaCierre" name="fechaCierre">
                                            </div>
                                            <div class="col-sm-4 mb-0">
                                                <label>Ot/Ov</label>
                                                <input type="text" class="form-control" id="otOv" name="otOv">
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

                                        <div class="row card-header border-left-primary" style="display: none;">                                           
                                            <div class="col-sm-4 mb-0">
                                                <div class="mb-0">
                                                    <label class="form-label">Cliente</label>
                                                    <textarea class="form-control" id="cliente" name="cliente" rows="3"></textarea>
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
                                    <div id="mensajeAlerta" class="alert alert-warning" style="display: none;">
                                        <p>⚠️ **Incidencia de Bajo Impacto.** Antes de registrarla, por favor **verifique con el área correspondiente** para determinar si requiere registro formal en el sistema.</p>
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
            if(tipo === "Personal") {
                $('#divformIncidencias').show();
                $('#formFiltro').hide();
            }else{
                $('#divformIncidencias').hide();
                $('#formFiltro').show();
            }

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
            var otOv = formData["otOv"];
                        
                $.ajax({
                    url: 'acciones_solicitud.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {opcion, responsable, tipo, clasificacion, fechaIncidente, fechaCierre, comentarios, otOv},
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
                data: {opcion, responsable: noEmpleadoInc},
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
        function evaluarIncidencia() {

            const respuestasSi = formFiltro.querySelectorAll('input[type="radio"]:checked[value="si"]');

            // 3. Evaluar el resultado
            if (respuestasSi.length > 0) {
                $('#divformIncidencias').show();
                $('#mensajeAlerta').hide();
                $('#formFiltro').hide();
            } else {
                $('#divformIncidencias').hide();
                $('#mensajeAlerta').show();
                $('#formFiltro').hide();
            }
        }
    </script>
</body>

</html>
