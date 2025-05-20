<?php
    session_start();
    include 'conn.php';
    if($_COOKIE['noEmpleado'] == '' || $_COOKIE['noEmpleado'] == null){
        echo '<script>window.location.assign("index")</script>';
    }
?>
<!DOCTYPE html>
<html lang = "en">

<head>

    <meta charset = "utf-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE = edge">
    <meta name = "viewport" content = "width = device-width, initial-scale = 1, shrink-to-fit = no">
    <meta name = "description" content = "">
    <meta name = "author" content = "">

    <title>RR HH</title>

    <!-- Custom fonts for this template-->
    <link href = "vendor/fontawesome-free/css/all.min.css" rel = "stylesheet" type = "text/css">
    <link
        href = "https://fonts.googleapis.com/css?family = Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel = "stylesheet">

    <!-- Custom styles for this template-->
    <link href = "css/sb-admin-2.min.css" rel = "stylesheet">

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
                        <h1 class = "h3 mb-0 text-gray-800">Módulo de solicitud de vacaciones</h1>                        
                    </div>

                    <!-- Content Row -->
                    <?php
                        include 'conteo.php';
                    ?>

                    <!-- Content Row -->

                    <div class = "row">
                        <!-- Periodico mural 
                        <center>
                        <p class="alert alert-info">
                            Estimado Equipo Mess,  dentro de los próximos días estarán habiendo modificaciones dentro de esta plataforma puesto que estamos en periodo de actualizaciones.
                            Agradecemos su paciencia y compresión
                        <p>                            
                        </center>
                        -->
                        <div class = "col-xl-7 col-lg-7">
                                <embed id="vistaPrevia" src='https://www.mess.com.mx/wp-content/uploads/2025/03/Marzo-2024.pdf#zoom=80' type="application/pdf" width="100%" height="550">
                        </div>
                        <!--AGENDA SALA DE JUNTAS-->
                            <div class="col-lg-5 mx-auto">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900">Agenda Sala de Juntas</h1>
                                </div>
                                <div class="p-4">
                                    <div class="text-center" id="calendar"></div>
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


    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src = "vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src = "vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src = "js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src = "vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src = "js/demo/chart-area-demo.js"></script>
    <script src = "js/demo/chart-pie-demo.js"></script>
    <script>
    $(document).ready(function () {
        verCalendarioLogin();
    });

        function verCalendarioLogin() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {        
                initialView: 'listWeek', // Cambiar a vista diaria
                events: 'SalaDeJuntas/acciones_calendarioGral.php?opcion=login', // Aquí llamas a tu PHP que devuelve las vacaciones en JSON
                editable: false,
                locale: 'es',
                eventContent: function(info) {
                    // Personalizar el contenido del evento
                    var nombreEmpleado = info.event.title;
                    var fechaInicio = info.event.start;
                    var fechaFin = info.event.end;
                    var descripcion = info.event.extendedProps.descripcion || 'Sin descripción'; // Obtener la descripción del evento
                    var displayText = nombreEmpleado + '<br>' + descripcion;

                    return { html: displayText };
                }
            });
            calendar.render();
        } 
    </script>
</body>

</html>