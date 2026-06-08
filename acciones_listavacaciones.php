<?php
/**
 * Backend dedicado de "Lista de Vacaciones" (listavacaciones_v2.php) - vista RH.
 *
 * Acciones (GET o POST 'accion'):
 *   - porAutorizar       : solicitudes pendientes (RH/jefe) de todos los empleados
 *   - autorizadas        : solicitudes autorizadas (estatus=2 y autorizaRH=2)
 *   - canceladas         : solicitudes canceladas/rechazadas
 *   - editarFeSolicitud  : actualiza fecha de solicitud y días gozados (POST)
 *
 * Seguridad: prepared statements en todas las consultas. Sustituye a las
 * acciones llenaTabla* y feSolicitudEdit de funciones_select.php (que en el
 * caso de la edición concatenaba $_POST directo en el SQL).
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

$accion = isset($_POST['accion']) ? $_POST['accion']
        : (isset($_GET['accion']) ? $_GET['accion'] : '');

// Filtro por mes (formato 'YYYY-MM'). Por defecto la vista manda el mes actual,
// así las listas se acotan y la carga es rápida. Vacío/'todos' = sin filtro.
$mes = isset($_POST['mes']) ? $_POST['mes']
     : (isset($_GET['mes']) ? $_GET['mes'] : '');
$rango = rangoMes($mes);

/**
 * Devuelve [primerDia, primerDiaDelMesSiguiente) para un mes 'YYYY-MM', o null
 * si no es válido. Se usa con 'fesolicitud >= ? AND fesolicitud < ?'.
 */
function rangoMes($mes)
{
    if (!preg_match('/^\d{4}-\d{2}$/', (string) $mes)) {
        return null;
    }
    $ini = $mes . '-01';
    $fin = date('Y-m-d', strtotime($ini . ' +1 month'));
    return array($ini, $fin);
}

/**
 * Días ya gozados (aprobados, tipo vacaciones) del empleado dentro de su
 * periodo de aniversario actual. Réplica de la lógica original.
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

/* --------------------------- POR AUTORIZAR --------------------------- */
if ($accion === 'porAutorizar') {
    $registros = [];
    $sql = "SELECT s.id, s.tipo,
                   DATE_FORMAT(s.feinicio, '%d/%m/%Y')  AS feinicio,
                   DATE_FORMAT(s.fefin, '%d/%m/%Y')     AS fefin,
                   s.notasempleado, s.notajefe, s.comentarios, s.estatus,
                   s.dias, s.autorizaRH, s.Dgozados,
                   s.empleado AS noEmp, u.nombre AS empleado, s.fesolicitud,
                   DATE_FORMAT(s.fesolicitud, '%Y-%m-%d') AS FechaBien,
                   (SELECT dias FROM diasvacaciones
                      WHERE anio = TIMESTAMPDIFF(YEAR, u.fechaIngreso, CURDATE())) AS diasD,
                   u.fechaIngreso
            FROM solicitudes s
            INNER JOIN usuarios u ON s.empleado = u.noEmpleado
            WHERE ((s.estatus = 1 AND s.autorizaRH = 1)
                OR (s.estatus = 1 AND s.autorizaRH = 2)
                OR (s.estatus = 2 AND s.autorizaRH = 1))
            ORDER BY s.fesolicitud DESC";
    // 'Por autorizar' NO se filtra por mes: son pendientes que RH debe atender
    // sin importar el mes de solicitud (si no, se ocultarían pendientes viejas).
    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $diasSol = calculaDiasSol($conn, (int) $row['noEmp'], $row['fechaIngreso']);
            $registros[] = [
                'id'           => $row['id'],
                'tSolicitud'   => $row['tipo'],
                'Finicio'      => $row['feinicio'],
                'FFin'         => $row['fefin'],
                'fSolicitud'   => $row['fesolicitud'],
                'FechaBien'    => $row['FechaBien'],
                'ComentariosE' => $row['notasempleado'],
                'ComentariosJ' => $row['notajefe'],
                'Comentarios'  => $row['comentarios'],
                'Estatus'      => $row['estatus'],
                'usuario'      => $row['empleado'],
                'noEmpleado'   => $row['noEmp'],
                'noDias'       => $row['dias'],
                'autorizaRH'   => $row['autorizaRH'],
                'Dgozados'     => $row['Dgozados'],
                'diasDisp'     => (int) $row['diasD'] - $diasSol,
            ];
        }
    }
    echo json_encode($registros);
    exit;
}

/* ---------------------------- AUTORIZADAS ---------------------------- */
if ($accion === 'autorizadas') {
    $registros = [];
    $sql = "SELECT s.id, s.tipo,
                   DATE_FORMAT(s.feinicio, '%d/%m/%Y')  AS feinicio,
                   DATE_FORMAT(s.fefin, '%d/%m/%Y')     AS fefin,
                   s.notasempleado, s.notajefe, s.comentarios, s.estatus,
                   s.dias, s.autorizaRH, s.Dgozados,
                   s.empleado AS noEmp, u.nombre AS empleado, s.fesolicitud,
                   DATE_FORMAT(s.fesolicitud, '%Y-%m-%d') AS FechaBien,
                   (SELECT dias FROM diasvacaciones
                      WHERE anio = TIMESTAMPDIFF(YEAR, u.fechaIngreso, CURDATE())) AS diasD,
                   u.fechaIngreso
            FROM solicitudes s
            INNER JOIN usuarios u ON s.empleado = u.noEmpleado
            WHERE s.estatus = 2 AND s.autorizaRH = 2";
    $types = '';
    $params = array();
    if ($rango) {
        $sql .= " AND s.fesolicitud >= ? AND s.fesolicitud < ?";
        $types  = 'ss';
        $params = array($rango[0], $rango[1]);
    } else {
        $sql .= " AND s.fesolicitud BETWEEN DATE_SUB(CURDATE(), INTERVAL 2 YEAR) AND CURDATE()";
    }
    $sql .= " ORDER BY s.fesolicitud DESC";

    if ($stmt = $conn->prepare($sql)) {
        if ($types !== '') { $stmt->bind_param($types, ...$params); }
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $diasSol = calculaDiasSol($conn, (int) $row['noEmp'], $row['fechaIngreso']);
            $registros[] = [
                'id'           => $row['id'],
                'tSolicitud'   => $row['tipo'],
                'Finicio'      => $row['feinicio'],
                'FFin'         => $row['fefin'],
                'fSolicitud'   => $row['fesolicitud'],
                'FechaBien'    => $row['FechaBien'],
                'ComentariosE' => $row['notasempleado'],
                'ComentariosJ' => $row['notajefe'],
                'Comentarios'  => $row['comentarios'],
                'Estatus'      => $row['estatus'],
                'usuario'      => $row['empleado'],
                'noEmpleado'   => $row['noEmp'],
                'noDias'       => $row['dias'],
                'autorizaRH'   => $row['autorizaRH'],
                'Dgozados'     => $row['Dgozados'],
                'diasDisp'     => (int) $row['diasD'] - $diasSol,
            ];
        }
    }
    echo json_encode($registros);
    exit;
}

/* ------------------------ CANCELADAS / RECHAZADAS -------------------- */
if ($accion === 'canceladas') {
    $registros = [];
    $sql = "SELECT s.id, s.tipo, s.feinicio, s.fefin, s.fesolicitud, s.dias,
                   s.notasempleado, s.notajefe, s.comentarios, s.estatus,
                   s.autorizaRH, s.empleado AS noEmp, u.nombre AS empleado
            FROM solicitudes s
            INNER JOIN usuarios u ON s.empleado = u.noEmpleado
            WHERE (s.estatus = 3 OR s.autorizaRH = 3)";
    $types = '';
    $params = array();
    if ($rango) {
        $sql .= " AND s.fesolicitud >= ? AND s.fesolicitud < ?";
        $types  = 'ss';
        $params = array($rango[0], $rango[1]);
    }
    $sql .= " ORDER BY s.fesolicitud DESC";

    if ($stmt = $conn->prepare($sql)) {
        if ($types !== '') { $stmt->bind_param($types, ...$params); }
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $registros[] = [
                'id'           => $row['id'],
                'tSolicitud'   => $row['tipo'],
                'Finicio'      => $row['feinicio'],
                'FFin'         => $row['fefin'],
                'fSolicitud'   => $row['fesolicitud'],
                'ComentariosE' => $row['notasempleado'],
                'ComentariosJ' => $row['notajefe'],
                'Comentarios'  => $row['comentarios'],
                'Estatus'      => $row['estatus'],
                'usuario'      => $row['empleado'],
                'noEmpleado'   => $row['noEmp'],
                'noDias'       => $row['dias'],
                'autorizaRH'   => $row['autorizaRH'],
            ];
        }
    }
    echo json_encode($registros);
    exit;
}

/* ------------------- EDITAR FECHA DE SOLICITUD / DGOZADOS ------------ */
if ($accion === 'editarFeSolicitud') {
    $id          = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $noEmpleado  = isset($_POST['noEmpleado']) ? (int) $_POST['noEmpleado'] : 0;
    $fsolicitud  = isset($_POST['fsolicitud']) ? $_POST['fsolicitud'] : '';
    $Dgozados    = isset($_POST['Dgozados']) ? (int) $_POST['Dgozados'] : 0;

    if ($id <= 0 || $noEmpleado <= 0) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    $sql = "UPDATE solicitudes SET fesolicitud = ?, Dgozados = ?
            WHERE empleado = ? AND id = ?";
    $ok = false;
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("siii", $fsolicitud, $Dgozados, $noEmpleado, $id);
        $ok = $stmt->execute();
        $stmt->close();
    }

    echo json_encode(['success' => $ok]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Accion no reconocida.']);
