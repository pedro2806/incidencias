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
                        <div class = "col-xl-12 col-lg-7">
                            <div class = "card shadow mb-4">
                                <!-- Card Header - Dropdown 
                                <div class = "card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class = "m-0 font-weight-bold text-info">REGIONES / AREAS / PUESTOS</h6>                                    
                                </div>
                                -->
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                	<li class="nav-item">
                                		<a class="nav-link active btn-outline-warning text-dark" type="button" id="region-tab" data-toggle="tab" href="#region" role="tab" aria-controls="region" aria-selected="true">REGIONES</a>
                                	</li>
                                	<li class="nav-item">
                                		<a class="nav-link btn-outline-success text-dark" id="area-tab" data-toggle="tab" href="#area" role="tab" aria-controls="area" aria-selected="false">AREAS</a>
                                	</li>
                                	<li class="nav-item">
                                		<a class="nav-link btn-outline-info text-dark" id="puesto-tab" data-toggle="tab" href="#puesto" role="tab" aria-controls="puesto" aria-selected="false">PUESTOS</a>
                                	</li>
                                </ul><br>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane border-left-warning fade show active in" id="region" role="tabpanel" aria-labelledby="region-tab">
                                        <center><h3><b>Regiones</b></h3></center>
                                            <table  class="table table-sm" style="width:80%"  id ="Tregion" name="Tregion">
                                                <thead class="table-warning">
                                                    <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Region</th>
                                                    <th scope="col">-</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php                                                    
                                                        include 'conn.php';
                                                        $Qregion = "SELECT * FROM region";                                                        
                                                        $Resregion= mysqli_query( $conn, $Qregion ) or die (mysqli_error($conn));
                                                        
                                                        While ($row2 = mysqli_fetch_array($Resregion)){
                                                            $id = $row2["id"];
                                                            $region = $row2["region"];
                                                            
                                                            echo '<tr>
                                                                    <td>'.$id.'</td>
                                                                    <td class="fs-6">'.utf8_encode($region).'</td>
                                                                    <td>
                                                                        <a href="#" class="btn btn-success btn-circle btn-sm">
                                                                            <i class="fas fa-check"></i>
                                                                        </a>
                                                                        <a href="#" class="btn btn-danger btn-circle btn-sm">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>';
                                                        
                                                        }

                                                    ?>                                                    
                                                </tbody>
                                            </table>
                                    </div>
                                    <div class="tab-pane border-left-success fade" id="area" role="tabpanel" aria-labelledby="area-tab">
                                        <center><h3><b>Departamentos</b></h3></center>
                                            <table  class="table table-sm" style="width:80%"  id ="Tdepto" name="Tdepto">
                                                <thead class="table-info">
                                                    <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Departamento</th>
                                                    <th scope="col">-</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php                                                                                                            
                                                        $Qdepto = "SELECT * FROM departamento";                                                        
                                                        $Resdepto= mysqli_query( $conn, $Qdepto ) or die (mysqli_error($conn));
                                                        
                                                        While ($rowdepto = mysqli_fetch_array($Resdepto)){
                                                            $id = $rowdepto["id"];
                                                            $departamento = $rowdepto["departamento"];
                                                            
                                                            echo '<tr>
                                                                    <td>'.$id.'</td>
                                                                    <td>'.utf8_encode($departamento).'</td>
                                                                    <td>
                                                                        <a href="#" class="btn btn-success btn-circle btn-sm">
                                                                            <i class="fas fa-check"></i>
                                                                        </a>
                                                                        <a href="#" class="btn btn-danger btn-circle btn-sm">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>';
                                                        
                                                        }

                                                    ?>                                                    
                                                </tbody>
                                            </table>
                                    </div>
                                    <div class="tab-pane border-left-info fade" id="puesto" role="tabpanel" aria-labelledby="puesto-tab">
                                            <center><h3><b>Puestos</b></h3></center>
                                            <table  class="table table-sm" style="width:80%"  id ="Tpuesto" name="Tpuesto">
                                                <thead class="table-info">
                                                    <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Puesto</th>
                                                    <th scope="col">-</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php                                                                                                            
                                                        $Qpuesto = "SELECT * FROM puesto";                                                        
                                                        $Respuesto= mysqli_query( $conn, $Qpuesto ) or die (mysqli_error($conn));
                                                        
                                                        While ($row2 = mysqli_fetch_array($Respuesto)){
                                                            $id = $row2["id"];
                                                            $puesto = $row2["puesto"];
                                                            
                                                            echo '<tr>
                                                                    <td>'.$id.'</td>
                                                                    <td>'.utf8_encode($puesto).'</td>
                                                                    <td>
                                                                        <a href="#" class="btn btn-success btn-circle btn-sm">
                                                                            <i class="fas fa-check"></i>
                                                                        </a>
                                                                        <a href="#" class="btn btn-danger btn-circle btn-sm">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                    </td>
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
            $('#Tregion').DataTable({
                language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish_Mexico.json'
                },
            });
            $('#Tdepto').DataTable({
                language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish_Mexico.json'
                },
            });
            $('#Tpuesto').DataTable({
                language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish_Mexico.json'
                },
            });
        });
    </script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" defer="defer"></script>
 
</html>