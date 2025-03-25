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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">
</head>

<body id = "page-top">

    <!-- Page Wrapper -->
    <div id = "wrapper">
        <?php
            //session_start();
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

                    <!-- Page Heading -->
                    <div class = "d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class = "h3 mb-0 text-gray-800">Mis solicitudes</h1>                        
                    </div>

                    <?php
                        include 'conteo.php';
                    ?>

                    <!-- Content Row -->
                    <div class = "card shadow mb-2">
                        <div class = "card-body">
                            <div class = "row">
                                <div class = "col-xl-1"></div>
                                <div class = "col-xl-10">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    	<li class="nav-item">
                                    		<a class="nav-link active btn-outline-warning" type="button" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Por autorizar</a>
                                    	</li>
                                    	<li class="nav-item">
                                    		<a class="nav-link btn-outline-success" id="autorizadas-tab" data-toggle="tab" href="#autorizadas" role="tab" aria-controls="autorizadas" aria-selected="false">Autorizadas</a>
                                    	</li>
                                    	<li class="nav-item">
                                    		<a class="nav-link btn-outline-danger" id="canceladas-tab" data-toggle="tab" href="#canceladas" role="tab" aria-controls="canceladas" aria-selected="false">Canceladas</a>
                                    	</li>
                                    </ul><br>
                                    <div class="tab-content" id="myTabContent">
                                    <!-- POR AUTORIZAR -->
                                        <div class="tab-pane fade show active in" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <table class="table table-sm table-striped" id = "TporAutorizar" name = "TporAutorizar">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">T/F Solicitud</th>                                                        
                                                        <th scope="col">No. días / Periodo</th>
                                                        <th scope="col">Comentarios</th>
                                                        <th scope="col">Estatus</th>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php                                                    
                                                        include 'conn.php';
                                                        $noEmpleado = $_COOKIE['noEmpleado'];
                                                        $Qsolicitudes = "SELECT * FROM solicitudes WHERE empleado = $noEmpleado AND (estatus = 1 OR autorizaRH = 1) ORDER BY fesolicitud ASC";
                                                        $res2= mysqli_query( $conn, $Qsolicitudes ) or die (mysqli_error($conn));
                                                        
                                                        While ($row2 = mysqli_fetch_array($res2)){
                                                            $id = $row2["id"];
                                                            $tipo = $row2["tipo"];                            
                                                            $desde = $row2["feinicio"];
                                                            $hasta = $row2["fefin"];
                                                            $feSol = $row2["fesolicitud"];
                                                            $noDias = $row2["dias"];
                                                            $notasE = $row2["notasempleado"];
                                                            $notasJ = $row2["notajefe"];
                                                            $estatus = $row2["estatus"];
                                                            $estatusRH = $row2["autorizaRH"];
                                                            
                                                            
                                                            //<td>'.$correo.'</td>
                                                            echo '<tr>                                                                    
                                                                    <td>';
                                                                    if($tipo == 1){
                                                                        echo '<span class="badge text-bg-success">Vacaciones</span><br>'.$feSol.'';
                                                                    }
                                                                    if($tipo == 2){
                                                                        echo '<span class="badge text-bg-info text-white">Permiso sin goce</span><br>'.$feSol.'';
                                                                    }
                                                                    if($tipo == 3){
                                                                        echo '<span class="badge text-bg-primary">Permiso con goce</span><br>'.$feSol.'';
                                                                    } 
                                                                    
                                                            echo '</td>
                                                                    <td><span class="badge text-bg-dark"> '.$noDias.' días</span><br>'.$desde.' - '.$hasta.'</td>                                                                    
                                                                    <td>'.utf8_encode($notasE).'</td>
                                                                    <td>';
                                                                        if($estatus == 1){
                                                                            echo '<span class="badge text-bg-warning">Por autorizar</span>';
                                                                        }
                                                                        if($estatus == 2){
                                                                            echo '<span class="badge text-bg-success">Autorizada</span>';
                                                                        }
                                                                        if($estatus == 3){
                                                                            echo '<span class="badge text-bg-danger">Rechazada</span>';
                                                                        }
                                                                        if($estatusRH == 1){
                                                                            echo '<span class="badge text-bg-warning">Por autorizar RH</span>';
                                                                        }
                                                                        if($estatusRH == 2){
                                                                            echo '<span class="badge text-bg-success">Autorizada RH</span>';
                                                                        }
                                                                        if($estatusRH == 3){
                                                                            echo '<span class="badge text-bg-danger">Rechazada RH</span>';
                                                                        }

                                                            echo ' </td>
                                                                   <td>
                                                                        <a class="btn btn-outline-danger btn-circle btn-sm" href="#" data-toggle="modal" data-target="#modalAutoriza" onClick="llenaInfo(3,'.$id.')">
                                                                            <i class="fas fa-times"></i>
                                                                        </a>
                                                                    </td>
                                                                  </tr>';
                                                        
                                                        }

                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <!-- AUTORIZADAS -->
                                        <div class="tab-pane fade" id="autorizadas" role="tabpanel" aria-labelledby="autorizadas-tab">
                                            <table class="table table-sm table-striped" id = "Tautorizadas" name = "Tautorizadas">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">T. de solicitud</th>                                                        
                                                        <th scope="col">Fecha de solicitud</th>
                                                        <th scope="col">No. días</th>
                                                        <th scope="col">Periodo</th>
                                                        <th scope="col">Comentarios</th>
                                                        <th scope="col">Comentarios jefe</th>
                                                        <th scope="col">Estatus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php                                                    
                                                        include 'conn.php';
                                                        $noEmpleado = $_COOKIE['noEmpleado'];
                                                        $Qsolicitudes = "SELECT * FROM solicitudes WHERE empleado = $noEmpleado AND (estatus = 2 AND autorizaRH = 2)";
                                                        $res2= mysqli_query( $conn, $Qsolicitudes ) or die (mysqli_error($conn));
                                                        
                                                        While ($row2 = mysqli_fetch_array($res2)){
                                                            $id = $row2["id"];
                                                            $tipo = $row2["tipo"];                            
                                                            $desde = $row2["feinicio"];
                                                            $hasta = $row2["fefin"];
                                                            $feSol = $row2["fesolicitud"];
                                                            $noDias = $row2["dias"];
                                                            $notasE = $row2["notasempleado"];
                                                            $notasJ = $row2["notajefe"];
                                                            $estatus = $row2["estatus"];
                                                            $autorizaRH = $row2["autorizaRH"];
                                                            
                                                            
                                                            //<td>'.$correo.'</td>
                                                            echo '<tr>                                                                    
                                                                    <td>';
                                                                    if($tipo == 1){
                                                                        echo '<span class="badge text-bg-success">Vacaciones</span>';
                                                                    }
                                                                    if($tipo == 2){
                                                                        echo '<span class="badge text-bg-info text-white">Permiso sin goce</span>';
                                                                    }
                                                                    if($tipo == 3){
                                                                        echo '<span class="badge text-bg-primary">Permiso con goce</span>';
                                                                    } 
                                                                    
                                                            echo '</td>
                                                                    <td>'.$feSol.'</td>
                                                                    <td>'.$noDias.'</td>
                                                                    <td>'.$desde.' - '.$hasta.'</td>                                                                    
                                                                    <td>'.$notasE.'</td>
                                                                    <td>'.$notasJ.'</td>
                                                                    <td>';
                                                                        if($estatus == 1){
                                                                            echo '<span class="badge text-bg-warning">Por autorizar</span>';
                                                                        }
                                                                        if($estatus == 2){
                                                                            echo '<span class="badge text-bg-success">Autorizada</span>';
                                                                        }
                                                                        if($estatus == 3){
                                                                            echo '<span class="badge text-bg-danger">Rechazada</span>';
                                                                        }
                                                                        echo '<br>';
                                                                        if($autorizaRH == 1){
                                                                            echo '<span class="badge text-bg-warning">Por autorizar RH</span>';
                                                                        }
                                                                        if($autorizaRH == 2){
                                                                            echo '<span class="badge text-bg-success">Autorizada RH</span>';
                                                                        }
                                                                        if($autorizaRH == 3){
                                                                            echo '<span class="badge text-bg-danger">Rechazada RH</span>';
                                                                        }

                                                            echo ' </td>
                                                                  </tr>';
                                                        
                                                        }

                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <!-- CANCELADAS / RECHAZADAS -->
                                        <div class="tab-pane fade" id="canceladas" role="tabpanel" aria-labelledby="canceladas-tab">
                                            <table class="table table-sm table-striped" id = "Tcanceladas" name = "Tcanceladas">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">T. de solicitud</th>                                                        
                                                        <th scope="col">Fecha de solicitud</th>
                                                        <th scope="col">No. días</th>
                                                        <th scope="col">Periodo</th>
                                                        <th scope="col">Comentarios</th>
                                                        <th scope="col">Comentarios jefe</th>
                                                        <th scope="col">Estatus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php                                                    
                                                        include 'conn.php';
                                                        $noEmpleado = $_COOKIE['noEmpleado'];
                                                        $Qsolicitudes = "SELECT * FROM solicitudes WHERE empleado = $noEmpleado AND estatus = 3";
                                                        $res2= mysqli_query( $conn, $Qsolicitudes ) or die (mysqli_error($conn));
                                                        
                                                        While ($row2 = mysqli_fetch_array($res2)){
                                                            $id = $row2["id"];
                                                            $tipo = $row2["tipo"];                            
                                                            $desde = $row2["feinicio"];
                                                            $hasta = $row2["fefin"];
                                                            $feSol = $row2["fesolicitud"];
                                                            $noDias = $row2["dias"];
                                                            $notasE = $row2["notasempleado"];
                                                            $notasJ = $row2["notajefe"];
                                                            $estatus = $row2["estatus"];
                                                            
                                                            
                                                            //<td>'.$correo.'</td>
                                                            echo '<tr>                                                                    
                                                                    <td>';
                                                                    if($tipo == 1){
                                                                        echo '<span class="badge text-bg-success">Vacaciones</span>';
                                                                    }
                                                                    if($tipo == 2){
                                                                        echo '<span class="badge text-bg-info text-white">Permiso sin goce</span>';
                                                                    }
                                                                    if($tipo == 3){
                                                                        echo '<span class="badge text-bg-primary">Permiso con goce</span>';
                                                                    } 
                                                                    
                                                            echo '</td>
                                                                    <td>'.$feSol.'</td>
                                                                    <td>'.$noDias.'</td>
                                                                    <td>'.$desde.' - '.$hasta.'</td>                                                                    
                                                                    <td>'.$notasE.'</td>
                                                                    <td>'.$notasJ.'</td>
                                                                    <td>';
                                                                        if($estatus == 1){
                                                                            echo '<span class="badge text-bg-warning">Por autorizar</span>';
                                                                        }
                                                                        if($estatus == 2){
                                                                            echo '<span class="badge text-bg-success">Autorizada</span>';
                                                                        }
                                                                        if($estatus == 3){
                                                                            echo '<span class="badge text-bg-danger">Rechazada</span>';
                                                                        }                                                                    

                                                            echo ' </td>
                                                                  </tr>';
                                                        
                                                        }

                                                    ?>
                                                </tbody>
                                            </table>
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
            <footer class = "sticky-footer bg-white">
                <div class = "container my-auto">
                    <div class = "copyright text-center my-auto">
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
    <a class = "scroll-to-top rounded" href = "#page-top">
        <i class = "fas fa-angle-up"></i>
    </a>
    <div class="modal fade show" id="modalAutoriza" tabindex="-1" role="dialog" aria-labelledby="modalAutoriza"  aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-left-primary">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Movimiento solicitud</h4>
                    <button class="close btn-danger" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form action="autorizar_incidencia.php" method="post">
                    <div class="modal-body">
                        
                            <b>Comentarios</b>
                            
                            <input type = "hidden" id = "idSolicitud" name = "idSolicitud">
                            <input type = "hidden" id = "estatusSol" name = "estatusSol">
                            <textarea class="form-control" id="comentariosJefe" name="comentariosJefe" rows="3"></textarea>
                        
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" type="button" data-dismiss="modal">Cancelar</button>
                        <button type = "submit" class="btn btn-outline-success" >Aceptar</a>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
    <!-- Bootstrap core JavaScript-->
    <!--<script src = "vendor/jquery/jquery.min.js"></script>-->
    <script src = "https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script src = "vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src = "vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src = "js/sb-admin-2.min.js"></script>    

    <script type="text/javascript">
    
        $(document).ready(function () {
            $('#TporAutorizar').DataTable({
                "language": {
                    "decimal": "",
                    "emptyTable": "No hay información disponible",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros totales)",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron resultados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna de manera ascendente",
                        "sortDescending": ": activar para ordenar la columna de manera descendente"
                    }
                }
            });
            
            $('#Tautorizadas').DataTable({
                "language": {
                    "decimal": "",
                    "emptyTable": "No hay información disponible",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros totales)",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron resultados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna de manera ascendente",
                        "sortDescending": ": activar para ordenar la columna de manera descendente"
                    }
                }
            });
            
            $('#Tcanceladas').DataTable({
                "language": {
                    "decimal": "",
                    "emptyTable": "No hay información disponible",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros totales)",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron resultados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna de manera ascendente",
                        "sortDescending": ": activar para ordenar la columna de manera descendente"
                    }
                }
            });
        });
        
        function llenaInfo(estatus, id) {
            $('#idSolicitud').val(id);
            $('#estatusSol').val(estatus);
        }
    </script>

    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" defer="defer"></script>

</body>

</html>