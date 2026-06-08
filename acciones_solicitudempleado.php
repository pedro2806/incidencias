<?php
/**
 * Backend dedicado de la pagina "Solicitudes para revisar" (solicitudempleado_v2.php).
 * Vista del JEFE: solicitudes de los empleados cuyo `jefe` es el usuario en sesion.
 *
 * Accion (POST 'accion'):
 *   - listar : porAutorizar / autorizadas / canceladas
 *
 * Seguridad: prepared statements. El numero de empleado (jefe) se toma de la
 * cookie en el servidor.
 */

header('Content-Type: application/json; charset=utf-8');
include 'conn.php';
mysqli_set_charset($conn, "utf8");

$noEmpleado = isset($_COOKIE['noEmpleado']) ? (int) $_COOKIE['noEmpleado'] : 0;

if ($noEmpleado <= 0) {
    echo json_encode(['success' => false, 'message' => 'Sesion no valida.']);
    exit;
}

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

if ($accion !== 'listar') {
    echo json_encode(['success' => false, 'message' => 'Accion no reconocida.']);
    exit;
}

/**
 * Días ya gozados (solicitados y aprobados) del empleado dentro de su periodo
 * de aniversario actual. Réplica de la lógica original.
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
        $sql = "SELECT IFNULL(SUM(dias), 0) AS diasSol FROM solicitudes
                WHERE empleado = ? AND (estatus = 2 AND autorizaRH = 2)
                  AND fesolicitud BETWEEN ? AND ? AND tipo = 1";
    } else {
        $fechaPrev = ($anio - 1) . $FechaIng;
        $fechaNext = $anio . $FechaIng;
        $sql = "SELECT IFNULL(SUM(dias), 0) AS diasSol FROM solicitudes
                WHERE empleado = ? AND (estatus = 2 AND autorizaRH = 2)
                  AND fesolicitud BETWEEN ? AND ?";
    }

    $diasSol = 0;
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

function fmtFecha($fecha)
{
    $ts = strtotime($fecha);
    return $ts ? date("d/m/Y", $ts) : '';
}

/* --------------------------- POR AUTORIZAR --------------------------- */
$porAutorizar = [];
$sqlPA = "SELECT s.id, s.empleado, s.tipo, s.feinicio, s.fefin, s.fesolicitud,
                 s.dias, s.notasempleado, s.estatus, s.autorizaRH,
                 u.nombre AS empleadoNom, u.fechaIngreso,
                 (SELECT dias FROM diasvacaciones
                    WHERE anio = TIMESTAMPDIFF(YEAR, u.fechaIngreso, CURDATE())) AS diasD
          FROM solicitudes s
          INNER JOIN usuarios u ON s.empleado = u.noEmpleado
          WHERE s.empleado IN (SELECT noEmpleado FROM usuarios WHERE jefe = ?)
            AND ((s.estatus = 1 AND s.autorizaRH = 1)
              OR (s.estatus = 1 AND s.autorizaRH = 2)
              OR (s.estatus = 2 AND s.autorizaRH = 1))
          ORDER BY s.fesolicitud DESC";

if ($stmt = $conn->prepare($sqlPA)) {
    $stmt->bind_param("i", $noEmpleado);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $diasSol  = calculaDiasSol($conn, (int) $row['empleado'], $row['fechaIngreso']);
        $diasDisp = (int) $row['diasD'] - $diasSol;

        $porAutorizar[] = [
            'id'            => $row['id'],
            'empleado'      => $row['empleado'],
            'empleadoNom'   => $row['empleadoNom'],
            'tipo'          => $row['tipo'],
            'fesolicitud'   => fmtFecha($row['fesolicitud']),
            'feinicio'      => fmtFecha($row['feinicio']),
            'fefin'         => fmtFecha($row['fefin']),
            'dias'          => $row['dias'],
            'notasempleado' => $row['notasempleado'],
            'estatus'       => $row['estatus'],
            'autorizaRH'    => $row['autorizaRH'],
            'diasDisp'      => $diasDisp,
        ];
    }
    $stmt->close();
}

/* ---------------------------- AUTORIZADAS ---------------------------- */
$autorizadas = [];
$sqlAut = "SELECT s.id, s.tipo, s.feinicio, s.fefin, s.fesolicitud, s.dias,
                  s.notajefe, s.estatus, s.autorizaRH, u.nombre AS empleado
           FROM solicitudes s
           INNER JOIN usuarios u ON s.empleado = u.noEmpleado
           WHERE s.empleado IN (SELECT noEmpleado FROM usuarios WHERE jefe = ?)
             AND (s.estatus = 2 AND s.autorizaRH = 2)";

if ($stmt = $conn->prepare($sqlAut)) {
    $stmt->bind_param("i", $noEmpleado);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $autorizadas[] = [
            'id'          => $row['id'],
            'empleado'    => $row['empleado'],
            'tipo'        => $row['tipo'],
            'fesolicitud' => fmtFecha($row['fesolicitud']),
            'feinicio'    => fmtFecha($row['feinicio']),
            'fefin'       => fmtFecha($row['fefin']),
            'dias'        => $row['dias'],
            'notajefe'    => $row['notajefe'],
            'estatus'     => $row['estatus'],
            'autorizaRH'  => $row['autorizaRH'],
        ];
    }
    $stmt->close();
}

/* ------------------------ CANCELADAS / RECHAZADAS -------------------- */
$canceladas = [];
$sqlCan = "SELECT s.id, s.tipo, s.feinicio, s.fefin, s.fesolicitud, s.dias,
                  s.notasempleado, s.notajefe, s.estatus, u.nombre AS empleado
           FROM solicitudes s
           INNER JOIN usuarios u ON s.empleado = u.noEmpleado
           WHERE s.empleado IN (SELECT noEmpleado FROM usuarios WHERE jefe = ?)
             AND ((s.estatus = 3) OR (s.autorizaRH = 3))";

if ($stmt = $conn->prepare($sqlCan)) {
    $stmt->bind_param("i", $noEmpleado);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $canceladas[] = [
            'id'            => $row['id'],
            'empleado'      => $row['empleado'],
            'tipo'          => $row['tipo'],
            'fesolicitud'   => fmtFecha($row['fesolicitud']),
            'feinicio'      => fmtFecha($row['feinicio']),
            'fefin'         => fmtFecha($row['fefin']),
            'dias'          => $row['dias'],
            'notasempleado' => $row['notasempleado'],
            'notajefe'      => $row['notajefe'],
            'estatus'       => $row['estatus'],
        ];
    }
    $stmt->close();
}

echo json_encode([
    'success'      => true,
    'porAutorizar' => $porAutorizar,
    'autorizadas'  => $autorizadas,
    'canceladas'   => $canceladas,
]);
