<?php
include("conn.php");
header('Content-Type: application/json; charset=utf-8');

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
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

echo json_encode(['success' => false, 'message' => 'Acción no soportada']);
exit;
?>                                                                                      