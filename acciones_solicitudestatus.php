<?php
/**
 * Backend de solicitudes / calendario.
 *
 * Acciones (POST o GET 'accion'):
 *   - listar          : solicitudes del usuario en sesion agrupadas en
 *                        porAutorizar / autorizadas / canceladas (POST).
 *   - calendarioJefes : eventos FullCalendar (JSON) con las vacaciones
 *                        aprobadas de los empleados a cargo del jefe en sesion
 *                        (GET; lo consume calendarioVacacionesJefes_v2.php).
 *
 * Seguridad: prepared statements. El numero de empleado se toma de la cookie
 * en el servidor, no de lo que envia el cliente.
 */

header('Content-Type: application/json; charset=utf-8');
include 'conn.php';
mysqli_set_charset($conn, "utf8");

$noEmpleado = isset($_COOKIE['noEmpleado']) ? (int) $_COOKIE['noEmpleado'] : 0;

if ($noEmpleado <= 0) {
    echo json_encode(['success' => false, 'message' => 'Sesion no valida.']);
    exit;
}

// FullCalendar consume por GET; el resto por POST.
$accion = isset($_POST['accion']) ? $_POST['accion']
        : (isset($_GET['accion']) ? $_GET['accion'] : '');

/* ------------------------------------------------------------------ *
 * Calendario de vacaciones de los empleados a cargo del jefe          *
 * (eventos FullCalendar)                                              *
 * ------------------------------------------------------------------ */
if ($accion === 'calendarioJefes') {
    $jefe = $noEmpleado;
    // Caso especial: 177 y 489 ven el calendario del jefe 45.
    if ($jefe == 177 || $jefe == 489) {
        $jefe = 45;
    }

    $events = [];
    $sql = "SELECT s.feinicio,
                   DATE_ADD(s.fefin, INTERVAL 1 DAY) AS fefin,
                   u.nombre
            FROM solicitudes s
            INNER JOIN usuarios u ON s.empleado = u.noEmpleado
            WHERE s.estatus = 2 AND s.autorizaRH = 2
              AND u.jefe = ? AND u.estatus = 1";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $jefe);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $events[] = [
                'title'  => $row['nombre'],
                'start'  => $row['feinicio'],
                'end'    => $row['fefin'],
                'nombre' => $row['nombre'],
            ];
        }
        $stmt->close();
    }

    echo json_encode($events);
    exit;
}

if ($accion !== 'listar') {
    echo json_encode(['success' => false, 'message' => 'Accion no reconocida.']);
    exit;
}

/**
 * Ejecuta una consulta de solicitudes filtrada por empleado y devuelve las filas.
 */
function consultaSolicitudes($conn, $sql, $noEmpleado)
{
    $filas = [];
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $noEmpleado);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $filas[] = [
                'id'            => $row['id'],
                'tipo'          => $row['tipo'],
                'feinicio'      => $row['feinicio'],
                'fefin'         => $row['fefin'],
                'fesolicitud'   => $row['fesolicitud'],
                'dias'          => $row['dias'],
                'notasempleado' => $row['notasempleado'],
                'notajefe'      => $row['notajefe'],
                'estatus'       => $row['estatus'],
                'autorizaRH'    => $row['autorizaRH'],
                'origen'        => isset($row['origen']) ? $row['origen'] : '',
            ];
        }
        $stmt->close();
    }
    return $filas;
}

$porAutorizar = consultaSolicitudes(
    $conn,
    "SELECT * FROM solicitudes
        WHERE empleado = ? 
            AND (estatus = 1 OR autorizaRH = 1)
            AND estatus != 3 
            AND autorizaRH != 3
        ORDER BY fesolicitud ASC",
    $noEmpleado
);

$autorizadas = consultaSolicitudes(
    $conn,
    "SELECT * FROM solicitudes
     WHERE empleado = ? AND (estatus = 2 AND autorizaRH = 2)",
    $noEmpleado
);

$canceladas = consultaSolicitudes(
    $conn,
    "SELECT * FROM solicitudes
     WHERE empleado = ? AND (estatus = 3 OR autorizaRH = 3)",
    $noEmpleado
);

echo json_encode([
    'success'      => true,
    'porAutorizar' => $porAutorizar,
    'autorizadas'  => $autorizadas,
    'canceladas'   => $canceladas,
]);
