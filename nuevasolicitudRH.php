<!DOCTYPE html>
<html>

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
</head>

<body id = "page-top">

    <!-- Page Wrapper -->
    <div id = "wrapper">
        <?php
            
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

                    <?php
                        include 'conteo.php';
                    ?>
                    <!-- Content Row -->

                    <div class = "row">

                        <!-- Area Chart -->
                        <div class = "col-xl-12">
                            <div class = "card shadow">
                                <!-- Card Body -->
                                    <div class = "card-body">
                                            <div class = "row">
                                                <div class = "col-xl-3" style = "text-align: center">
                                                    <img class = "sidebar-card-illustration" src = "img/MESS_05_Imagotipo_1.png" width = "160">
                                                </div>
                                                <div class = "col-xl-6">
                                                <center><p class="fs-4"><b>AUTORIZACIÓN DE INCIDENCIAS</b></p></center>
                                                </div>                                            
                                                <div class = "col-xl-3" style = "text-align: center">
                                                    <b>Recursos Humanos - </b>
                                                    <b><?php $hoy = date("d-m-Y"); print_r($hoy); ?></b>
                                                </div>
                                            </div>
                                            <div class = "row card-footer border-left-primary">
                                                <!--<div class = "col-xl-1"></div>-->
                                                <div class = "col-xl-8 mb-0">
                                                    <b>Por favor selecciona que tipo de incidencia es la que se autorizará:</b>
                                                </div>
                                            </div>
                                        <form method="POST" action="procesar_solicitud.php">
                                            <div class = "row card-footer border-left-primary">
                                                <div class = "col-xl-1"></div>
                                                <div class = "col-xl-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="ti1" id="ti1" onchange="tIncidencia(1);" value="1" required>
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            <b>Vacaciones</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class = "col-xl-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="ti1" id="ti2" onchange="tIncidencia(2);" value="1" required>
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            <b>Permiso sin goce</b>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class = "col-xl-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="ti1" id="ti3" onchange="tIncidencia(3);" value="1" required>
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            <b>Permiso con goce. (ver anexo: Reglamento)</b>
                                                            <input type="hidden" name="opIncidencia" id="opIncidencia">
                                                        </label>
                                                    </div>
                                                </div>                                                
                                            </div>
                                            
                                            <div class = "row card-footer border-left-primary">
                                                <!--<div class = "col-xl-1"></div>-->
                                                <div class = "col-xl-12">
                                                    <b>Favor de tomar nota de los días que el empleado estará fuera de Jornada Laboral (aplica para permiso sin goce, con goce y vacaciones):</b>
                                                </div>
                                            </div>
                                            
                                            <div class = "row card-footer border-left-primary">
                                                <div class = "col-xl-1"></div>
                                                <div class = "col-xl-3">
                                                    <div class="mb-1">
                                                        <label for="exampleFormControlInput1" class="form-label">Fecha de incio</label>
                                                        <input type="date" class="form-control" id="fechaInicial" name = "fechaInicial" onchange="diasEntreFechas();"  required>
                                                    </div>
                                                </div>
                                                <div class = "col-xl-3">
                                                    <div class="mb-1">
                                                        <label for="exampleFormControlInput1" class="form-label">Fecha de termino</label>
                                                        <input type="date" class="form-control" id="fechaFinal" name  = "fechaFinal" onchange="diasEntreFechas();" required>
                                                    </div>
                                                </div>
                                                <div class = "col-xl-2">
                                                    <div class="mb-1">
                                                        <label for="exampleFormControlInput1" class="form-label">No de días</label>
                                                        <input type="number" class="form-control" id="noDias" name ="noDias">
                                                    </div>  
                                                </div>
                                            </div>
                                            <div class = "row card-header border-left-primary">
                                                <!--<div class = "col-xl-1"></div>-->
                                                <div class = "col-xl-12">
                                                    <div class="mb-0">
                                                        <b for="exampleFormControlTextarea1" class="form-label">Indicar fechas cuando los días no sean un periodo corrido</b>
                                                        <textarea class="form-control" id="notas" name="notas" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class = "row  card-header border-left-success">
                                                <div class = "col-xl-4"></div>
                                                <div class = "col-xl-4">
                                                    <center>
                                                    <?php
                                                            $noEmp = $_COOKIE['noEmpleado'];
                                                            
                                                            $Qjefe = "SELECT us.nombre as jefe FROM autoriza a
                                                                        LEFT JOIN usuarios us ON a.jefe = us.noEmpleado
                                                                        WHERE a.usuario = $noEmp";
                                                            $resJefe= mysqli_query( $conn, $Qjefe ) or die (mysqli_error($conn));
                                                            
                                                            While ($row = mysqli_fetch_array($resJefe)){
                                                                $jefe = utf8_encode($row["jefe"]);
                                                            }
                                                            echo '<b class ="text-success text-xl">Autoriza: '.$jefe.'    </b> <br>  ';
                                                        ?>
                                                        <button type="submit" class="btn btn-primary">Solicitar</button>
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

    <!-- Bootstrap core JavaScript-->
    <script src = "vendor/jquery/jquery.min.js"></script>
    <script src = "vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src = "vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src = "js/sb-admin-2.min.js"></script>

    <script type="text/javascript">
    
        $(document).ready(function () {            
            
        });
        
        var tIncidencia = function (ti) {
            $('#opIncidencia').val(ti);
        }

        var diasEntreFechas = function () {
            fechaInicial = $('#fechaInicial').val();
            fechaFinal = $('#fechaFinal').val();
            
            var fechaDesde = new Date(fechaInicial);
            var fechaHasta = new Date(fechaFinal);
            var contador = 0;
            
            while (fechaDesde <= fechaHasta) {
                var dia = fechaDesde.getDay();
                
                if (dia == 0 || dia == 6) { // 0 es domingo, 6 es sábado
                    
                }
                else{
                    console.log('dia:'+ dia);
                    contador++;
                }
                fechaDesde.setDate(fechaDesde.getDate() + 1);
            }
            
            $('#noDias').val(contador);
            //return contador;
        }

    </script>

    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" defer="defer"></script>    
</body>

</html>