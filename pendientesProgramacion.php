<!DOCTYPE html>
<html lang="sp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestión de Pendientes | RRHH</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <style>
        /* Estilo para tareas completadas: Texto tachado y opacidad baja */
        .tarea-realizada {
            background-color: #f8f9fc !important;
            color: #b7b9cc !important;
            transition: 0.4s;
        }
        .tarea-realizada td {
            text-decoration: line-through;
        }
        .tarea-realizada .badge {
            opacity: 0.5;
            text-decoration: none !important;
        }

        /* Animación de parpadeo para nuevas tareas */
        .highlight {
            animation: flash-success 2s;
        }

        @keyframes flash-success {
            0% { background-color: #1cc88a; color: white; }
            100% { background-color: transparent; }
        }

        /* Ajustes de tabla */
        .table-hover tbody tr:hover {
            background-color: #f1f3f9;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        
        <?php 
            session_start();
            include 'menu.php'; 
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                
                <?php include 'encabezado.php'; ?>

                <div class="container-fluid">
                    
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Mis Pendientes Diarios</h1>
                    </div>

                    <div class="card shadow mb-4 border-left-primary">
                        <div class="card-body">
                            <form id="formPendiente" class="row gx-3 gy-2 align-items-center">
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <div class="input-group-text bg-primary text-white border-primary">
                                            <i class="fas fa-edit"></i>
                                        </div>
                                        <input type="text" class="form-control" id="tarea" name="tarea" placeholder="Escribe tu siguiente tarea aquí..." required autofocus>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <select class="form-select border-primary" name="prioridad" id="prioridad">
                                        <option value="Alta">Prioridad Alta</option>
                                        <option value="Media" selected>Prioridad Media</option>
                                        <option value="Baja">Prioridad Baja</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary w-100 font-weight-bold shadow-sm">
                                        <i class="fas fa-plus-circle"></i> AGREGAR
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-list-ul"></i> Actividades del Área
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="tablaPendientes" width="100%">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="5%" class="text-center">Status</th>
                                            <th>Descripción</th>
                                            <th width="10%">Prioridad</th>
                                            <th width="15%">Registro</th>
                                            <th width="15%">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista_pendientes">
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div> </div> <footer class="sticky-footer bg-white shadow-sm mt-4">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; MESS RRHH 2026</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Carga inicial
            listarPendientes();

            // Guardar con AJAX
            $("#formPendiente").on("submit", function(e){
                e.preventDefault();
                
                $.ajax({
                    url: "acciones_pendientes.php?op=guardar",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(v){
                        if(v.trim() == "1"){
                            // Resetear formulario y dar foco al input
                            $("#tarea").val("").focus();
                            
                            // Actualizar tabla con efecto visual
                            listarPendientes(true);
                        } else {
                            swal("Error", "No se pudo registrar en la base de datos.", "error");
                        }
                    },
                    error: function(){
                        swal("Error Crítico", "No hay comunicación con el servidor.", "error");
                    }
                });
            });
        });

        // Función para cargar la tabla
        function listarPendientes(conEfecto = false) {
            // Anti-caché con getTime()
            $.get("acciones_pendientes.php?op=listar&t=" + new Date().getTime(), function(data){
                $("#lista_pendientes").html(data);
                
                // Si viene de un registro nuevo, resaltamos la primera fila
                if(conEfecto){
                    $("#lista_pendientes tr:first").addClass("highlight");
                }
            });
        }

        // Función para marcar como terminado
        function marcarRealizado(id) {
            $.post("acciones_pendientes.php?op=completar", {id: id}, function(v){
                if(v.trim() == "1"){
                    listarPendientes();
                } else {
                    swal("Error", "No se pudo actualizar el estado.", "warning");
                }
            });
        }
    </script>
</body>
</html>