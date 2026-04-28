<?php
include("conn.php");
header('Content-Type: application/json; charset=utf-8');

$accion = isset($_POST['accion']) ? $_POST['accion'] : (isset($_GET['accion']) ? $_GET['accion'] : '');
$noEmpleado = isset($_COOKIE['noEmpleado']) ? trim($_COOKIE['noEmpleado']) : '';

// Registrar Notificación para Vacaciones
if ($accion === 'registrarNotificacionVacaciones') {
    $idUsuarioActualiza = intval($noEmpleado);
    $accionNotificacion = 'SolicitudVacaciones';
    $sistema = 'vacaciones';
    $archivo = 'solicitudempleado';

    if ($noEmpleado === '' || !ctype_digit($noEmpleado)) {
        echo json_encode(['success' => false, 'message' => 'Tu sesión expiró, por favor inicia sesión nuevamente']);
        exit;
    }

    if ($solicita === '' || !ctype_digit($solicita)) {
        echo json_encode(['success' => false, 'message' => 'Solicitante inválido']);
        exit;
    }

    $sqlDestino = "SELECT jefe FROM usuarios WHERE noEmpleado = ? LIMIT 1";
    $stmtDestino = $conn->prepare($sqlDestino);
    if (!$stmtDestino) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar destino']);
        exit;
    }

    $solicitaInt = intval($solicita);
    $stmtDestino->bind_param("i", $solicitaInt);
    $stmtDestino->execute();
    $resultDestino = $stmtDestino->get_result();

    $id_usuario_Destino = 0;
    if ($rowDestino = $resultDestino->fetch_assoc()) {
        $id_usuario_Destino = intval($rowDestino['jefe']);
    }
    $stmtDestino->close();

    if ($id_usuario_Destino <= 0) {
        echo json_encode(['success' => true, 'insertados' => 0, 'message' => 'Sin jefe destino']);
        exit;
    }

    $sqlInsert = "INSERT INTO notificacion_historial
        (id_usuario_actualiza, id_usuario_destino, accion, sistema, archivo, id_registro_referencia, fecha_creacion, fecha_atencion, recordar, estatus)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NULL, 'Solicitó vacaciones', 'NoLeida')";

    $stmtInsert = $conn->prepare($sqlInsert);
    if (!$stmtInsert) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar inserción']);
        exit;
    }

    $insertados = 0;
    $idRef = $idRegistroReferencia > 0 ? $idRegistroReferencia : 0;
    $stmtInsert->bind_param("iisssi", $idUsuarioActualiza, $id_usuario_Destino, $accionNotificacion, $sistema, $archivo, $idRef);
    if ($stmtInsert->execute()) {
        $insertados++;
    }
    $stmtInsert->close();

    echo json_encode(['success' => true, 'insertados' => $insertados], JSON_UNESCAPED_UNICODE);
    exit;
}

// Obtener empleados activos
if ($accion === 'obtenerEmpleados') {
    $sql = "SELECT id_usuario, noEmpleado, nombre FROM usuarios WHERE estatus = 1 ORDER BY nombre ASC";
    $result = $conn->query($sql);
    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener empleados']);
        exit;
    }
    $empleados = [];
    while ($row = $result->fetch_assoc()) {
        $empleados[] = $row;
    }
    echo json_encode(['success' => true, 'empleados' => $empleados], JSON_UNESCAPED_UNICODE);
    exit;
}

// Descontar días a los empleados seleccionados
if ($accion === 'descontarDias') {
    if ($noEmpleado === '' || !ctype_digit($noEmpleado)) {
        echo json_encode(['success' => false, 'message' => 'Sesión inválida']);
        exit;
    }
    $noEmpleadoInt = intval($noEmpleado);

    $sqlAcc = "SELECT id FROM accesos_especiales WHERE noEmpleado = ? AND sistema = 'incidencias' AND opcion = 'verDescontadorDeDias' AND estatus = 1 LIMIT 1";
    $stmtAcc = $conn->prepare($sqlAcc);
    $stmtAcc->bind_param("i", $noEmpleadoInt);
    $stmtAcc->execute();
    if ($stmtAcc->get_result()->num_rows === 0) {
        $stmtAcc->close();
        echo json_encode(['success' => false, 'message' => 'Sin permisos para esta acción']);
        exit;
    }
    $stmtAcc->close();

    $fecha_inicio = isset($_POST['fecha_inicio']) ? trim($_POST['fecha_inicio']) : '';
    $fecha_fin    = isset($_POST['fecha_fin'])    ? trim($_POST['fecha_fin'])    : '';
    $dias         = isset($_POST['dias'])         ? intval($_POST['dias'])       : 0;
    $razon        = isset($_POST['razon'])        ? trim($_POST['razon'])        : '';
    $empleados    = isset($_POST['empleados'])    ? $_POST['empleados']          : [];

    if (!$fecha_inicio || !$fecha_fin || $dias <= 0 || $razon === '' || empty($empleados)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }

    $sql = "INSERT INTO solicitudes (empleado, tipo, feinicio, fefin, fesolicitud, dias, notasempleado, notajefe, estatus, solicita, comentarios, autorizaRH, pago, Dgozados, origen)
            VALUES (?, 1, ?, ?, CURDATE(), ?, ?, ?, 2, ?, ?, 2, 'No', 0, 'RRHH')";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar consulta']);
        exit;
    }

    $insertados = 0;
    foreach ($empleados as $noEmpVictima) {
        $noEmpVictimaInt = intval($noEmpVictima);
        if ($noEmpVictimaInt <= 0) continue;
        $stmt->bind_param("ississis", $noEmpVictimaInt, $fecha_inicio, $fecha_fin, $dias, $razon, $razon, $noEmpleadoInt, $razon);
        if ($stmt->execute()) {
            $insertados++;
        }
    }
    $stmt->close();

    echo json_encode(['success' => true, 'insertados' => $insertados, 'message' => "Descuento aplicado a $insertados empleado(s)"], JSON_UNESCAPED_UNICODE);
    exit;
}

// Obtener historial de descuentos
if ($accion === 'obtenerHistorial') {
    $sql = "SELECT s.id, u.nombre AS empleado,
                   DATE_FORMAT(s.feinicio, '%d/%m/%Y') AS fecha_inicio,
                   DATE_FORMAT(s.fefin, '%d/%m/%Y') AS fecha_fin,
                   s.dias, s.origen AS razon,
                   a.nombre AS admin,
                   DATE_FORMAT(s.fesolicitud, '%d/%m/%Y') AS fecha_registro
            FROM solicitudes s
            INNER JOIN usuarios u ON s.empleado = u.noEmpleado
            LEFT JOIN usuarios a ON s.solicita = a.noEmpleado
            WHERE s.tipo = 1 AND s.origen = 'RRHH'
            ORDER BY s.id DESC";
    $result = $conn->query($sql);
    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener historial']);
        exit;
    }
    $historial = [];
    while ($row = $result->fetch_assoc()) {
        $historial[] = $row;
    }
    echo json_encode(['success' => true, 'historial' => $historial], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Acción no soportada']);
exit;
?>                                                                                      