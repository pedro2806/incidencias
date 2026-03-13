<?php 
    session_start();
    // Capturamos el empleado de la cookie para mostrarlo en la interfaz
    $noEmpleado_cookie = isset($_COOKIE['noEmpleado']) ? $_COOKIE['noEmpleado'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bitácora de Pendientes | MESS RRHH</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <style>
        .tarea-realizada { background-color: #f8f9fc !important; color: #b7b9cc !important; }
        .tarea-realizada td { text-decoration: line-through; }
        .highlight { animation: flash-green 1.5s ease-out; }
        @keyframes flash-green {
            0% { background-color: #1cc88a; color: white; }
            100% { background-color: transparent; }
        }
        .scroll-avances { max-height: 300px; overflow-y: auto; background: #fdfdfd; padding: 10px; border-radius: 5px; }
        .avance-item { border-left: 3px solid #4e73df; padding-left: 10px; margin-bottom: 10px; border-bottom: 1px solid #eee; }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'menu.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'encabezado.php'; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Bitácora de Actividades</h1>                        
                    </div>

                    <div class="card shadow mb-4 border-left-primary">
                        <div class="card-body">
                            <form id="formPendiente" class="row gx-3 gy-2 align-items-center">
                                <div class="col-sm-7">
                                    <label class="small font-weight-bold">Nueva Actividad:</label>
                                    <input type="text" class="form-control border-primary" id="tarea" name="tarea" placeholder="¿Qué proyecto o tarea iniciamos?" required autofocus>
                                </div>
                                <div class="col-sm-3">
                                    <label class="small font-weight-bold">Prioridad:</label>
                                    <select class="form-select border-primary" name="prioridad">
                                        <option value="Alta">Alta</option>
                                        <option value="Media" selected>Media</option>
                                        <option value="Baja">Baja</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="d-none d-sm-block">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100 font-weight-bold">INICIAR</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow mb-4" style="display: none;">
                        <div class="card-body bg-light">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <label class="small font-weight-bold">Desde:</label>
                                    <input type="date" id="f_inicio" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label class="small font-weight-bold">Hasta:</label>
                                    <input type="date" id="f_fin" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-sm btn-dark w-100" onclick="listarPendientes()">
                                        <i class="fas fa-search"></i> Filtrar
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-sm btn-outline-secondary w-100" onclick="limpiarFiltros()">
                                        <i class="fas fa-sync"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th width="5%" class="text-center">Status</th>
                                            <th>Descripción de la Actividad</th>
                                            <th width="10%">Prioridad</th>
                                            <th width="15%">Registro</th>
                                            <th width="20%" class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista_pendientes"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalAvances" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title font-weight-bold"><i class="fas fa-history"></i> Bitácora de Avances</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <h6 id="tituloTarea" class="text-primary font-weight-bold mb-3"></h6>
                            <form id="formAvance" class="bg-light p-3 rounded mb-4 shadow-sm">
                                <input type="hidden" id="id_pendiente_avance" name="id_pendiente">
                                <div class="row">
                                    <div class="col-9">
                                        <textarea class="form-control" name="comentario" id="comentario_avance" rows="2" placeholder="Describe el avance de hoy..." required></textarea>
                                    </div>
                                    <div class="col-3">
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-save"></i> ANOTAR
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <label class="font-weight-bold text-gray-800"><i class="fas fa-stream"></i> Historial de progreso:</label>
                            <div id="historial_avances" class="scroll-avances border mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="sticky-footer bg-white mt-auto shadow-sm">
                <div class="container my-auto"><div class="copyright text-center my-auto"><span>MESS RRHH &copy; 2026</span></div></div>
            </footer>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        $(document).ready(function () {
            listarPendientes();

            // Guardar Tarea Maestra
            $("#formPendiente").on("submit", function(e){
                e.preventDefault();
                $.ajax({
                    url: "acciones_pendientes.php?op=guardar",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(v){
                        if(v.trim() == "1"){
                            $("#tarea").val("").focus();
                            listarPendientes(true);
                        } else {
                            swal("Error", "No se pudo iniciar la actividad", "error");
                        }
                    }
                });
            });

            // Guardar Avance Específico
            $("#formAvance").on("submit", function(e){
                e.preventDefault();
                $.ajax({
                    url: "acciones_pendientes.php?op=guardar_avance",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(v){
                        if(v.trim() == "1"){
                            $("#comentario_avance").val("");
                            let id = $("#id_pendiente_avance").val();
                            cargarHistorial(id);
                            swal("Avance Registrado", "", "success");
                        }
                    }
                });
            });
        });

        function listarPendientes(nuevo = false) {
            let fi = $("#f_inicio").val();
            let ff = $("#f_fin").val();
            $.get(`acciones_pendientes.php?op=listar&f_inicio=${fi}&f_fin=${ff}&t=${new Date().getTime()}`, function(data){
                $("#lista_pendientes").html(data);
                if(nuevo) $("#lista_pendientes tr:first").addClass("highlight");
            });
        }

        function abrirAvances(id, nombre) {
            $("#id_pendiente_avance").val(id);
            $("#tituloTarea").html("<i class='fas fa-tasks'></i> " + nombre);
            cargarHistorial(id);
            var modal = new bootstrap.Modal(document.getElementById('modalAvances'));
            modal.show();
        }

        function cargarHistorial(id) {
            $("#historial_avances").load("acciones_pendientes.php?op=ver_avances&id=" + id);
        }

        function marcarRealizado(id) {
            $.post("acciones_pendientes.php?op=completar", {id: id}, function(v){
                if(v.trim() == "1"){
                    listarPendientes();
                    swal("¡Logrado!", "Actividad marcada como completada", "success");
                } else {
                    // Esto mostrará el mensaje de error que configuramos en el PHP
                    swal("Atención", v, "error");
                }
            });
        }

        function eliminarTarea(id) {
            swal({
                title: "¿Eliminar registro?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((del) => {
                if (del) {
                    $.post("acciones_pendientes.php?op=eliminar", {id: id}, function(v){
                        if(v.trim() == "1") listarPendientes();
                    });
                }
            });
        }

        function limpiarFiltros() {
            $("#f_inicio, #f_fin").val("");
            listarPendientes();
        }
    </script>
</body>
</html>