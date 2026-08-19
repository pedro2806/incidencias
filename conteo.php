<?php 
include 'conn.php';

// Validar sesión de empleado
if(empty($_COOKIE['noEmpleado'])){
    echo '<script>window.location.assign("index")</script>';
    exit;
}

$noEmpleado = (int) $_COOKIE['noEmpleado'];

// Consulta SQL unificada que reemplaza toda la lógica anterior
$sql = "SELECT 
    u.antiguedad, 
    d.departamento AS departamento, 
    p.puesto AS puesto, 
    j.nombre AS jefe, 
    u.fechaIngreso, 
    u.inicio_actual, 
    u.fin_actual,
    COALESCE(dv_actual.dias, 0) AS dias_ley_actual, 
    COALESCE(dv_anterior.dias, 0) AS dias_ley_anterior,        
    
    COALESCE(( SELECT SUM(s.dias) FROM solicitudes s WHERE s.empleado = u.noEmpleado AND s.fesolicitud BETWEEN u.inicio_actual AND u.fin_actual AND s.estatus = 2 AND s.autorizaRH = 2 AND s.tipo = 1 ), 0) AS diasSol, 
    COALESCE(( SELECT SUM(s.dias) FROM solicitudes s WHERE s.empleado = u.noEmpleado AND s.fesolicitud BETWEEN u.inicio_anterior AND u.fin_anterior AND s.estatus = 2 AND s.autorizaRH = 2 AND s.tipo = 1 ), 0) AS diasSolAnterior, 
    
    ( 
        (COALESCE(dv_actual.dias, 0) - GREATEST(0, COALESCE(( SELECT SUM(s.dias) FROM solicitudes s WHERE s.empleado = u.noEmpleado AND s.fesolicitud BETWEEN u.inicio_anterior AND u.fin_anterior AND s.estatus = 2 AND s.autorizaRH = 2 AND s.tipo = 1 ), 0) - COALESCE(dv_anterior.dias, 0))) 
        - 
        COALESCE(( SELECT SUM(s.dias) FROM solicitudes s WHERE s.empleado = u.noEmpleado AND s.fesolicitud BETWEEN u.inicio_actual AND u.fin_actual AND s.estatus = 2 AND s.autorizaRH = 2 AND s.tipo = 1 ), 0)
    ) AS diasdisponibles, 
    
    u.foto
FROM ( 
    SELECT 
        foto, noEmpleado, fechaIngreso, jefe, departamento, puesto, 
        TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) AS antiguedad, 
        DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) YEAR) AS inicio_actual,
        DATE_SUB(DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) + 1 YEAR), INTERVAL 1 DAY) AS fin_actual,
        DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) - 1 YEAR) AS inicio_anterior,
        DATE_SUB(DATE_ADD(fechaIngreso, INTERVAL TIMESTAMPDIFF(YEAR, fechaIngreso, CURDATE()) YEAR), INTERVAL 1 DAY) AS fin_anterior
    FROM usuarios WHERE noEmpleado = $noEmpleado 
) u 
LEFT JOIN usuarios j ON u.jefe = j.noEmpleado 
LEFT JOIN departamento d ON u.departamento = d.id 
LEFT JOIN puesto p ON u.puesto = p.id 
LEFT JOIN diasvacaciones dv_actual ON dv_actual.anio = u.antiguedad 
LEFT JOIN diasvacaciones dv_anterior ON dv_anterior.anio = GREATEST(0, u.antiguedad - 1) 
GROUP BY u.noEmpleado;";

$resultado = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$datos = mysqli_fetch_assoc($resultado);

// Variables aseguradas
$nombreUsuario = isset($_COOKIE['nombredelusuario']) ? $_COOKIE['nombredelusuario'] : 'Colaborador';
$antiguedad = $datos['antiguedad'] ?? 0;
$diasLey = $datos['dias_ley_actual'] ?? 0;
$diasSol = $datos['diasSol'] ?? 0;
$diasDisponibles = $datos['diasdisponibles'] ?? 0;
$fechaRenovacion = $datos['fin_actual'] ?? '';
$diasLeyAnterior = $datos['dias_ley_anterior'] ?? 0;
$diasSolAnterior = $datos['diasSolAnterior'] ?? 0;
$diasDeuda = max(0, $diasSolAnterior - $diasLeyAnterior);
?>

<!-- Content Row / Rediseño de Tarjetas de Vacaciones -->
<div class="row">

    <!-- Tarjeta 1: Perfil / Usuario -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-0">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">                        
                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                            <?php echo htmlspecialchars($nombreUsuario); ?>
                        </div>
                        <div class="text-muted small mt-1">No. Empleado: <?php echo $noEmpleado; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tarjeta 2: Antigüedad y Días de Ley -->
    <div class="col-xl-2 col-md-6 mb-2">
        <div class="card border-left-info shadow h-100 py-0">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">                        
                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                            Antig.: <?php echo $antiguedad; ?> años
                        </div>
                        <div class="h6 mb-0 font-weight-bold text-gray-800 mt-1">
                            Vac. X ley: <?php echo $diasLey; ?> días
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta 3: Días Solicitados y Disponibles -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-0">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">                        
                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                            Días solicitados: <?php echo $diasSol; ?>
                        </div>
                        <div class="h6 mb-0 font-weight-bold text-success mt-1">
                            Días disponibles: <?php echo $diasDisponibles; ?> días
                        </div>
                        <!-- Input oculto requerido para formularios -->
                        <input type="hidden" class="form-control" id="diasDisponibles" name="diasDisponibles" value="<?php echo $diasDisponibles; ?>" readonly>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tarjeta 4: Fecha de Renovación -->
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-0">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">                                                
                        <div class="h6 mb-0 font-weight-bold text-gray-800 mt-1">
                            Renovación de Vac.: <?php echo $fechaRenovacion; ?>                        
                        </div>
                        <div class="h6 mb-0 font-weight-bold text-gray-800 mt-1">
                            Días adelantados  del periodo anterior: <?php echo $diasDeuda; ?>                        
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>