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

    <title>RRHH</title>

    <!-- Custom fonts for this template-->
    <link href = "vendor/fontawesome-free/css/all.min.css" rel = "stylesheet" type = "text/css">

    <!-- Custom styles for this template-->
    <link href = "css/sb-admin-2.min.css" rel = "stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <!--<link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    
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
                    include 'encabezado.php';
                ?>
                

                <!-- Begin Page Content -->
                <div class = "container-fluid">
                    <h1 class = "h3 mb-2 text-gray-800">Solicitudes de vacaciones y permisos</h1>
                    <p class = "mb-4">Consulta el estatus de solicitudes de vacaciones  y permisos.</p>

                    <!-- Content Row -->

                    <div class = "row">
                    <!-- Content Row -->
                    <div class = "card shadow mb-2">
                        <div class = "card-body">
                            <div class = "row">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active btn-outline-warning text-dark" type="button" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Lista de solicitudes</a>
                                    </li>
                                </ul><br>
                                                                        
                                <div class="tab-content" id="myTabContent">                                    
                                    <br>
                                    <div class="tab-pane border-left-warning fade show active in" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <table class="table table-sm table-striped" id = "TporAutorizar" name = "TporAutorizar">
                                            <thead class = "table-primary">
                                                <tr>
                                                    <th scope="col" width="24%">Empleado</th>
                                                    <th scope="col" width="12%">T. de solicitud</th>
                                                    <th scope="col" width="12%">Fecha de sol</th>
                                                    <th scope="col" width="20%">No. días / Periodo</th>
                                                    <th scope="col" width="20%">Comentarios</th>
                                                    <th scope="col" width="8%">Estatus</th>
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
    

    <script type="text/javascript">
    
        $(document).ready(function () {
                        
            llenaTablaPorAutorizar();
            
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
                },
                columnDefs: [
                    {
                        targets: 2,  // Índice de la columna que tiene las fechas
                        render: function (data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                var date = new Date(data);
                                date.setMinutes(date.getMinutes() + date.getTimezoneOffset()); // Corrige el desfase de UTC
                                return ('0' + date.getDate()).slice(-2) + '/' +
                                        ('0' + (date.getMonth() + 1)).slice(-2) + '/' +
                                        date.getFullYear();
                            }

                            return data;
                        }
                    }
                ]
            });
            
        });
        
        function llenaTablaAutorizadas() {
            
                    opcion = "llenaTablaAutorizadas";
                    cookieNoEmpleado="403";
                    cookieRol="1";
                    $.ajax({
                        url: 'funciones_select.php', // La URL del script PHP que obtendrá los datos
                        method: 'GET',
                        dataType: 'json', //TIPO DE DATO JSON
                        data:{opcion, cookieNoEmpleado, cookieRol}, //Los parametros que se envian
                        success: function(registros) {
                            
                            var table = $('#Tautorizadas').DataTable();
                            
                            table.clear().draw();
                            
                            registros.forEach(function(Registro) { //EL valor que se recibe
                                var tSolicitudBadge = '';
                                if (Registro.tSolicitud == 1) {
                                    tSolicitudBadge = '<span class="badge text-bg-success">Vacaciones</span>';
                                } else if (Registro.tSolicitud == 2) {
                                    tSolicitudBadge = '<span class="badge text-bg-info text-white">Permiso sin goce</span>';
                                } else if (Registro.tSolicitud == 3) {
                                    tSolicitudBadge = '<span class="badge text-bg-primary">Permiso con goce</span>';
                                }
                                
                                var EstatusBadge = '';
                                if(Registro.Estatus == 1){
                                    EstatusBadge = '<span class="badge text-bg-warning">Por autorizar</span>';
                                }
                                if(Registro.Estatus == 2){
                                    EstatusBadge = '<span class="badge text-bg-success">Autorizada</span>';
                                }
                                if(Registro.autoriza == 3){
                                    EstatusBadge = '<span class="badge text-bg-danger">Rechazada/Cancelada</span>';
                                } 
                                
                                var EstatusRH = '';
                                if(Registro.autorizaRH == 1){
                                    EstatusRH = '<span class="badge text-bg-warning">Por autorizar RH</span>';
                                }
                                if(Registro.autorizaRH == 2){
                                    EstatusRH = '<span class="badge text-bg-success">Autorizada RH</span>';
                                }
                                if(Registro.autorizaRH == 3){
                                    EstatusRH = '<span class="badge text-bg-danger">Rechazada/Cancelada RH</span>';
                                } 
                                
                                table.row.add([
                                    '<b>' + Registro.usuario,
                                    tSolicitudBadge,
                                    Registro.FechaBien,
                                    '<span class="badge text-bg-dark"><b>' + Registro.noDias + ' días</b></span><span class="badge text-bg-light"><b>' + Registro.Dgozados + ' gozados</b></span><span class="badge text-bg-warning"><b>' + Registro.diasDisp + ' Rest</b></span><br> <b>' + Registro.Finicio + ' - ' + Registro.FFin +'</b>',
                                    '<p style="margin-bottom: 0px; margin-top: 0px;"><small>Grales:' + Registro.Comentarios + '</small></p><p style="margin-bottom: 0px; margin-top: 0px;"><small>Colab:' + Registro.ComentariosE + '</small></p><p style="margin-bottom: 0px;"><small>Jefe:' + Registro.ComentariosJ + '</small></p>',
                                    EstatusBadge+EstatusRH,
                                    '<a class="btn btn-outline-primary btn-circle btn-sm" href="#" data-toggle="modal" data-target="#modalEdita" onClick="llenaInfoEditar('+ Registro.id+',\''+ Registro.fSolicitud+'\', '+Registro.noEmpleado+',\''+ Registro.usuario+'\', '+ Registro.Dgozados+')">'+
                                        '<i class="fas fa-edit"></i>'+
                                    '</a>'
                                    ]).draw(false);
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            
                            //console.error('Error al obtener los equipos:', textStatus, errorThrown);
                        }
                    });
        }
        
        function llenaInfo(estatusRH, id, estatus, accion) {
            $('#idSolicitud').val(id);
            $('#estatusSol').val(estatus);
            $('#estatusRH').val(estatusRH);
            $('#accion').val(accion);
        }
        
    </script>
</body>
</html>