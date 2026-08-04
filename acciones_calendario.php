<?php
// Conexi贸n a la base de datos
include 'conn.php';
mysqli_set_charset($conn, "utf8");
$noEmpleado_cookie = isset($_COOKIE['noEmpleado']) ? $_COOKIE['noEmpleado'] : null;
// Con isset: las vistas que llegan por POST (o sin 'ing') emitian un warning que
// se colaba ANTES del JSON y hacia que jQuery lo descartara como respuesta invalida.
$opcion = isset($_GET["opcion"]) ? $_GET["opcion"] : '';
$ing = isset($_GET["ing"]) ? $_GET["ing"] : '';
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

// Consulta de las solicitudes de vacaciones aprobadas
if ($opcion == "rrhh") {
    
    $sql = "SELECT s.empleado, s.fesolicitud, s.feinicio, DATE_ADD(s.fefin, INTERVAL 1 DAY) as fefin, u.nombre
            FROM solicitudes s
            INNER JOIN usuarios u ON s.empleado = u.noEmpleado
            WHERE s.estatus = 2 AND s.autorizaRH = 2 AND u.estatus = 1"; // Filtrar solo las aprobadas
    
    // Validamos que no sea vacío, ni cero, ni el string "null"
    if (!empty($ing) && $ing !== 'null' && $ing !== 'undefined') {
        $sql .= " AND s.empleado = " . intval($ing);
    }

    //echo $sql;
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

// Vacaciones de los compañeros del mismo departamento (excluyendo al usuario logueado).
// Pensado para usuarios NO jefes que quieren ver el calendario de su departamento.
if ($opcion == "departamento") {
    $noEmp = intval($noEmpleado_cookie);
    $events = array();

    if ($noEmp > 0) {
        $sqlDept = "SELECT s.empleado,
                           s.fesolicitud,
                           s.feinicio,
                           DATE_ADD(s.fefin, INTERVAL 1 DAY) AS fefin,
                           u.nombre
                    FROM solicitudes s
                    INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                    WHERE s.estatus = 2
                      AND (s.autorizaRH = 2 OR s.autorizaRh = 1)
                      AND u.estatus = 1
                      AND u.noEmpleado <> $noEmp
                      AND u.departamento = (SELECT departamento FROM usuarios WHERE noEmpleado = $noEmp)";

        $resultDept = $conn->query($sqlDept);
        if ($resultDept) {
            while ($row = $resultDept->fetch_assoc()) {
                $events[] = array(
                    'title'  => $row['nombre'],
                    'start'  => $row['feinicio'],
                    'end'    => $row['fefin'],
                    'nombre' => $row['nombre']
                );
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode($events);
}

if ($accion == 'ActividadesCalendarioPlaneadasfiltro') {

    // Si 'area' es un multiselect, se recibirá como un array
    // Se usa un array vacío por defecto si no se selecciona nada
    $areas = isset($_POST['area']) && is_array($_POST['area']) ? $_POST['area'] : [];
    $ingeniero = isset($_POST['ing']) ? $_POST['ing'] : '';

    // Consultar las actividades planeadas del usuario actual
    $fechaHoy = date('Y-m-d');
    $fechaInicio = date('Y-m-d', strtotime($fechaHoy . ' -50 days'));

    $sql = "SELECT ot.*, DATE(ot.start_date) as FechaPlaneadaInicioDate
            FROM servicios_planeados ot
            WHERE DATE(ot.start_date) >= ? AND ot.tipo_ot = 'SiteServiceOrder'";

    $whereClauses = [];
    $params = [$fechaInicio]; // Array para los parámetros de la consulta preparada
    $param_types = "s";       // String para los tipos de los parámetros (s = string)

    // Manejo de múltiples áreas seleccionadas
    if (!empty($areas)) {
        // Construye un array de placeholders para la cláusula IN (?, ?, ?)
        $placeholders = implode(',', array_fill(0, count($areas), '?'));
        $whereClauses[] = "REPLACE(SUBSTRING_INDEX(ot.order_code, '-', 1), '25', '') IN ($placeholders)";

        // Añade cada área al array de parámetros
        foreach ($areas as $area_item) {
            $params[] = $area_item;
            $param_types .= "s"; // Todas las áreas son strings
        }
    }

    // Manejo del ingeniero
    if (!empty($ingeniero)) {
        $whereClauses[] = "ot.engineer LIKE ?";
        $params[] = "%" . $ingeniero . "%"; // Añade comodines para LIKE
        $param_types .= "s"; // El ingeniero es un string
    }

    if (!empty($whereClauses)) {
        $sql .= " AND " . implode(' AND ', $whereClauses);
    }

    // Preparar la consulta
    if ($stmt = $conn->prepare($sql)) {
        // Enlazar los parámetros dinámicamente
        // La sintaxis '...' es para desempaquetar el array $params en argumentos individuales
        $stmt->bind_param($param_types, ...$params);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $actividades = [];
            while ($row = $result->fetch_assoc()) {
                $actividades[] = $row;
            }
            echo json_encode(['status' => 'success', 'actividades' => $actividades]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron actividades planeadas o error en la consulta.']);
        }

        $stmt->close(); // Cerrar el statement
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta: ' . $conn->error]);
    }
}

if ($opcion == 'llenaIngenieros') {        
    header('Content-Type: application/json; charset=utf-8');

    $sql = "SELECT noEmpleado, nombre FROM usuarios WHERE estatus = 1 ORDER BY nombre ASC";
    $result = $conn->query($sql);

    $ingenieros = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Aseguramos que los datos vayan en UTF-8 para evitar errores con acentos o la "ñ"
            $ingenieros[] =  $row; 
            // Nota: Si tu base de datos ya está en UTF-8, basta con: $ingenieros[] = $row;
        }
    }

    echo json_encode($ingenieros);
    exit; // Importante para detener la ejecución aquí
}

// Cerrar la conexión
$conn->close();
?>
