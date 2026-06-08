<?php include 'acceso_admin.php'; ?>
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
    <link rel="stylesheet" href="vendor/datatables/2.1.8/css/dataTables.dataTables.css" />
    <link href="vendor/datatables/buttons/3.1.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href = "css/vacaciones.css" rel = "stylesheet">
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
                    include 'encabezado.php';
                ?>

                <!-- encabezado.php (compartido) hace document.getElementById("noEmpleado").value = cookie -->
                <input type="hidden" id="noEmpleado">

                <!-- Begin Page Content -->
                <div class = "container-fluid">

                    <!-- Page Heading -->
                    <div class = "d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class = "h3 mb-0 text-gray-800">Reporte Nomina</h1>
                    </div>

                    <!-- Content Row -->

                    <div class = "row">
                    <!-- Content Row -->
                    <div class = "card shadow mb-2 w-100">
                        <div class = "card-body">
                            <div>
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    	<li class="nav-item">
                                    		<a class="nav-link active btn-outline-warning text-dark" type="button" id="sPagar-tab" data-toggle="tab" href="#sPagar" role="tab" aria-controls="sPagar" aria-selected="true">AUTORIZADAS SIN PAGAR</a>
                                    	</li>
                                    	<li class="nav-item">
                                    		<a class="nav-link btn-outline-primary text-dark" id="pPagar-tab" data-toggle="tab" href="#pPagar" role="tab" aria-controls="pPagar" aria-selected="false">POR PAGAR / NÓMINA</a>
                                    	</li>
                                    	<li class="nav-item">
                                    		<a class="nav-link btn-outline-success text-dark" id="pagadas-tab" data-toggle="tab" href="#pagadas" role="tab" aria-controls="pagadas" aria-selected="false">PAGADAS</a>
                                    	</li>
                                    </ul><br>


                                    <div class="tab-content" id="myTabContent">
                                    <!-- POR PAGAR -->
                                        <br>
                                        <div class="tab-pane border-left-warning fade show active in" id="sPagar" role="tabpanel" aria-labelledby="sPagar-tab">

                                            <div class="d-flex align-items-center flex-wrap mb-3">
                                                <button type="button" class="btn btn-naranja btn-sm mb-1" onClick="mandarNomina()"><i class="fas fa-file-invoice-dollar mr-1"></i>Generar Reporte Nómina / Mandar Nómina</button>
                                            </div>

                                            <table class="table table-sm table-striped" id = "TporPagar" name = "TporPagar">
                                                <thead class = "table-primary">
                                                    <tr>
                                                        <th scope="col" width="5%">ID</th>
                                                        <th scope="col" width="5%">NO. EMP</th>
                                                        <th scope="col" width="20%">NOMBRE</th>
                                                        <th scope="col" width="10%">F. SOL</th>
                                                        <th scope="col" width="10%">F. INI</th>
                                                        <th scope="col" width="10%">F. FIN</th>
                                                        <th scope="col" width="5%">DIAS</th>
                                                        <th scope="col" width="10%">NOTAS EMP</th>
                                                        <th scope="col" width="10%">NOTAS JE</th>
                                                        <th scope="col" width="10%">COMENT</th>
                                                        <th scope="col" width="5%">TIPO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    <!-- NOMINA -->

                                        <div class="tab-pane border-left-primary fade" id="pPagar" role="tabpanel" aria-labelledby="pPagar-tab">

                                            <div class="d-flex align-items-center flex-wrap mb-3" id="accionesEnNomina">
                                                <button type="button" class="btn btn-naranja btn-sm mr-2 mb-1" onClick="mandarPagado()"><i class="fas fa-check-circle mr-1"></i>Marcar como pagado</button>
                                                <button type="button" class="btn btn-success btn-sm mb-1" onClick="exportarExcelNomina()"><i class="fas fa-file-excel mr-1"></i>Exportar a Excel</button>
                                            </div>

                                            <table class="table table-sm table-hover table-striped" id = "TenNomina" name = "TenNomina">
                                                <thead class = "table-primary">
                                                    <tr>
                                                        <th scope="col" width="5%">ID</th>
                                                        <th scope="col" width="5%">NO. EMP</th>
                                                        <th scope="col" width="20%">NOMBRE</th>
                                                        <th scope="col" width="10%">F. SOL</th>
                                                        <th scope="col" width="10%">F. INI</th>
                                                        <th scope="col" width="10%">F. FIN</th>
                                                        <th scope="col" width="5%">DIAS</th>
                                                        <th scope="col" width="10%">NOTAS EMP</th>
                                                        <th scope="col" width="10%">NOTAS JE</th>
                                                        <th scope="col" width="10%">COMENT</th>
                                                        <th scope="col" width="5%">TIPO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    <!-- PAGADAS -->

                                        <div class="tab-pane border-left-success fade" id="pagadas" role="tabpanel" aria-labelledby="pagadas-tab">
                                            <table class="table table-sm table-striped" id = "Tpagadas" name = "Tpagadas">
                                                <thead class = "table-primary">
                                                    <tr>
                                                        <th scope="col" width="5%">ID</th>
                                                        <th scope="col" width="5%">NO. EMP</th>
                                                        <th scope="col" width="20%">NOMBRE</th>
                                                        <th scope="col" width="10%">F. SOL</th>
                                                        <th scope="col" width="10%">F. INI</th>
                                                        <th scope="col" width="10%">F. FIN</th>
                                                        <th scope="col" width="5%">DIAS</th>
                                                        <th scope="col" width="10%">NOTAS EMP</th>
                                                        <th scope="col" width="10%">NOTAS JE</th>
                                                        <th scope="col" width="10%">COMENT</th>
                                                        <th scope="col" width="5%">TIPO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
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


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery-3.7.1.js"></script>
    <script src = "vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src = "vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src = "js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/2.1.8/js/dataTables.js"></script>
    <script src="vendor/datatables/buttons/3.1.2/js/dataTables.buttons.min.js"></script>
    <script src="vendor/jszip/jszip.min.js"></script>
    <script src="vendor/datatables/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="vendor/datatables/buttons/3.1.2/js/buttons.print.min.js"></script>
    <script src="vendor/sweetalert2/sweetalert2.all.min.js"></script>

    <script src="js/mandarNomina.js"></script>
</body>
</html>
