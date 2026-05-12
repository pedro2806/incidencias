<?php
include 'conn.php';
if ($_COOKIE['noEmpleado'] == '' || $_COOKIE['noEmpleado'] == null) {
    echo '<script>window.location.assign("index")</script>';
    exit;
}
$noEmp = intval($_COOKIE['noEmpleado']);
$stmtAcc = $conn->prepare("SELECT id FROM accesos_especiales WHERE noEmpleado = ? AND sistema = 'incidencias' AND opcion = 'verDescontadorDeDias' AND estatus = 1 LIMIT 1");
$stmtAcc->bind_param("i", $noEmp);
$stmtAcc->execute();
if ($stmtAcc->get_result()->num_rows === 0) {
    echo '<script>window.location.assign("inicio")</script>';
    exit;
}
$stmtAcc->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RRHH - Descontar Días</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body id="page-top">

    <div id="wrapper">
        <?php include 'menu.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'encabezado.php'; ?>

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Descontar Días</h1>
                    </div>

                    <!-- Formulario -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Nuevo Descuento</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">

                                <!-- Campos -->
                                <div class="col-md-7">
                                    <div class="row g-2 mb-3">
                                        <div class="col-sm-4">
                                            <label class="form-label">Fecha Inicio</label>
                                            <input type="date" id="fechaInicio" class="form-control form-control-sm" onchange="calcularDias()">
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label">Fecha Fin</label>
                                            <input type="date" id="fechaFin" class="form-control form-control-sm" onchange="calcularDias()">
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label">Días</label>
                                            <input type="number" id="noDias" class="form-control form-control-sm" readonly style="background:#f8f9fc;">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Razón</label>
                                        <textarea id="razon" class="form-control form-control-sm" rows="3" placeholder="Ej: Días con goce por política de empresa"></textarea>
                                    </div>
                                    <button class="btn btn-primary btn-sm" onclick="aplicarDescuento()">
                                        <i class="fas fa-minus-circle me-1"></i>Aplicar
                                    </button>
                                </div>

                                <!-- Lista de empleados -->
                                <div class="col-md-5">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label mb-0">
                                            Empleados &nbsp;<span class="badge text-bg-primary" id="countSeleccionados">0</span>
                                        </label>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary btn-sm" onclick="seleccionarTodos()">Todos</button>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="deseleccionarTodos()">Ninguno</button>
                                        </div>
                                    </div>
                                    <input type="text" id="buscarEmpleado" class="form-control form-control-sm mb-2" placeholder="Buscar empleado...">
                                    <div id="listaEmpleados" style="max-height:220px;overflow-y:auto;border:1px solid #dee2e6;padding:8px;border-radius:4px;">
                                        <small class="text-muted">Cargando...</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Historial -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <h6 class="m-0 font-weight-bold text-primary">Historial de Descuentos</h6>
                                <div class="d-flex align-items-center gap-2 ms-auto flex-wrap">
                                    <label class="mb-0 small">Fecha Inicio</label>
                                    <input type="date" id="filtroInicio" class="form-control form-control-sm" style="width:150px" onchange="cargarHistorial()">
                                    <label class="mb-0 small">Fecha Fin</label>
                                    <input type="date" id="filtroFin" class="form-control form-control-sm" style="width:150px" onchange="cargarHistorial()">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="$('#filtroInicio,#filtroFin').val('');cargarHistorial()">Limpiar</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-striped" id="ThistorialDescuentos">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Periodo</th>
                                        <th>Días</th>
                                        <th>Razón</th>
                                        <th>Aplicado por</th>
                                        <th>Fecha registro</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; MESS <?php echo date('Y'); ?></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

    <script>
    const dtLang = {
        decimal: "", emptyTable: "No hay información disponible",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "Mostrando 0 a 0 de 0 registros",
        infoFiltered: "(Filtrado de _MAX_ registros totales)",
        lengthMenu: "Mostrar _MENU_ registros", loadingRecords: "Cargando...",
        processing: "Procesando...", search: "Buscar:", zeroRecords: "No se encontraron resultados",
        paginate: { first: "Primero", last: "Último", next: "Siguiente", previous: "Anterior" }
    };

    $(document).ready(function () {
        $('#ThistorialDescuentos').DataTable({ language: dtLang, pageLength: 50 });
        cargarEmpleados();
        cargarHistorial();

        $('#buscarEmpleado').on('input', function () {
            var filtro = $(this).val().toLowerCase();
            $('#listaEmpleados .form-check').each(function () {
                $(this).toggle($(this).find('label').text().toLowerCase().includes(filtro));
            });
        });
    });

    function calcularDias() {
        var inicio = $('#fechaInicio').val();
        var fin    = $('#fechaFin').val();
        if (inicio && fin) {
            var d1 = new Date(inicio + 'T00:00:00');
            var d2 = new Date(fin + 'T00:00:00');
            if (d2 >= d1) {
                $('#noDias').val(Math.round((d2 - d1) / 86400000) + 1);
            } else {
                $('#noDias').val('');
            }
        }
    }

    function cargarEmpleados() {
        $.ajax({
            url: 'acciones_restardias.php', method: 'GET', dataType: 'json',
            data: { accion: 'obtenerEmpleados' },
            success: function (resp) {
                if (!resp.success) return;
                var html = '';
                resp.empleados.forEach(function (emp) {
                    html += '<div class="form-check">' +
                        '<input class="form-check-input check-empleado" type="checkbox"' +
                        ' value="' + emp.noEmpleado + '" id="emp_' + emp.noEmpleado + '" checked>' +
                        '<label class="form-check-label small" for="emp_' + emp.noEmpleado + '">' +
                        emp.nombre + '</label></div>';
                });
                $('#listaEmpleados').html(html);
                actualizarContador();
                $(document).on('change', '.check-empleado', actualizarContador);
            }
        });
    }

    function actualizarContador() {
        $('#countSeleccionados').text($('.check-empleado:checked').length);
    }

    function seleccionarTodos() {
        $('#listaEmpleados .form-check:visible .check-empleado').prop('checked', true);
        actualizarContador();
    }

    function deseleccionarTodos() {
        $('#listaEmpleados .form-check:visible .check-empleado').prop('checked', false);
        actualizarContador();
    }

    function cargarHistorial() {
        $.ajax({
            url: 'acciones_restardias.php', method: 'GET', dataType: 'json',
            data: { accion: 'obtenerHistorial', filtroInicio: $('#filtroInicio').val(), filtroFin: $('#filtroFin').val() },
            success: function (resp) {
                if (!resp.success) return;
                var table = $('#ThistorialDescuentos').DataTable();
                table.clear();
                resp.historial.forEach(function (r) {
                    table.row.add([
                        '<b>' + r.empleado + '</b>',
                        r.fecha_inicio + ' — ' + r.fecha_fin,
                        '<span class="badge text-bg-dark">' + r.dias + ' días</span>',
                        r.razon,
                        r.admin || '—',
                        r.fecha_registro
                    ]);
                });
                table.draw();
            }
        });
    }

    function aplicarDescuento() {
        var fechaInicio = $('#fechaInicio').val();
        var fechaFin    = $('#fechaFin').val();
        var dias        = parseInt($('#noDias').val()) || 0;
        var razon       = $('#razon').val().trim();
        var empleados   = [];
        $('.check-empleado:checked').each(function () { empleados.push($(this).val()); });

        if (!fechaInicio || !fechaFin || dias <= 0) {
            Swal.fire({ title: 'Fechas inválidas', text: 'Selecciona un rango de fechas válido', icon: 'warning' });
            return;
        }
        if (!razon) {
            Swal.fire({ title: 'Razón requerida', text: 'Escribe la razón del descuento', icon: 'warning' });
            return;
        }
        if (empleados.length === 0) {
            Swal.fire({ title: 'Sin empleados', text: 'Selecciona al menos un empleado', icon: 'warning' });
            return;
        }

        Swal.fire({
            title: '¿Aplicar?',
            text: dias + ' días corridos a ' + empleados.length + ' empleado(s). Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74a3b'
        }).then(function (result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url: 'acciones_restardias.php', method: 'POST', dataType: 'json',
                data: {
                    accion: 'descontarDias',
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin,
                    dias: dias,
                    razon: razon,
                    'empleados[]': empleados
                },
                success: function (resp) {
                    if (resp.success) {
                        Swal.fire({ title: '¡Listo!', text: resp.message, icon: 'success' }).then(function () {
                                        $('#fechaInicio, #fechaFin, #noDias, #razon').val('');
                                        $('#buscarEmpleado').val('').trigger('input');
                                        seleccionarTodos();
                                        cargarHistorial();
                                    });
                    } else {
                        Swal.fire({ title: 'Error', text: resp.message, icon: 'error' });
                    }
                },
                error: function () {
                    Swal.fire({ title: 'Error', text: 'No se pudo conectar con el servidor', icon: 'error' });
                }
            });
        });
    }
    </script>
</body>
</html>
