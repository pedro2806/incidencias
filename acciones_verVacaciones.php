<?php
/**
 * Backend dedicado de "Ver solicitudes de vacaciones" (verVacaciones_v2.php) - vista RH.
 *
 * Acciones (POST o GET 'accion'):
 *   - llenaSelectPersonal  : empleados activos para el filtro Select2
 *   - llenaTablaVacaciones : solicitudes (últimos 2 años) del empleado filtrado
 *                            + cálculo de días disponibles (calculaDiasSol)
 *   - llenaTablaServicios  : OTs/servicios donde el empleado es ingeniero
 *
 * Seguridad: prepared statements en todas las consultas (sustituye la
 * interpolación directa de $_POST[empleado] de funciones_select.php) y el
 * acceso se valida server-side contra accesos_especiales (bloque Administración,
 * opcion 'verAdministracion').
 */

header('Content-Type: application/json; charset=utf-8');
include 'conn.php';
mysqli_set_charset($conn, "utf8");

// Autorización por el bloque Administración (cookie + accesos_especiales).
$noEmpleado = isset($_COOKIE['noEmpleado']) ? (int) $_COOKIE['noEmpleado'] : 0;
$stmtAcc = $conn->prepare("SELECT id FROM accesos_especiales WHERE noEmpleado = ? AND sistema = 'incidencias' AND opcion = 'verAdministracion' AND estatus = 1 LIMIT 1");
$stmtAcc->bind_param("i", $noEmpleado);
$stmtAcc->execute();
if ($stmtAcc->get_result()->num_rows === 0) {
    $stmtAcc->close();
    http_response_code(403);
    echo json_encode(array('error' => 'No autorizado'));
    exit;
}
$stmtAcc->close();

$accion = isset($_POST['opcion']) ? $_POST['opcion']
        : (isset($_GET['opcion']) ? $_GET['opcion'] : '');

$empleado = isset($_POST['empleado']) ? (int) $_POST['empleado']
          : (isset($_GET['empleado']) ? (int) $_GET['empleado'] : 0);

/**
 * Días ya gozados (aprobados, tipo vacaciones) del empleado dentro de su
 * periodo de aniversario actual. Misma lógica que el original.
 */
function calculaDiasSol($conn, $noEmp, $fechaIngreso)
{
    $FechaIng     = substr($fechaIngreso, 4, 6);   // "-MM-DD"
    $anio         = date("Y");
    $fechaCompara = $anio . $FechaIng;
    $hoy          = date("Y-m-d");

    if ($fechaCompara <= $hoy) {
        $fechaPrev = $anio . $FechaIng;
        $fechaNext = ($anio + 1) . $FechaIng;
    } else {
        $fechaPrev = ($anio - 1) . $FechaIng;
        $fechaNext = $anio . $FechaIng;
    }

    $diasSol = 0;
    $sql = "SELECT IFNULL(SUM(dias), 0) FROM solicitudes
            WHERE empleado = ? AND (estatus = 2 AND autorizaRH = 2)
              AND fesolicitud BETWEEN ? AND ? AND tipo = 1";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iss", $noEmp, $fechaPrev, $fechaNext);
        $stmt->execute();
        $stmt->bind_result($d);
        if ($stmt->fetch()) {
            $diasSol = (int) $d;
        }
        $stmt->close();
    }
    return $diasSol;
}

/* ----------------------- SELECT DE PERSONAL ----------------------- */
if ($accion === "llenaSelectPersonal") {
    $personal = array();
    $sql = "SELECT noEmpleado, nombre FROM usuarios WHERE estatus = 1 ORDER BY nombre ASC";
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $personal[] = array(
                'noEmpleado' => $row["noEmpleado"],
                'nombre'     => $row["nombre"]
            );
        }
    }
    echo json_encode($personal);
    exit;
}

/* --------------------- TABLA DE VACACIONES ------------------------ */
if ($accion === "llenaTablaVacaciones") {
    $registros = array();

    $sql = "SELECT s.id, s.tipo, DATE_FORMAT(s.feinicio, '%d/%m/%Y') AS feinicio,
                   DATE_FORMAT(s.fefin, '%d/%m/%Y') AS fefin, s.notasempleado, s.notajefe,
                   s.comentarios, s.estatus, s.dias, s.autorizaRH, s.Dgozados,
                   s.empleado AS noEmp, u.nombre AS empleado, s.fesolicitud,
                   DATE_FORMAT(s.fesolicitud, '%Y-%m-%d') AS FechaBien,
                   (SELECT dias FROM diasvacaciones
                     WHERE anio = TIMESTAMPDIFF(YEAR, u.fechaIngreso, CURDATE())) AS diasD,
                   u.fechaIngreso
            FROM solicitudes s
            INNER JOIN usuarios u ON s.empleado = u.noEmpleado
            WHERE s.fesolicitud BETWEEN DATE_SUB(CURDATE(), INTERVAL 2 YEAR) AND CURDATE()
              AND u.noEmpleado = ?
            ORDER BY s.fesolicitud DESC";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $empleado);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $diasSol = calculaDiasSol($conn, $row["noEmp"], $row["fechaIngreso"]);
            $registros[] = array(
                'id'           => $row["id"],
                'tSolicitud'   => $row["tipo"],
                'Finicio'      => $row["feinicio"],
                'FFin'         => $row["fefin"],
                'fSolicitud'   => $row["fesolicitud"],
                'ComentariosE' => $row["notasempleado"],
                'ComentariosJ' => $row["notajefe"],
                'Comentarios'  => $row["comentarios"],
                'Estatus'      => $row["estatus"],
                'usuario'      => $row["empleado"],
                'noEmpleado'   => $row["noEmp"],
                'noDias'       => $row["dias"],
                'autorizaRH'   => $row["autorizaRH"],
                'FechaBien'    => $row["FechaBien"],
                'Dgozados'     => $row["Dgozados"],
                'diasDisp'     => $row["diasD"] - $diasSol
            );
        }
        $stmt->close();
    }
    echo json_encode($registros);
    exit;
}

/* --------------------- TABLA DE SERVICIOS ------------------------- */
if ($accion === "llenaTablaServicios") {
    $servicios = array();

    $sql = "SELECT ot.*, DATE(ot.start_date) AS FechaPlaneadaInicioDate, u.nombre,
                   IFNULL(u2.nombre,'') AS nombre2, IFNULL(u3.nombre,'') AS nombre3,
                   comment_logistic, estatus_logistic,
                   (SELECT departamento FROM usuarios WHERE noEmpleado = ot.capturado_por) AS depto,
                   reprogramado, motivo_reprogramacion, motivo_cancelacion
            FROM servicios_planeados_mess ot
            INNER JOIN usuarios u  ON ot.engineer  = u.id_usuario
            LEFT  JOIN usuarios u2 ON ot.engineer2 = u2.id_usuario
            LEFT  JOIN usuarios u3 ON ot.engineer3 = u3.id_usuario
            WHERE (SELECT id_usuario FROM usuarios WHERE noEmpleado = ?) IN (ot.engineer, ot.engineer2, ot.engineer3)
            ORDER BY ot.start_date DESC";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $empleado);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $servicios[] = array(
                'id'                      => $row["id"],
                'service_order'           => $row["service_order"],
                'cliente'                 => $row["ds_cliente"],
                'ot'                      => $row["order_code"],
                'start_date'              => $row["start_date"],
                'area'                    => $row["area"],
                'FechaPlaneadaInicioDate' => $row["FechaPlaneadaInicioDate"],
                'engineer'                => $row["nombre"],
                'engineer2'               => $row["nombre2"],
                'engineer3'               => $row["nombre3"],
                'estatus'                 => $row["estatus"],
                'depto'                   => $row["depto"],
                'reprogramado'            => $row["reprogramado"],
                'motivo_reprogramacion'   => $row["motivo_reprogramacion"],
                'ciudad'                  => $row["city"]
            );
        }
        $stmt->close();
    }
    echo json_encode($servicios);
    exit;
}
