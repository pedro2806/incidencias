<?php 
include 'conn.php';
    if($_COOKIE['noEmpleado'] == '' || $_COOKIE['noEmpleado'] == null){
        echo '<script>window.location.assign("index")</script>';
    }
?>
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
    <!--<link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
    
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
                        <h1 class = "h3 mb-0 text-gray-800">Reporte Nomina</h1>                        
                    </div>

                    <?php
                      //  include 'conteo.php';
                    ?>

                    <!-- Content Row -->

                    <div class = "row">
                    <!-- Content Row -->
                    <div class = "card shadow mb-2">
                        <div class = "card-body">
                            <div class = "row">
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
                                            
                                            <button type="button" class="btn btn-primary" onClick="mandarNomina()">Generar Reporte Nómina / Mandar Nómina</button><br><br>
                                            
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
                                            
                                            <button type="button" class="btn btn-success" onClick="mandarPagado()">Marcar como pagado</button><br><br>
                                            
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
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src = "vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src = "vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src = "js/sb-admin-2.min.js"></script>    
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <!--<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" defer="defer"></script>-->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    

    <script type="text/javascript">
    
        $(document).ready(function () {
            
            llenaTablaPagadas();
            llenaTablaEnNomina();
            llenaTablaPorPagar();
            
            $('#TporPagar').DataTable({
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
                },
                columnDefs: [{}]
                
            });
            
            $('#TenNomina').DataTable({
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
                },
                columnDefs: [{}],
                dom: '<"row"<"col-md-6"B><"col-md-6"f>>rtip',  // Personalización del layout
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Exportar a Excel', // Texto del botón
                        filename: function() {
                        // Obtener la fecha y hora actual
                        var date = new Date();
                        var year = date.getFullYear();
                        var month = ('0' + (date.getMonth() + 1)).slice(-2); // Añadir 0 a la izquierda
                        var day = ('0' + date.getDate()).slice(-2); // Añadir 0 a la izquierda
                        var hours = ('0' + date.getHours()).slice(-2); // Añadir 0 a la izquierda
                        var minutes = ('0' + date.getMinutes()).slice(-2); // Añadir 0 a la izquierda
                        var seconds = ('0' + date.getSeconds()).slice(-2); // Añadir 0 a la izquierda
        
                        // Formato del nombre del archivo: solicitudes-YYYY-MM-DD-HH-MM-SS
                        return 'Solicitudes por pagar -' + year + '-' + month + '-' + day + '-' + hours + '-' + minutes + '-' + seconds;
                    },
                    title: 'Solicitudes por pagar' // Título dentro del archivo Excel
                    }
                ]
            });


            
            $('#Tpagadas').DataTable({
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
                },
                columnDefs: [{}]
            });
        });
        
        function llenaTablaPagadas() {
            
                    opcion = "llenaTablaPagadas";
                    cookieNoEmpleado="403";
                    cookieRol="1";
                    $.ajax({
                        url: 'acciones_mandarNomina.php', // La URL del script PHP que obtendrá los datos
                        method: 'GET',
                        dataType: 'json', //TIPO DE DATO JSON
                        data:{opcion, cookieNoEmpleado, cookieRol}, //Los parametros que se envian
                        success: function(registros) {
                            
                            var table = $('#Tpagadas').DataTable();
                            
                            table.clear().draw();
                            
                            registros.forEach(function(Registro) { //EL valor que se recibe
                                var tSolicitudBadge = '';
                                if (Registro.tipo == 1) {
                                    tSolicitudBadge = 'Vacaciones';
                                } else if (Registro.tipo == 2) {
                                    tSolicitudBadge = 'Permiso sin goce';
                                } else if (Registro.tipo == 3) {
                                    tSolicitudBadge = 'Permiso con goce';
                                }
                                
                                table.row.add([
                                    Registro.id,
                                    Registro.noEmpleado,
                                    Registro.nombre,
                                    Registro.fesolicitud,
                                    Registro.feinicio,
                                    Registro.fefin,
                                    Registro.dias,
                                    Registro.notasempleado,
                                    Registro.notajefe,
                                    Registro.comentarios,
                                    tSolicitudBadge
                                    ]).draw(false);
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            
                            //console.error('Error al obtener los equipos:', textStatus, errorThrown);
                        }
                    });
        }
        
        function llenaTablaEnNomina() {//TenNomina
                    opcion = "llenaTablaEnNomina";
                    cookieNoEmpleado="403";
                    cookieRol="1";
                    
                    $.ajax({
                        url: 'acciones_mandarNomina.php', // La URL del script PHP que obtendrá los datos
                        method: 'GET',
                        dataType: 'json', //TIPO DE DATO JSON
                        data:{opcion, cookieNoEmpleado, cookieRol}, //Los parametros que se envian
                        success: function(registros) {
                            
                            var table = $('#TenNomina').DataTable();
                            
                            table.clear().draw();
                            
                            registros.forEach(function(Registro) { //EL valor que se recibe
                                var tSolicitudBadge = '';
                                if (Registro.tipo == 1) {
                                    tSolicitudBadge = 'Vacaciones';
                                } else if (Registro.tipo == 2) {
                                    tSolicitudBadge = 'Permiso sin goce';
                                } else if (Registro.tipo == 3) {
                                    tSolicitudBadge = 'Permiso con goce';
                                } 
                                
                                table.row.add([
                                    Registro.id,
                                    Registro.noEmpleado,
                                    Registro.nombre,
                                    Registro.fesolicitud,
                                    Registro.feinicio,
                                    Registro.fefin,
                                    Registro.dias,
                                    Registro.notasempleado,
                                    Registro.notajefe,
                                    Registro.comentarios,
                                    tSolicitudBadge
                                    ]).draw(false);
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            //console.error('Error al obtener los equipos:', textStatus, errorThrown);
                        }
                    });
        }
        
        function llenaTablaPorPagar() {
                    opcion = "llenaTablaPorPagar";
                    cookieNoEmpleado="403";
                    cookieRol="1";
                    $.ajax({
                        url: 'acciones_mandarNomina.php', // La URL del script PHP que obtendrá los datos
                        method: 'GET',
                        dataType: 'json', //TIPO DE DATO JSON
                        data:{opcion, cookieNoEmpleado, cookieRol}, //Los parametros que se envian
                        success: function(registros) {
                            
                            var table = $('#TporPagar').DataTable();
                            
                            table.clear().draw();
                            
                            registros.forEach(function(Registro) { //EL valor que se recibe
                                var tSolicitudBadge = '';
                                if (Registro.tipo == 1) {
                                    tSolicitudBadge = 'Vacaciones';
                                } else if (Registro.tipo == 2) {
                                    tSolicitudBadge = 'Permiso sin goce';
                                } else if (Registro.tipo == 3) {
                                    tSolicitudBadge = 'Permiso con goce';
                                }
                                
                                table.row.add([
                                    Registro.id,
                                    Registro.noEmpleado,
                                    Registro.nombre,
                                    Registro.fesolicitud,
                                    Registro.feinicio,
                                    Registro.fefin,
                                    Registro.dias,
                                    Registro.notasempleado,
                                    Registro.notajefe,
                                    Registro.comentarios,
                                    tSolicitudBadge
                                    ]).draw(false);
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            //console.error('Error al obtener los equipos:', textStatus, errorThrown);
                        }
                    });
        }
        
        function mandarNomina (){
                opcion = "mandarNomina";
                
                Swal.fire({
                  title: "Estás seguro de generar el reporte?",
                  text: "Se cambiaran a por pagar las solicitudes",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  cancelButtonText: "Cancelar",
                  confirmButtonText: "Procesar solicitudes"
                }).then((result) => {
                  if (result.isConfirmed) {
                        //SE MANDA A NOMINA
                        $.ajax({
                            url: 'acciones_mandarNomina.php',
                            method: 'GET',
                            dataType: 'json',
                            data:{opcion},
                            success: function(Registros) {
                                Swal.fire({
                                    title: "Confirmado!",
                                    text: "Se procesaron las solicitudes con éxito!",
                                    icon: "success"
                                }).then(function() {
                                    
                                });
                            },error: function(jqXHR, textStatus, errorThrown) {
                              //swal.fire('Error al aplicar el cambio.', error);
                            }
                        });
                        
                  }
                });

        }
        
        function mandarPagado (){
                opcion = "mandarPagado";
                
                Swal.fire({
                  title: "Estás seguro de marcar como pagados?",
                  text: "Se pondrán como pagadas las solicitudes",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  cancelButtonText: "Cancelar",
                  confirmButtonText: "Procesar solicitudes"
                }).then((result) => {
                  if (result.isConfirmed) {
                        //SE MANDA A NOMINA
                        $.ajax({
                            url: 'acciones_mandarNomina.php',
                            method: 'GET',
                            dataType: 'json',
                            data:{opcion},
                            success: function(Registros) {
                                Swal.fire({
                                    title: "Confirmado!",
                                    text: "Se procesaron las solicitudes con éxito!",
                                    icon: "success"
                                }).then(function() {
                                    
                                });
                            },error: function(jqXHR, textStatus, errorThrown) {
                              //swal.fire('Error al aplicar el cambio.', error);
                            }
                        });
                        
                  }
                });

        }
       
    </script>
</body>
</html>