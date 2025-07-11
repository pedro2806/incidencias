<?php
// Conexi贸n a la base de datos
include 'conn.php';
mysqli_set_charset($conn, "utf8");
$noEmpleado_cookie = isset($_COOKIE['noEmpleado']) ? $_COOKIE['noEmpleado'] : null;
$opcion = $_GET["opcion"];
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

// Consulta de las solicitudes de vacaciones aprobadas
if ($opcion == "rrhh") {
    
    $sql = "SELECT s.empleado, s.fesolicitud, s.feinicio, s.fefin, u.nombre
            FROM solicitudes s
            INNER JOIN usuarios u ON s.empleado = u.noEmpleado
            WHERE s.estatus = 2 AND s.autorizaRH = 2 AND u.estatus = 1"; // Filtrar solo las aprobadas
    
    $result = $conn->query($sql);
    
    $events = array();
    
    while ($row = $result->fetch_assoc()) {
        $events[] = array(
            'title' => $row['nombre'], // Mostrar el nombre del empleado
            'start' => $row['feinicio'],
            'end' => $row['fefin'],
            'nombre' => $row['nombre']
        );
    }
    
    // Devolver los eventos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($events);
}

if ($opcion == "jefes") {
    if($noEmpleado_cookie == 177 || $noEmpleado_cookie == 489){
        $noEmpleado_cookie = 45;
    }
    $sqlJefes = "SELECT s.empleado, s.fesolicitud, s.feinicio, DATE_ADD(s.fefin, INTERVAL 1 DAY) as fefin, u.nombre, u.jefe
                FROM solicitudes s
                INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                WHERE s.estatus = 2 AND s.autorizaRH = 2 AND u.jefe = $noEmpleado_cookie AND u.estatus = 1";
    
    $resultJefes = $conn->query($sqlJefes);
    
    $events = array();
    
    while ($row = $resultJefes->fetch_assoc()) {
        $events[] = array(
            'title' => $row['nombre'], // Mostrar el nombre del empleado
            'start' => $row['feinicio'],
            'end' => $row['fefin'],
            'nombre' => $row['nombre']
        );
    }
    
    // Devolver los eventos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($events);
}

if($accion == 'ActividadesCalendarioPlaneadasfiltro'){

    $area = isset($_POST['area']) ? $_POST['area'] : '';
    $ingeniero = isset($_POST['ing']) ? $_POST['ing'] : '';

    // Consultar las actividades planeadas del usuario actual
    $fechaHoy = date('Y-m-d');
    $fechaInicio = date('Y-m-d', strtotime($fechaHoy . ' -50 days'));

    /*$sql = "SELECT ot.*, DATE(ot.FechaPlaneadaInicio) as FechaPlaneadaInicioDate, c.nombre as cliente
            FROM ordenes_servicio ot
            LEFT JOIN clientes c ON ot.customer_id = c.id_cliente
            WHERE DATE(ot.FechaPlaneadaInicio) >= '$fechaInicio' AND ot.qualityAreas NOT IN ('qualityAreas') AND ot.status IN ('Asignada', 'Trabajando') AND ot.engineers != ''";*/
    $sql = "SELECT ot.*, DATE(ot.start_date) as FechaPlaneadaInicioDate
            FROM servicios_planeados ot            
            WHERE DATE(ot.start_date) >= '$fechaInicio' AND ot.tipo_ot = 'SiteServiceOrder'";
    

    $whereClauses = [];

    if (!empty($area)) {
        $area = $conn->real_escape_string($area);
        $whereClauses[] = "REPLACE(SUBSTRING_INDEX(ot.order_code, '-', 1), '25', '') =  '$area'";
    }

    if (!empty($ingeniero)) {
        $ingeniero = $conn->real_escape_string($ingeniero);
        $whereClauses[] = "ot.engineer LIKE '%" . $ingeniero . "%'";
    }

    if (!empty($whereClauses)) {
        $sql .= " AND " . implode(' AND ', $whereClauses);
    }
    //echo $sql;
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $actividades = [];
        while ($row = $result->fetch_assoc()) {
            $actividades[] = $row;
        }
        echo json_encode(['status' => 'success', 'actividades' => $actividades]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se encontraron actividades planeadas o error en la consulta.']);
    }
}
$conn->close();
?>
