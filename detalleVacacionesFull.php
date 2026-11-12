<?php
// Incluimos tu archivo de conexión real
include 'conn.php';

// Obtener el noEmpleado seleccionado (si no hay selección, se queda en null para no cargar nada al inicio)
$noEmpleadoSeleccionado = isset($_GET['noEmpleado']) && $_GET['noEmpleado'] !== '' ? $_GET['noEmpleado'] : null;

// 1. Obtener lista de empleados para el <select>
$listaEmpleados = [];
$resEmpleados = $conn->query("SELECT noEmpleado, CONCAT(nombres, ' ', apellidos) AS nombre_completo FROM usuarios WHERE estatus = 1 ORDER BY nombres ASC");
if ($resEmpleados) {
    while ($row = $resEmpleados->fetch_assoc()) {
        $listaEmpleados[] = $row;
    }
}

$empleadoInfo = null;
$detalleSolicitudes = [];

if ($noEmpleadoSeleccionado) {
    // 2. Consulta Principal usando COALESCE para evitar nulos en departamento y puesto
    $queryPrincipal = "
        SELECT 
            u.noEmpleado,
            CONCAT(u.nombres, ' ', u.apellidos) AS nombre_completo,
            u.antiguedad, 
            COALESCE(d.departamento, 'Sin departamento actualizado') AS departamento, 
            COALESCE(p.puesto, 'Sin puesto actualizado') AS puesto, 
            COALESCE(j.nombres, 'Sin jefe asignado') AS jefe, 
            u.fechaIngreso, 
            COALESCE(dv_actual.dias, 0) AS dias_ley_actual, 
            COALESCE(dv_anterior.dias, 0) AS dias_ley_anterior,
            COALESCE(sol_ant.total_dias, 0) AS diasSolAnterior,
            COALESCE(sol_act.total_dias, 0) AS diasSol, 
            
            -- Días solicitados de más en el periodo anterior (que se descuentan)
            GREATEST(0, COALESCE(sol_ant.total_dias, 0) - COALESCE(dv_anterior.dias, 0)) AS dias_excedidos_anterior,
            
            ( 
                (COALESCE(dv_actual.dias, 0) - GREATEST(0, COALESCE(sol_ant.total_dias, 0) - COALESCE(dv_anterior.dias, 0))) 
                - 
                COALESCE(sol_act.total_dias, 0)
            ) AS diasdisponibles, 
            u.inicio_actual, u.fin_actual,
            u.inicio_anterior, u.fin_anterior
        FROM ( 
            SELECT 
                noEmpleado, nombres, apellidos, fechaIngreso, jefe, departamento, puesto, 
                TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) AS antiguedad, 
                DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) YEAR) AS inicio_actual,
                DATE_SUB(DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) + 1 YEAR), INTERVAL 1 DAY) AS fin_actual,
                DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) - 1 YEAR) AS inicio_anterior,
                DATE_SUB(DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) YEAR), INTERVAL 1 DAY) AS fin_anterior
            FROM usuarios WHERE noEmpleado = ? 
        ) u 
        LEFT JOIN usuarios j ON u.jefe = j.noEmpleado 
        LEFT JOIN departamento d ON u.departamento = d.id 
        LEFT JOIN puesto p ON u.puesto = p.id 
        LEFT JOIN diasvacaciones dv_actual ON dv_actual.anio = u.antiguedad 
        LEFT JOIN diasvacaciones dv_anterior ON dv_anterior.anio = GREATEST(0, u.antiguedad - 1) 
        LEFT JOIN (
            SELECT empleado, SUM(dias) AS total_dias
            FROM solicitudes 
            WHERE estatus = 2 AND autorizaRH = 2 AND tipo = 1
              AND fesolicitud BETWEEN (SELECT DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) - 1 YEAR) FROM usuarios WHERE noEmpleado = ?)
              AND (SELECT DATE_SUB(DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) YEAR), INTERVAL 1 DAY) FROM usuarios WHERE noEmpleado = ?)
            GROUP BY empleado
        ) sol_ant ON sol_ant.empleado = u.noEmpleado
        LEFT JOIN (
            SELECT empleado, SUM(dias) AS total_dias
            FROM solicitudes 
            WHERE estatus = 2 AND autorizaRH = 2 AND tipo = 1
              AND fesolicitud BETWEEN (SELECT DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) YEAR) FROM usuarios WHERE noEmpleado = ?)
              AND (SELECT DATE_SUB(DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) + 1 YEAR), INTERVAL 1 DAY) FROM usuarios WHERE noEmpleado = ?)
            GROUP BY empleado
        ) sol_act ON sol_act.empleado = u.noEmpleado;
    ";
    
    $stmtEmp = $conn->prepare($queryPrincipal);
    $stmtEmp->bind_param("sssss", $noEmpleadoSeleccionado, $noEmpleadoSeleccionado, $noEmpleadoSeleccionado, $noEmpleadoSeleccionado, $noEmpleadoSeleccionado);
    $stmtEmp->execute();
    $resultadoEmp = $stmtEmp->get_result();
    $empleadoInfo = $resultadoEmp->fetch_assoc();

    // 3. Consulta para el detalle de solicitudes incluyendo feinicio, fefin, pago y validación de duplicados
    $queryDetalle = "
        SELECT 
            s.fesolicitud AS fecha_solicitud,
            s.feinicio,
            s.fefin,
            s.dias,
            s.tipo,
            s.estatus,
            s.pago,
            CASE 
                WHEN s.fesolicitud BETWEEN u.inicio_actual AND u.fin_actual THEN 'Periodo Actual'
                WHEN s.fesolicitud BETWEEN u.inicio_anterior AND u.fin_anterior THEN 'Periodo Anterior'
                WHEN s.fesolicitud < u.inicio_anterior THEN 'Histórico (Más de 2 periodos)'
                ELSE 'Próximo / Otro'
            END AS periodo,
            CASE 
                WHEN s.fesolicitud BETWEEN u.inicio_actual AND u.fin_actual THEN CONCAT(u.inicio_actual, ' al ', u.fin_actual)
                WHEN s.fesolicitud BETWEEN u.inicio_anterior AND u.fin_anterior THEN CONCAT(u.inicio_anterior, ' al ', u.fin_anterior)
                ELSE 'Anterior a los últimos periodos'
            END AS rango_periodo,
            (
                SELECT COUNT(*) 
                FROM solicitudes dup 
                WHERE dup.empleado = s.empleado 
                  AND dup.feinicio = s.feinicio 
                  AND dup.fefin = s.fefin 
                  AND dup.estatus = 2 
                  AND dup.autorizaRH = 2 
                  AND dup.tipo = 1
            ) AS veces_repetida
        FROM solicitudes s
        CROSS JOIN (
            SELECT 
                fechaIngreso,
                DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) YEAR) AS inicio_actual,
                DATE_SUB(DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) + 1 YEAR), INTERVAL 1 DAY) AS fin_actual,
                DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) - 1 YEAR) AS inicio_anterior,
                DATE_SUB(DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) YEAR), INTERVAL 1 DAY) AS fin_anterior
            FROM usuarios 
            WHERE noEmpleado = ?
        ) u
        WHERE s.empleado = ? 
          AND s.estatus = 2 
          AND s.autorizaRH = 2 
          AND s.tipo = 1
        ORDER BY s.fesolicitud DESC;
    ";
    
    $stmtDet = $conn->prepare($queryDetalle);
    $stmtDet->bind_param("ss", $noEmpleadoSeleccionado, $noEmpleadoSeleccionado);
    $stmtDet->execute();
    $resultadoDet = $stmtDet->get_result();
    while($rowDet = $resultadoDet->fetch_assoc()) {
        $detalleSolicitudes[] = $rowDet;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tablero de Vacaciones</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Select2 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
    <style>
        .card-compact .card-body {
            padding: 0.5rem 1rem !important;
        }
        .table-custom th {
            background-color: #f8f9fc;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #b7b9cc;
            border-bottom: 2px solid #e3e6f0 !important;
        }
        .table-custom td {
            vertical-align: middle !important;
            padding: 1rem 0.75rem !important;
            color: #3a3b45;
        }
        .badge-tipo {
            background-color: #eaecf4;
            color: #6e707e;
            font-weight: 600;
            padding: 0.35em 0.65em;
            font-size: 75%;
            border-radius: 4px;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">
        <?php include 'menu.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'encabezado.php'; ?>

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-fw fa-calendar-alt"></i> Tablero de Control de Vacaciones</h1>

                    <!-- Selector de Empleado con Select2 -->
                    <div class="card shadow mb-3">
                        <div class="card-body py-2">
                            <form id="formEmpleado" method="GET" action="" class="form-inline">
                                <label class="mr-2 font-weight-bold text-primary" for="noEmpleado">Seleccionar Empleado:</label>
                                <select name="noEmpleado" id="noEmpleado" class="form-control mr-3 col-md-6">
                                    <option value="">-- Buscar por nombre o número de empleado --</option>
                                    <?php foreach ($listaEmpleados as $emp): ?>
                                        <option value="<?= $emp['noEmpleado'] ?>" <?= ($emp['noEmpleado'] == $noEmpleadoSeleccionado) ? 'selected' : '' ?>>
                                            [<?= $emp['noEmpleado'] ?>] <?= htmlspecialchars($emp['nombre_completo']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </div>

                    <?php if ($empleadoInfo): ?>
                        <!-- Información General Integrada -->
                        <div class="card shadow mb-3 border-left-info card-compact">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3 border-right py-1">
                                        <h6 class="font-weight-bold text-dark mb-0"><?= htmlspecialchars($empleadoInfo['nombre_completo']) ?></h6>
                                        <small class="text-primary font-weight-bold">No. Emp: <?= $empleadoInfo['noEmpleado'] ?></small> | 
                                        <small class="text-muted">Depto: <?= htmlspecialchars($empleadoInfo['departamento']) ?></small>
                                    </div>
                                    <div class="col-md-3 border-right px-3 py-1">
                                        <small class="d-block text-muted"><strong>Puesto:</strong> <?= htmlspecialchars($empleadoInfo['puesto']) ?></small>
                                        <small class="d-block text-muted"><strong>Jefe:</strong> <?= htmlspecialchars($empleadoInfo['jefe']) ?></small>
                                    </div>
                                    <div class="col-md-3 border-right px-3 py-1">
                                        <small class="d-block text-muted"><strong>Ingreso:</strong> <?= $empleadoInfo['fechaIngreso'] ?></small>
                                        <small class="d-block text-muted"><strong>Antigüedad:</strong> <span class="text-success font-weight-bold"><?= $empleadoInfo['antiguedad'] ?> años</span></small>
                                    </div>
                                    <div class="col-md-3 pl-3 py-1">
                                        <small class="d-block text-primary"><strong>Días de Ley (Actual):</strong> <span class="font-weight-bold"><?= $empleadoInfo['dias_ley_actual'] ?> días</span></small>
                                        <small class="d-block text-success"><strong>Disfrutados (Actual):</strong> <span class="font-weight-bold"><?= $empleadoInfo['diasSol'] ?> días</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjetas de Resumen Restantes -->
                        <div class="row">
                            <!-- 1. Días Disfrutados Periodo Anterior -->
                            <div class="col-xl-4 col-md-6 mb-3">
                                <div class="card border-left-info shadow h-100 card-compact">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-0">Disfrutados (Anterior)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $empleadoInfo['diasSolAnterior'] ?> días</div>
                                            <small class="text-muted" style="font-size: 70%;">Del <?= $empleadoInfo['inicio_anterior'] ?> al <?= $empleadoInfo['fin_anterior'] ?></small>
                                        </div>
                                        <i class="fas fa-history fa-2x text-gray-200"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Días Excedidos del Periodo Anterior -->
                            <div class="col-xl-4 col-md-6 mb-3">
                                <div class="card border-left-danger shadow h-100 card-compact">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-0">Días de más (Periodo Anterior)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $empleadoInfo['dias_excedidos_anterior'] ?> días</div>
                                            <small class="text-danger font-weight-bold" style="font-size: 70%;">Descontados del actual</small>
                                        </div>
                                        <i class="fas fa-exclamation-triangle fa-2x text-gray-200"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Balance Total Disponible -->
                            <div class="col-xl-4 col-md-6 mb-3">
                                <div class="card border-left-warning shadow h-100 card-compact">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-0">Total Disponibles</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $empleadoInfo['diasdisponibles'] ?> días</div>
                                            <small class="text-warning font-weight-bold" style="font-size: 70%;">Saldo final neto</small>
                                        </div>
                                        <i class="fas fa-wallet fa-2x text-gray-200"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Detalle de Solicitudes -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 bg-white">
                                <h6 class="m-0 font-weight-bold text-primary">Historial y Detalle de Solicitudes</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-custom m-0" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Fecha / Tipo</th>
                                                <th>Periodo / Detalle Días</th>
                                                <th>Clasificación Periodo</th>
                                                <th>Rango Asignado</th>
                                                <th class="text-center">Pago</th>
                                                <th class="text-center">Estatus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($detalleSolicitudes) > 0): ?>
                                                <?php foreach ($detalleSolicitudes as $sol): ?>
                                                    <?php 
                                                        $badgeClass = 'badge-secondary';
                                                        if ($sol['periodo'] == 'Periodo Actual') {
                                                            $badgeClass = 'badge-success';
                                                        } elseif ($sol['periodo'] == 'Periodo Anterior') {
                                                            $badgeClass = 'badge-primary';
                                                        } elseif ($sol['periodo'] == 'Histórico (Más de 2 periodos)') {
                                                            $badgeClass = 'badge-warning text-dark';
                                                        }
                                                        
                                                        $esDuplicada = ($sol['veces_repetida'] > 1);
                                                    ?>
                                                    <tr class="<?= $esDuplicada ? 'table-warning' : '' ?>">
                                                        <!-- Columna Fecha / Tipo -->
                                                        <td>
                                                            <div class="font-weight-bold text-dark"><?= $sol['fecha_solicitud'] ?></div>
                                                            <span class="badge-tipo">Vacaciones</span>
                                                        </td>

                                                        <!-- Columna Periodo / Detalle Días -->
                                                        <td>
                                                            <div class="font-weight-bold text-dark"><?= $sol['feinicio'] ?> - <?= $sol['fefin'] ?></div>
                                                            <small class="text-muted font-weight-bold">
                                                                <span class="text-primary"><?= $sol['dias'] ?> Solicitado(s)</span>
                                                            </small>
                                                            <?php if ($esDuplicada): ?>
                                                                <br><span class="badge badge-danger" style="font-size: 70%;"><i class="fas fa-exclamation-circle"></i> Posible Duplicado (<?= $sol['veces_repetida'] ?> veces)</span>
                                                            <?php endif; ?>
                                                        </td>

                                                        <!-- Columna Clasificación Periodo -->
                                                        <td>
                                                            <span class="badge <?= $badgeClass ?>" style="font-size: 85%; padding: 0.4em 0.7em;">
                                                                <?= htmlspecialchars($sol['periodo']) ?>
                                                            </span>
                                                        </td>

                                                        <!-- Columna Rango del Periodo -->
                                                        <td>
                                                            <small class="text-muted font-weight-bold"><?= htmlspecialchars($sol['rango_periodo']) ?></small>
                                                        </td>

                                                        <!-- Columna Pago -->
                                                        <td class="text-center">
                                                            <?php if (strtolower($sol['pago']) == 'si'): ?>
                                                                <span class="badge badge-light border text-success px-2 py-1 font-weight-bold">Pagado</span>
                                                            <?php else: ?>
                                                                <span class="badge badge-light border text-danger px-2 py-1 font-weight-bold">No Pagado</span>
                                                            <?php endif; ?>
                                                        </td>

                                                        <!-- Columna Estatus -->
                                                        <td class="text-center">
                                                            <span class="badge badge-success px-3 py-2" style="font-size: 80%;">Autorizada</span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">No hay solicitudes registradas para este empleado.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center py-4">
                            <h4><i class="fas fa-info-circle"></i> Ningún empleado seleccionado</h4>
                            <p class="mb-0">Utiliza el buscador superior para seleccionar un empleado y visualizar su tablero de vacaciones.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2026</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Scripts de Bootstrap y Select2 -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Select2 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('#noEmpleado').select2({
                placeholder: "-- Buscar por nombre o número de empleado --",
                allowClear: true,
                width: '100%'
            });

            // Usar el evento select propio de Select2 para forzar el envío del formulario al hacer clic
            $('#noEmpleado').on('select2:select', function (e) {
                $('#formEmpleado').submit();
            });
        });
    </script>

</body>

</html>