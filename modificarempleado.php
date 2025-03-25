<!DOCTYPE html>
<html lang = "sp">

<head>
    <meta charset = "utf-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE = edge">
    <meta name = "viewport" content = "width = device-width, initial-scale = 1, shrink-to-fit = no">
    <meta name = "description" content = "">
    <meta name = "author" content = "">

    <title>RR HH</title>

    <!-- Custom fonts for this template-->
    <link href = "vendor/fontawesome-free/css/all.min.css" rel = "stylesheet" type = "text/css">

    <!-- Custom styles for this template-->
    <link href = "css/sb-admin-2.min.css" rel = "stylesheet">
    <link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">
</head>

<body id = "page-top">

    <!-- Page Wrapper -->
    <div id = "wrapper">
        <?php
            session_start();
            //if(isset($_SESSION['nombredelusuario'])){}
            
            include 'menu.php';
        ?>

        

        <!-- Content Wrapper -->
        <div id = "content-wrapper" class = "d-flex flex-column">

            <!-- Main Content -->
            <div id = "content">
            
                <?php
                    //session_start();
                    //if(isset($_SESSION['nombredelusuario'])){}
                    
                    include 'encabezado.php';
                ?>
                <!-- Begin Page Content -->
                <div class = "container-fluid">

                    <div class = "card shadow">
                        <div class = "card-body">
                            <div class = "row">
                                <div class = "col-sm-3" style = "text-align: center">
                                    <!--<img class = "sidebar-card-illustration" src = "img/MESS_05_Imagotipo_1.png" width = "160">-->
                                </div>
                                <div class = "col-xl-6">
                                <center><p class="fs-4"><b>Editar información del trabajador</b></p></center>
                                </div>
                            </div>
                            <br<br>
                            <form method="post">
                            <input class="form-control" type="hidden" id="id_empleado" name="id_empleado" value="<?php echo $_POST["id_empleado"]; ?>">
                            
                                <div class = "row">                                            
                                    <div class = "col-sm-3">
                                        <label for="nombre">Nombre:</label>
                                        <input class="form-control" type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required><br>
                                    </div>
                                    <div class = "col-sm-3">
                                        <label for="correo">Correo Electrónico:</label>
                                        <input class="form-control" type="email" id="correo" name="correo" value="<?php echo $correo; ?>" required><br>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="puesto">Puesto:</label>
                                        <select class="form-select" id="puesto" name="puesto" required>
                                            
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="region">Región:</label>
                                        <select class="form-select" id="region" name="region">
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class = "row">                                            
                                    <div class="col-sm-3">
                                        <label for="departamento">Departamento:</label>
                                        <select class="form-select" id="departamento" name="departamento">
                                        </select>
                                    </div>
                                    <div class = "col-sm-3">    
                                        <label for="fechaIngreso">Fecha de Ingreso:</label>
                                        <input class="form-control" type="date" id="fechaIngreso" name="fechaIngreso"  required><br>
                                    </div>
                                    <div class = "col-sm-3">
                                        <label for="rol">Rol:</label>
                                        <select class="form-select" id="rol" name="rol" value="<?php echo $rol; ?>" required>
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="jefe">Jefe:</label>
                                        <select class="form-select" id="jefe" name="jefe">
                                            <option value="<?php echo $idjefe; ?>" selected><?php echo $jefe; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class = "row">                                            
                                    <div class = "col-sm-4">
                                    </div>
                                    <div class = "col-sm-4">
                                        <center><button class = "btn btn-success"  type="button" onClick="guardarCambios(event)">Guardar Cambios</button></center>
                                    </div>
                                </div>
                        </form>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class = "sticky-footer bg-white">
                <div class = "container my-auto">
                    <div class = "copyright text-center my-auto">
                        <span>Copyright &copy; MESS</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class = "scroll-to-top rounded" href = "#page-top">
        <i class = "fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class = "modal fade" id = "logoutModal" tabindex = "-1" role = "dialog" aria-labelledby = "exampleModalLabel"
        aria-hidden = "true">
        <div class = "modal-dialog" role = "document">
            <div class = "modal-content">
                <div class = "modal-header">
                    <h5 class = "modal-title" id = "exampleModalLabel">Cerrar sesión</h5>
                    <button class = "close" type = "button" data-dismiss = "modal" aria-label = "Close">
                        <span aria-hidden = "true">×</span>
                    </button>
                </div>
                <div class = "modal-body">¿Estas seguro?</div>
                <div class = "modal-footer">
                    <button class = "btn btn-info" type = "button" data-dismiss = "modal">Cancelar</button>
                    <a class = "btn btn-danger" href = "logout">Salir</a>
                </div>
            </div>
        </div>
    </div>
    </body>
    <!-- Bootstrap core JavaScript
    <script src = "vendor/jquery/jquery.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src = "vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src = "vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src = "js/sb-admin-2.min.js"></script>
    <script type="text/javascript">
    
        $(document).ready(function () {
            cargaInicial();
        });
        
    </script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" defer="defer"></script>
    
    <script type="text/javascript">
    
        function cargaInicial(){
            muestrapuesto();
            muestraregion();
            muestradepartamento();
            muestrajefe();
            muestraDatosUsuario();
        }
        function muestrapuesto(){
            var opcion = "puesto";
                $.ajax({
                    url: 'llena_selects.php',
                    method: 'GET',
                    dataType: 'json',
                    data:{opcion},
                    success: function(data) {
                        var select = $('#puesto');
                        data.forEach(function(puesto) {
                            
                            //AQUI SE CREA LA CADENA PARA LOS OPTION
                            var option = $('<option></option>').attr('value', puesto.id).text(puesto.puestos);
                            
                            //AQUI SE VAN CARGANDO LAS OPCIONES DEL SELECT--- ATTR = ATRIBUTOS VALUE Y TEXTO
                            select.append(option);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal.fire("Algo Salio Mal!", "Intenta de Nuevo", "error");
                        
                    }
                });
        }
        
        function muestraregion() {
            var opcion = "region";
            $.ajax({
                url: 'llena_selects.php',
                method: 'GET',
                dataType: 'json',
                data: { opcion },
                success: function(data) {
                    var select = $('#region');
                    data.forEach(function(region) {
                        var option = $('<option></option>').attr('value', region.id).text(region.region);
                        select.append(option);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal.fire("Algo Salio Mal!", "Intenta de Nuevo", "error");
                }
            });
        }
        
        function muestradepartamento(){
            var opcion = "departamento";
                $.ajax({
                    url: 'llena_selects.php',
                    method: 'GET',
                    dataType: 'json',
                    data:{opcion},
                    success: function(data) {
                        var select = $('#departamento');
                        data.forEach(function(departamento) {
                            
                            //AQUI SE CREA LA CADENA PARA LOS OPTION
                            var option = $('<option></option>').attr('value', departamento.id).text(departamento.departamento);
                            
                            //AQUI SE VAN CARGANDO LAS OPCIONES DEL SELECT--- ATTR = ATRIBUTOS VALUE Y TEXTO
                            select.append(option);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal.fire("Algo Salio Mal!", "Intenta de Nuevo", "error");
                    }
                });
        }
        
        function muestrajefe() {
            var opcion = "jefe";
            $.ajax({
                url: 'llena_selects.php',
                method: 'GET',
                dataType: 'json',
                data: { opcion },
                success: function(data) {
                    var select = $('#jefe');
                    data.forEach(function(jefe) {
                        var option = $('<option></option>').attr('value', jefe.id).text(jefe.nombre);
                        select.append(option);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal.fire("Algo Salio Mal!", "Intenta de Nuevo", "error");
                }
            });
        }
        
        function guardarCambios(){
            var idEmpleado = $('#id_empleado').val();
            var nombre = $('#nombre').val();
            var correo = $('#correo').val();
            var puesto = $('#puesto').val();
            var region = $('#region').val();
            var departamento = $('#departamento').val();
            var fechaIngreso = $('#fechaIngreso').val();
            var rol = $('#rol').val();
            var jefe = $('#jefe').val();
            var accion = "ModificarUS";

            $.ajax({
                url: 'acciones_contrasena.php',
                method: 'POST',
                dataType: 'json',
                data:{accion, idEmpleado, nombre, correo, puesto, region, departamento, fechaIngreso, rol, jefe},
                success: function() {
                    swal.fire("Se aplicaron los cambios","","success");
                    cargaInicial();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal.fire("Algo Salio Mal!", "Intenta de Nuevo", "error");
                }
            });
        }
        
        function muestraDatosUsuario() {
            var opcion = "datosUsuario";
            var idEmpleado = $('#id_empleado').val();
            $.ajax({
                url: 'llena_selects.php',
                method: 'GET',
                dataType: 'json',
                data: { opcion, idEmpleado },
                success: function(data) {
                    
                    data.forEach(function(usuario) {
                        
                        $('#nombre').val(usuario.nombre);
                        $('#correo').val(usuario.correo);
                        $('#puesto').val(usuario.id_puesto);
                        $('#region').val(usuario.id_region);
                        $('#departamento').val(usuario.departamento);
                        $('#fechaIngreso').val(usuario.fechaIngreso);
                        $('#rol').val(usuario.rol);
                        $('#jefe').val(usuario.jefe);
                        
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal.fire("Algo Salio Mal!", "Intenta de Nuevo", "error");
                }
            });
        }
    </script>
</html>            