<?php
// Conexión a la base de datos
include 'conn.php';
mysqli_set_charset($conn, "utf8");
$noEmpleado_cookie = isset($_COOKIE['noEmpleado']) ? $_COOKIE['noEmpleado'] : null;
$opcion = $_GET["opcion"];

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

    $sqlJefes = "SELECT s.empleado, s.fesolicitud, s.feinicio, s.fefin, u.nombre, u.jefe
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
$conn->close();
?>
