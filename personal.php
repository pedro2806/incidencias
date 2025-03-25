<!DOCTYPE html>
<html lang = "sp">

<head>

    <meta charset = "utf-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE = edge">
    <meta name = "viewport" content = "width = device-width, initial-scale = 1, shrink-to-fit = no">
    <meta name = "description" content = "">
    <meta name = "author" content = "">

    <title>RR HH</title>

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

                    <!-- Page Heading 
                    <div class = "d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class = "h3 mb-0 text-gray-800">Personal MESS</h1>                        
                    </div>
                    -->
                    <!-- Content Row -->

                    <div class = "row">

                        <!-- Area Chart -->
                        <div class = "col-xl-12">
                            <div class = "card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class = "card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h5 class = "m-0 font-weight-bold text-info">Personal MESS</h5>
                                </div>
                                <!-- Card Body -->
                                    <div class = "card-body">
                                        <div class = "row">                                            
                                            <div class = "col-xl-12">
                                            <table  class="table-responsive table-sm table-striped display compact" id ="Tusuarios" name="Tusuarios">
                                                <thead class="table-info">
                                                    <tr>                                                    
                                                        <th scope="col">Empleado</th>
                                                        <!--<th scope="col">Correo</th>
                                                        <th scope="col">Puesto</th>-->
                                                        <th scope="col">Region</th>
                                                        <th scope="col">Depto</th>
                                                        <th scope="col">Jefe</th>
                                                        <th scope="col">Fecha de ingreso</th>
                                                        <th scope="col">Antigüedad</th>
                                                        <th scope="col">Días</th>
                                                        <th scope="col">Contraro</th>
                                                        <th scope="col">Estatus</th>
                                                        <th scope="col"><i class="fas fa-edit"></i></th>
                                                        <th scope="col"><i class="fas fa-trash"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php                                                    
                                                        include 'conn.php';
                                                        $Qusuarios = "SELECT U.id, U.noEmpleado, U.nombre, U.correo, P.puesto, R.region, D.departamento, U.fechaIngreso, U.estatus, 
                                                        TIMESTAMPDIFF(YEAR,U.fechaIngreso,CURDATE()) AS antiguedad, U.tipoContrato,
                                                        (SELECT dias FROM diasvacaciones WHERE anio =(TIMESTAMPDIFF(YEAR,U.fechaIngreso,CURDATE()) ) )AS diasD, 
                                                        J.nombre as jefe
                                                        FROM usuarios U
                                                        LEFT JOIN puesto P ON U.puesto = P.id
                                                        LEFT JOIN region R ON U.region = R.id
                                                        LEFT JOIN departamento D ON U.departamento = D.id
                                                        LEFT JOIN usuarios J ON U.jefe = J.noEmpleado";
                                                        $res2= mysqli_query( $conn, $Qusuarios ) or die (mysqli_error($conn));
                                                        
                                                        While ($row2 = mysqli_fetch_array($res2)){
                                                            $noEmpleado = $row2["noEmpleado"];
                                                            $FechaIng = substr($row2["fechaIngreso"], 4, 6);
                                                            $anio = date("Y");
                                                            $fechaCompara = $anio.$FechaIng;
                                                            $hoy = date("Y-m-d");
                                                            $noEmp = $row2["noEmp"];
                                                            if ($fechaCompara <= $hoy){
                                                                $anioNext = $anio + 1;
                                                                $fechaPrev = $anio.$FechaIng;
                                                                $fechaNext = $anioNext.$FechaIng;
                                                                $QdiasSol = "SELECT IFNULL(SUM(dias), '0') as diasSol FROM solicitudes WHERE empleado = $noEmpleado AND (estatus = 2 && autorizaRH = 2) AND fesolicitud BETWEEN '$fechaPrev' AND '$fechaNext'";
                                                                
                                                                $resdiasSol= mysqli_query( $conn, $QdiasSol ) or die (mysqli_error($conn));
                                                                
                                                                While ($rowSol = mysqli_fetch_array($resdiasSol)){
                                                                    $diasSol = $rowSol["diasSol"];
                                                                }
                                                            }else{
                                                                $anioPrev = $anio - 1;
                                                                $fechaPrev = $anioPrev.$FechaIng;
                                                                $fechaNext = $anio.$FechaIng;
                                                                
                                                                $QdiasSol = "SELECT SUM(dias) as diasSol FROM solicitudes WHERE empleado = $noEmpleado AND (estatus = 2 && autorizaRH = 2) AND fesolicitud BETWEEN '$fechaPrev' AND '$fechaNext'";
                                                                $resdiasSol= mysqli_query( $conn, $QdiasSol ) or die (mysqli_error($conn));
                                                                
                                                                While ($rowSol = mysqli_fetch_array($resdiasSol)){
                                                                    $diasSol = $rowSol["diasSol"];
                                                                }
                                                            }
                                                            
                                                            
                                                            
                                                            
                                                            $id = $row2["id"];
                                                                                        
                                                            $nombre = $row2["nombre"];
                                                            $correo = $row2["correo"];
                                                            $puesto = utf8_encode($row2["puesto"]);
                                                            $region = $row2["region"];
                                                            $fechaIngreso = $row2["fechaIngreso"];
                                                            $departamento = $row2["departamento"];
                                                            $estatus = $row2["estatus"];
                                                            $antiguedad = $row2["antiguedad"];
                                                            $tipoContrato = $row2["tipoContrato"];
                                                            $jefe = $row2["jefe"];
                                                            
                                                            $Qdias = "SELECT * FROM diasvacaciones WHERE anio = $antiguedad";
                                                            $resdias= mysqli_query( $conn, $Qdias ) or die (mysqli_error($conn));
                                                            
                                                            While ($row3 = mysqli_fetch_array($resdias)){
                                                                $dias = $row3["dias"];
                                                            }
                                                            $dias = 0;
                                                            
                                                            //<td>'.$correo.'</td>
                                                            //<td>'.$puesto.'</td>
                                                            echo '<tr>                                                                    
                                                                    <td> '.$nombre.'</td>                                                                    
                                                                    <td>'.$region.'</td>
                                                                    <td>'.$departamento.'</td>
                                                                    <td>'.$jefe.'</td>
                                                                    <td>'.$fechaIngreso.'</td>
                                                                    <td>'.$antiguedad.'</td>
                                                                    <td>'.$row2["diasD"] - $diasSol.'</td>
                                                                    <td>'.$tipoContrato.'</td>
                                                                    <td>'.$estatus.'</td>
                                                                    <td>';
                                                                    ?>
                                                                        <form action="modificarempleado" method="post">
                                                                            <input type="hidden" id = "id_empleado" name="id_empleado" value="<?php echo $id; ?>">
                                                                            <button type="submit" class="btn btn-warning btn-circle btn-sm"><i class="fas fa-edit"></i></button>
                                                                        </form>
                                                                    </td>
                                                                    <td>
                                                                        <input type="hidden" id = "id_empleado" name="id_empleado" value="<?php echo $id; ?>">
                                                                        <button type="submit" class="btn btn-danger btn-circle btn-sm" onClick="bajaUs(this)" data-id="<?php echo $id; ?>"><i class="fas fa-trash"></i></button>
                                                                <?php
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
    
    <!-- Modal BajaUs-->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este usuario?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger yes-delete">Sí</button>
                </div>
            </div>
        </div>
    </div>
    
    </body>
    <!-- Bootstrap core JavaScript-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" defer="defer"></script>
    <!--<script src = "vendor/jquery/jquery.min.js"></script>-->
    <script src = "vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src = "vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src = "js/sb-admin-2.min.js"></script>
    <script type="text/javascript">
    
        $(document).ready(function () {
            $('#Tusuarios').DataTable({
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
        
        function bajaUs(boton) {
            var idEmpleado = boton.getAttribute('data-id');
        
            $('#confirmDeleteModal').modal('show'); // Muestra el modal
        
            $('.yes-delete').off('click').on('click', function() { // Elimina y agrega el evento click para evitar duplicaciones
                $.ajax({
                    type: 'POST',
                    url: 'acciones_contrasena.php',
                    data: { accion: "BajaUS", idEmpleado: idEmpleado },
                    success: function(response) {
                        Swal.fire(
                            '¡Eliminado!',
                            'El usuario ha sido eliminado.',
                            'success'
                        )
                        $('#confirmDeleteModal').modal('hide'); // Oculta el modal después de la eliminación
                        location.reload(); // Recarga la página
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        //$('#confirmDeleteModal').modal('hide'); // Oculta el modal en caso de error
                    }
                });
            });
        }
        
    </script>
 
</html>