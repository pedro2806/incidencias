<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sala de Juntas - Modificar Reserva">
    <meta name="author" content="MESS Team">
    <meta name="keywords" content="Sala de Juntas, Modificar, Reservas, Gestión">
    <title>Sala de Juntas - Modificar Reserva</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">   
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'menu.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include '../encabezado.php'; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Mis Reservas</h1>
                    </div>

                    <div class="container-fluid">
                        <ul class="nav nav-tabs" id="reservasTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active btn-outline-success" id="activas-tab" data-bs-toggle="tab" href="#activas" role="tab" aria-controls="activas" aria-selected="true">Activas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-outline-warning" id="canceladas-tab" data-bs-toggle="tab" href="#canceladas" role="tab" aria-controls="canceladas" aria-selected="false">Canceladas</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="reservasTabContent">
                            <!-- Pestaña Activas -->
                            <div class="tab-pane fade show active" id="activas" role="tabpanel" aria-labelledby="activas-tab">
                                <div class="table-responsive">
                                    <table id="tablaActivas" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Fin</th>
                                                <th>Descripción</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Las filas se llenarán mediante AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Pestaña Canceladas -->
                            <div class="tab-pane fade" id="canceladas" role="tabpanel" aria-labelledby="canceladas-tab">
                                <div class="table-responsive">
                                    <table id="tablaCanceladas" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Fin</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Las filas se llenarán mediante AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; MESS 2025</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Modal para editar reserva -->
    <div class="modal fade" id="modalEditarReserva" tabindex="-1" aria-labelledby="modalEditarReservaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarReservaLabel">Editar Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarReserva">
                        <input type="hidden" id="id" name="id">
                        <div class="mb-3">
                            <label for="fecha_hora_inicio" class="form-label">Fecha y hora de inicio:</label>
                            <input type="datetime-local" class="form-control" id="fecha_hora_inicio" name="fecha_hora_inicio" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_hora_fin" class="form-label">Fecha y hora de fin:</label>
                            <input type="datetime-local" class="form-control" id="fecha_hora_fin" name="fecha_hora_fin" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <button type="button" class="btn btn-success" id="btnGuardarCambios">Confirmar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            // Cargar las reservas activas al cargar la página
            cargarReservas();
            // Cargar las reservas canceladas al cargar la página
            cargarReservasCanceladas();
            
            //Tabla "Reservas Activas" en Español
            $('#tablaActivas').DataTable({
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

            //Tabla "Reservas Canceladas" en Español
            $('#tablaCanceladas').DataTable({
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
 
            // Inicializar DataTables
            $('#tablaActivas').DataTable();
            $('#tablaCanceladas').DataTable();

            // Manejar el cambio de pestañas
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                const target = $(e.target).attr("href"); // ID de la pestaña activa
                if (target === '#activas') {
                    cargarReservas(); // Cargar reservas activas
                } else if (target === '#canceladas') {
                    cargarReservasCanceladas(); // Cargar reservas canceladas
                }
            });
        });

        // Función para cargar las reservas
        function cargarReservas() {
            $.ajax({
                url: 'acciones_agendarSala', // Archivo PHP que devuelve los datos
                type: 'POST',
                dataType: 'json',
                data: { accion: 'obtenerReservas' }, // Acción específica para obtener las reservas
                success: function (response) {
                    if (response.success) {
                        // Limpiar el tbody de la tabla
                        const tbody = $('#tablaActivas tbody');
                        tbody.empty();

                        // Agregar las filas a la tabla
                        response.data.forEach(reserva => {
                            const fila = `
                                <tr>
                                    <td>${reserva.id}</td>
                                    <td>${reserva.fecha_hora_inicio}</td>
                                    <td>${reserva.fecha_hora_fin}</td>
                                    <td>${reserva.descripcion}</td>
                                    <td>
                                        <button class="btn btn-outline-warning btn-sm" onclick="editarReserva(${reserva.id})">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="eliminarReserva(${reserva.id})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                            tbody.append(fila);
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: response.message || "No se pudieron obtener las reservas.",
                            icon: "error"
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: "Error",
                        text: "Error al procesar la solicitud.",
                        icon: "error"
                    });
                }
            });
        }

        // Función para cargar las reservas canceladas
        function cargarReservasCanceladas() {
            $.ajax({
                url: 'acciones_agendarSala', 
                type: 'POST',
                dataType: 'json',
                data: { accion: 'VerReservasCanceladas' }, 
                success: function (response) {
                    if (response.success) {
                        const tbody = $('#tablaCanceladas tbody');
                        tbody.empty();

                        // Agregar las filas a la tabla
                        response.data.forEach(reserva => {
                            const fila = `
                                <tr>
                                    <td>${reserva.id}</td>
                                    <td>${reserva.fecha_hora_inicio}</td>
                                    <td>${reserva.fecha_hora_fin}</td>
                                    <td>${reserva.descripcion}</td>
                                </tr>
                            `;
                            tbody.append(fila);
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: response.message || "No se pudieron obtener las reservas canceladas.",
                            icon: "error"
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: "Error",
                        text: "Error al procesar la solicitud.",
                        icon: "error"
                    });
                }
            });
        }

        // Función para editar una reserva
        function editarReserva(id) {
            $.ajax({
                url: 'acciones_agendarSala',
                type: 'POST',
                dataType: 'json',
                data: { accion: 'obtenerReserva', id: id },
                success: function (response) {
                    if (response.success) {
                        // Llenar el formulario del modal con los datos de la reserva
                        $('#id').val(response.data.id);
                        $('#fecha_hora_inicio').val(response.data.fecha_hora_inicio);
                        $('#fecha_hora_fin').val(response.data.fecha_hora_fin);
                        $('#descripcion').val(response.data.descripcion);

                        // Mostrar el modal
                        $('#modalEditarReserva').modal('show');
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: response.message || "No se pudo obtener la información de la reserva.",
                            icon: "error"
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: "Error",
                        text: "Error al procesar la solicitud.",
                        icon: "error"
                    });
                }
            });
        };

        // Función para guardar los cambios de la reserva
        $('#btnGuardarCambios').click(function () {
            const id = $('#id').val();
            const finicio = $('#fecha_hora_inicio').val();
            const ffin = $('#fecha_hora_fin').val();
            const descripcion = $('#descripcion').val();

            // Validar que los campos no estén vacíos
            if (!finicio || !ffin || !descripcion) {
                Swal.fire({
                    title: "Espera",
                    text: "Por favor, completa todos los campos.",
                    icon: "warning"
                });
                return;
            }

            // Enviar los datos actualizados mediante AJAX
            $.ajax({
                url: 'acciones_agendarSala',
                type: 'POST',
                dataType: 'json',
                data: { accion: 'actualizaSolicitud', id, finicio, ffin, descripcion },
                success: function (response) {
                    Swal.fire({
                        title: "Éxito",
                        text: "Reserva actualizada con éxito.",
                        icon: "success"
                    }).then(() => {
                        $('#modalEditarReserva').modal('hide');
                        location.reload();
                    });
                },
                error: function () {
                    Swal.fire({
                        title: "Error",
                        text: "Error al procesar la solicitud.",
                        icon: "error"
                    });
                }
            });
        });
        
        // Función para eliminar una reserva
        function eliminarReserva(id) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción cancelará la reserva. ¿Deseas continuar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, cancelar",
                cancelButtonText: "No, volver"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'acciones_agendarSala',
                        type: 'POST',
                        dataType: 'json',
                        data: { accion: 'eliminaSolicitud', id: id }, 
                        success: function (response) {
                            if (response) {
                                Swal.fire({
                                    title: "Éxito",
                                    text: "Reserva cancelada con éxito.",
                                    icon: "success"
                                }).then(() => {
                                    cargarReservas(); 
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: "No se pudo cancelar la reserva. Inténtalo de nuevo.",
                                    icon: "error"
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                title: "Error",
                                text: "Error al procesar la solicitud.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>