<?php include 'acceso_admin.php'; ?>
<!DOCTYPE html>
<html lang = "sp">
<head>
    <meta charset = "utf-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE = edge">
    <meta name = "viewport" content = "width = device-width, initial-scale = 1, shrink-to-fit = no">
    <meta name = "description" content = "">
    <meta name = "author" content = "">

    <title>RRHH</title>

    <!-- Custom fonts for this template-->
    <link href = "vendor/fontawesome-free/css/all.min.css" rel = "stylesheet" type = "text/css">

    <!-- Custom styles for this template-->
    <link href = "css/sb-admin-2.min.css" rel = "stylesheet">
    <link rel="stylesheet" href="vendor/datatables/2.1.8/css/dataTables.dataTables.css" />
    <link href="vendor/select2/css/select2.min.css" rel="stylesheet" />
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

                <!-- encabezado.php (compartido) asigna el valor de la cookie a #noEmpleado -->
                <input type="hidden" id="noEmpleado">

                <!-- Begin Page Content -->
                <div class = "container-fluid">
                    <h1 class = "h3 mb-0 text-gray-800">Solicitudes de vacaciones y permisos</h1>

                    <!-- Content Row -->
                    <div class = "row">
                        <div class = "col-12">
                            <div class = "card shadow mb-2 w-100">
                                <div class = "card-body">

                                    <div class = "row">
                                        <div class="col-md-6 mb-1">
                                            <label for="filtro-ingeniero" class="mr-1">Filtrar por Ingeniero:</label>
                                            <select id="filtro-ingeniero" name="filtro-ingeniero" class="form-control mr-1">
                                                <option value="0">Selecciona...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end mb-2">
                                            <button class="btn btn-primary btn-sm w-100" onclick="llenaTablaVacaciones()"><i class="fas fa-fw fa-filter mr-1"></i>Filtrar</button>
                                        </div>
                                    </div>

                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active btn-outline-warning text-dark" type="button" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Lista Vacaciones</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn-outline-success text-dark" id="autorizadas-tab" data-toggle="tab" href="#autorizadas" role="tab" aria-controls="autorizadas" aria-selected="false">Servicios</a>
                                        </li>
                                    </ul><br>

                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane border-left-warning fade show active in" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <br>
                                            <table class="table table-sm table-hover w-100" id="TvacacionesPersonal" name="TvacacionesPersonal" style="border-bottom: 1px solid #e3e6f0;">
                                                <thead>
                                                    <tr class="bg-light text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05rem;">
                                                        <!-- id oculto -->
                                                        <th width="0%" class="font-weight-bold border-0 py-2 ">ID</th>
                                                        <th width="24%" class="font-weight-bold border-0 py-2">Empleado</th>        
                                                        <th scope="col" width="12%" class="font-weight-bold border-0 py-2">Fecha / Tipo</th>
                                                        <th scope="col" width="26%" class="font-weight-bold border-0 py-2">Periodo / Detalle Días</th>
                                                        <th scope="col" width="8%" class="font-weight-bold border-0 py-2 text-center">Coments</th>
                                                        <th scope="col" width="10%" class="font-weight-bold border-0 py-2">Estatus</th>
                                                        <th scope="col" width="10%" class="font-weight-bold border-0 py-2">Pago</th>
                                                        <th scope="col" width="10%" class="font-weight-bold border-0 py-2">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane border-left-success fade" id="autorizadas" role="tabpanel" aria-labelledby="autorizadas-tab">
                                            <table id="TSolAbiertas" class="table table-hover w-100">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Ingeniero</th>
                                                        <th>Area</th>
                                                        <th>OT</th>
                                                        <th>Fecha Planeada</th>
                                                        <th>Cliente</th>
                                                        <th>Ciudad</th>
                                                        <th>Estatus</th>
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
    <script src="vendor/select2/js/select2.min.js"></script>

    <script src="js/verVacaciones.js"></script>
</body>
</html>
