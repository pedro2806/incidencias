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
    <link href = "https://fonts.googleapis.com/css?family = Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel = "stylesheet">

    <!-- Custom styles for this template-->
    <link href = "css/sb-admin-2.min.css" rel = "stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body id = "page-top">

    <!-- Page Wrapper -->
    <div id = "wrapper">
        <?php
            //session_start();
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
                        <h1 class = "h3 mb-0 text-gray-800">Solicitudes</h1>                        
                    </div>

                    <?php
                        include 'conteo.php';
                    ?>

                    <!-- Content Row -->

                    <div class = "row">
                    <!-- Content Row -->
                    <div class = "card shadow mb-2">
                        <div class = "card-header py-2 d-flex flex-row align-items-center justify-content-between">
                            <h6 class = "m-0 font-weight-bold text-black">Solicitudes</h6>                                    
                        </div>
                        <div class = "card-body">
                            <div class = "row">
                                
                                
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    	<li class="nav-item">
                                    		<a class="nav-link active btn-outline-warning text-dark" type="button" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Por autorizar</a>
                                    	</li>
                                    	<li class="nav-item">
                                    		<a class="nav-link btn-outline-success text-dark" id="autorizadas-tab" data-toggle="tab" href="#autorizadas" role="tab" aria-controls="autorizadas" aria-selected="false">Autorizadas</a>
                                    	</li>
                                    	<li class="nav-item">
                                    		<a class="nav-link btn-outline-danger text-dark" id="canceladas-tab" data-toggle="tab" href="#canceladas" role="tab" aria-controls="canceladas" aria-selected="false">Canceladas</a>
                                    	</li>
                                    </ul><br>
                                    
                                    
                                    <div class="tab-content" id="myTabContent">
                                    <!-- POR AUTORIZAR -->
                                        <br>
                                        <div class="tab-pane border-left-warning fade show active in" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <table class="table table-sm table-striped" id = "TporAutorizar" name = "TporAutorizar">
                                                <thead class = "table-primary">
                                                    <tr>
                                                        <th scope="col">Empleado</th>
                                                        <th scope="col">T. de solicitud</th>
                                                        <th scope="col">Fecha de solicitud</th>
                                                        <th scope="col">No. días/Periodo</th>
                                                        <th scope="col">Comentarios</th>
                                                        <th scope="col">Estatus</th>
                                                        <th scope="col">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody >
                                                <?php                                                    
                                                        include 'conn.php';
                                                        $noEmpleado = $_COOKIE['noEmpleado'];
                                                        $Qsolicitudes = "SELECT s.*, u.nombre as empleadoNom, s.fesolicitud, u.fechaIngreso, s.empleado,
                                                                        (SELECT dias FROM diasvacaciones WHERE anio =(TIMESTAMPDIFF(YEAR,u.fechaIngreso,CURDATE()) ) )AS diasD
                                                                        FROM solicitudes s 
                                                                        INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                                                                        WHERE empleado in (SELECT noEmpleado FROM usuarios WHERE jefe = $noEmpleado) AND (s.estatus = 1 OR autorizaRH = 1) order by fesolicitud DESC";
                                                        $res2= mysqli_query( $conn, $Qsolicitudes ) or die (mysqli_error($conn));
                                                        
                                                        While ($row2 = mysqli_fetch_array($res2)){
                                                               
                                                                $FechaIng = substr($row2["fechaIngreso"], 4, 6);
                                                                $anio = date("Y");
                                                                $fechaCompara = $anio.$FechaIng;
                                                                $hoy = date("Y-m-d");
                                                                
                                                                $noEmp = $row2["empleado"];
                                                                if ($fechaCompara <= $hoy){
                                                                    $anioNext = $anio + 1;
                                                                    $fechaPrev = $anio.$FechaIng;
                                                                    $fechaNext = $anioNext.$FechaIng;
                                                                    $QdiasSol = "SELECT IFNULL(SUM(dias), '0') as diasSol FROM solicitudes WHERE empleado = $noEmp AND (estatus = 2 && autorizaRH = 2) AND fesolicitud BETWEEN '$fechaPrev' AND '$fechaNext'";
                                                                    $resdiasSol= mysqli_query( $conn, $QdiasSol ) or die (mysqli_error($conn));
                                                                    
                                                                    While ($rowSol = mysqli_fetch_array($resdiasSol)){
                                                                        $diasSol = $rowSol["diasSol"];
                                                                    }
                                                                }else{
                                                                    $anioPrev = $anio - 1;
                                                                    $fechaPrev = $anioPrev.$FechaIng;
                                                                    $fechaNext = $anio.$FechaIng;
                                                                    
                                                                    $QdiasSol = "SELECT SUM(dias) as diasSol FROM solicitudes WHERE empleado = $noEmp AND (estatus = 2 && autorizaRH = 2) AND fesolicitud BETWEEN '$fechaPrev' AND '$fechaNext'";
                                                                     $resdiasSol= mysqli_query( $conn, $QdiasSol ) or die (mysqli_error($conn));
                                                                    
                                                                    While ($rowSol = mysqli_fetch_array($resdiasSol)){
                                                                        $diasSol = $rowSol["diasSol"];
                                                                    }
                                                                }
                                                            
                                                            
                                                            $id = $row2["id"];
                                                            $empleado = $row2["empleado"];
                                                            $tipo = $row2["tipo"];                            
                                                            $desde = $row2["feinicio"];
                                                            $hasta = $row2["fefin"];
                                                            $feSol = $row2["fesolicitud"];
                                                            $noDias = $row2["dias"];
                                                            $notasE = $row2["notasempleado"];
                                                            $notasJ = $row2["notajefe"];
                                                            $estatus = $row2["estatus"];
                                                            $empleadoNom = $row2["empleadoNom"];
                                                            $autorizaRH = $row2["autorizaRH"];
                                                            $diasDisp = $row2["diasD"] - $diasSol;
                                                            
                                                           echo '<tr>
                                                                    <td><b>'.$empleadoNom.'</td>
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
                                                                    echo '<span class="badge text-bg-primary"><b>'.$diasDisp.' días Disp.</b></span>';
                                                                    
                                                            echo '</td>
                                                                    <td><b>'.date("d/m/Y", strtotime($feSol)).'</td>
                                                                    <td><span class="badge text-bg-dark"><b>'.$noDias.' días</b></span><br> '.date("d/m/Y", strtotime($desde)).' - '.date("d/m/Y", strtotime($hasta)).'</td>
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
                                                                   <td>
                                                                        <a class="btn btn-outline-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#modalAutoriza" onClick="llenaInfo(2, '.$id.', '.$noDias.', '.$empleado.', '.$autorizaRH.')">
                                                                            <i class="fas fa-check"></i>
                                                                        </a>                                                                        
                                                                        <a class="btn btn-outline-danger btn-circle btn-sm" href="#" data-toggle="modal" data-target="#modalAutoriza" onClick="llenaInfo(3, '.$id.', '.$noDias.', '.$empleado.', '.$autorizaRH.')">
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
                                        
                                        <div class="tab-pane border-left-success fade" id="autorizadas" role="tabpanel" aria-labelledby="autorizadas-tab">
                                            <table class="table table-sm table-striped" id = "Tautorizadas" name = "Tautorizadas">
                                                <thead class = "table-primary">
                                                    <tr>
                                                        <th scope="col">Empleado</th>
                                                        <th scope="col">T. de solicitud</th>
                                                        <th scope="col">Fecha de solicitud</th>
                                                        <th scope="col">No. días/Periodo</th>
                                                        <th scope="col">Comentarios</th>
                                                        <th scope="col">Estatus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php                                                    
                                                        include 'conn.php';
                                                        $noEmpleado = $_COOKIE['noEmpleado'];
                                                        $Qsolicitudes = "SELECT s.*, u.nombre as empleado FROM solicitudes s 
                                                        INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                                                        WHERE empleado in (SELECT noEmpleado FROM usuarios WHERE jefe = $noEmpleado) AND (s.estatus = 2 AND s.autorizaRH = 2)";
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
                                                            $empleado = $row2["empleado"];
                                                            $autorizaRH = $row2["autorizaRH"];
                                                            
                                                            echo '<tr>
                                                                    <td><b>'.utf8_encode($empleado).'</td>
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
                                                                    <td><b>'.date("d/m/Y", strtotime($feSol)).'</td>
                                                                    <td><span class="badge text-bg-dark"><b>'.$noDias.' días</b></span><br> '.date("d/m/Y", strtotime($desde)).' - '.date("d/m/Y", strtotime($hasta)).'</td>
                                                                    <td>'.utf8_encode($notasJ).'</td>
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
                                        
                                        <div class="tab-pane border-left-danger fade" id="canceladas" role="tabpanel" aria-labelledby="canceladas-tab">
                                            <table class="table table-sm table-striped" id = "Tcanceladas" name = "Tcanceladas">
                                                <thead class = "table-primary">
                                                    <tr>
                                                        <th scope="col">Empleado</th>
                                                        <th scope="col">T. de solicitud</th>
                                                        <th scope="col">Fecha de solicitud</th>
                                                        <th scope="col">No. días/Periodo</th>
                                                        <th scope="col">Comentarios</th>
                                                        <th scope="col">Comentarios jefe</th>
                                                        <th scope="col">Estatus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php                                                    
                                                        include 'conn.php';
                                                        $noEmpleado = $_COOKIE['noEmpleado'];
                                                        $Qsolicitudes = "SELECT s.*, u.nombre as empleado FROM solicitudes s 
                                                        INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                                                        WHERE empleado in (SELECT noEmpleado FROM usuarios WHERE jefe = $noEmpleado) AND s.estatus = 3";
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
                                                            $empleado = $row2["empleado"];
                                                            
                                                            echo '<tr>
                                                                    <td><b>'.utf8_encode($empleado).'</td>
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
                                                                    <td><b>'.date("d/m/Y", strtotime($feSol)).'</td>
                                                                    <td><span class="badge text-bg-dark"><b>'.$noDias.' días</b></span><br> '.date("d/m/Y", strtotime($desde)).' - '.date("d/m/Y", strtotime($hasta)).'</td>
                                                                    <td>'.utf8_encode($notasE).'</td>
                                                                    <td>'.utf8_encode($notasJ).'</td>
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
                        <span>Copyright &copy; MESS 2023</span>
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
                    <h4 class="modal-title" id="exampleModalLabel">Autorizar</h4>
                    <button class="close btn-danger btn-sm" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form action="autorizar_incidencia.php" method="post">
                    <div class="modal-body">
                        
                            <b>Comentarios</b>
                            
                            <input type = "hidden" id = "idSolicitud" name = "idSolicitud">
                            <input type = "hidden" id = "estatusSol" name = "estatusSol">
                            <input type = "hidden" id = "estatusRH" name = "estatusRH">
                            <input type = "hidden" id = "ndias" name = "ndias">
                            <input type = "hidden" id = "nempleado" name = "nempleado">
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
    <script src = "vendor/jquery/jquery.min.js"></script>
    <script src = "vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src = "vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src = "js/sb-admin-2.min.js"></script>    
    
    

    <script type="text/javascript">
    
        $(document).ready(function () {
            $('#Tsolicitudes').DataTable({
                language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish_Mexico.json'
                },
            });
    
    
    
            $('#TporAutorizar').DataTable({
                
            });
            
            $('#Tautorizadas').DataTable({
                
            });
            
            $('#Tcanceladas').DataTable({
                
            });
        });
    
    </script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" defer="defer"></script>
    <script> 
    
        function llenaInfo(estatus, id, días, empleado, rh) {
            $('#idSolicitud').val(id);
            $('#estatusSol').val(estatus);
            $('#ndias').val(días);
            $('#nempleado').val(empleado);
            $('#estatusRH').val(rh);
        }
    </script>

</body>

</html>