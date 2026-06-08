<?php
/**
 * Backend dedicado de la pagina "Nueva solicitud" (nuevasolicitud_v2.php).
 *
 * Acciones (POST 'accion'):
 *   - empleadosSolicita : lista de empleados para los que el usuario puede solicitar
 *   - jefeAutoriza      : nombre del jefe que autoriza al usuario en sesion
 *   - agregarSolicitud  : inserta los periodos de la solicitud
 *
 * Seguridad: todas las consultas usan prepared statements. El numero de
 * empleado y el rol se toman del lado del servidor (cookie + BD), no de lo
 * que envia el cliente.
 */

header('Content-Type: application/json; charset=utf-8');
include 'conn.php';
mysqli_set_charset($conn, "utf8");

// --- Identidad del usuario en sesion (no se confia en el cliente) ---
$noEmpleado = isset($_COOKIE['noEmpleado']) ? (int) $_COOKIE['noEmpleado'] : 0;

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

// El rol se consulta en BD, no se toma del cookie/POST.
$rol = 0;
if ($stmt = $conn->prepare("SELECT rol FROM usuarios WHERE noEmpleado = ?")) {
    $stmt->bind_param("i", $noEmpleado);
    $stmt->execute();
    $stmt->bind_result($rolDb);
    if ($stmt->fetch()) {
        $rol = (int) $rolDb;
    }
    $stmt->close();
}

/* ------------------------------------------------------------------ *
 * Lista de empleados para los que el usuario puede crear solicitudes  *
 * ------------------------------------------------------------------ */
if ($accion === 'empleadosSolicita') {
    $usuarios = [];

    if ($rol == 1) {
        $sql  = "SELECT noEmpleado, nombre FROM usuarios WHERE estatus = 1 ORDER BY nombre";
        $stmt = $conn->prepare($sql);
    } elseif ($rol == 2) {
        $sql  = "SELECT noEmpleado, nombre FROM usuarios ORDER BY nombre";
        $stmt = $conn->prepare($sql);
    } else { // rol 3 u otros
        if ($noEmpleado == 403) {
            $sql  = "SELECT noEmpleado, nombre FROM usuarios ORDER BY nombre";
            $stmt = $conn->prepare($sql);
        } else {
            $sql  = "(SELECT u.noEmpleado, u.nombre FROM usuarios u
                          WHERE u.jefe = ? AND u.estatus = 1)
                     UNION
                     (SELECT noEmpleado, nombre FROM usuarios WHERE noEmpleado = ?)
                     ORDER BY nombre";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $noEmpleado, $noEmpleado);
        }
    }

    if (!$stmt) {
        echo json_encode([]);
        exit;
    }

    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $usuarios[] = [
            'noEmpleado' => $row['noEmpleado'],
            'nombre'     => $row['nombre'],
        ];
    }
    $stmt->close();

    echo json_encode($usuarios);
    exit;
}

/* ------------------------------------------------------------------ *
 * Nombre del jefe que autoriza al usuario en sesion                   *
 * ------------------------------------------------------------------ */
if ($accion === 'jefeAutoriza') {
    $jefe = '';
    $sql  = "SELECT us.nombre AS jefe
             FROM usuarios a
             LEFT JOIN usuarios us ON a.jefe = us.noEmpleado
             WHERE a.noEmpleado = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $noEmpleado);
        $stmt->execute();
        $stmt->bind_result($jefeDb);
        if ($stmt->fetch()) {
            $jefe = $jefeDb;
        }
        $stmt->close();
    }

    echo json_encode(['success' => true, 'jefe' => $jefe]);
    exit;
}

/* ------------------------------------------------------------------ *
 * Inserta la solicitud (uno o varios periodos)                        *
 * ------------------------------------------------------------------ */
if ($accion === 'agregarSolicitud') {
    $opIncidencia = isset($_POST['opIncidencia']) ? (int) $_POST['opIncidencia'] : 0;
    $notas        = isset($_POST['notas']) ? $_POST['notas'] : '';
    $comentarios  = isset($_POST['comentarios']) ? $_POST['comentarios'] : '';
    $solicita     = isset($_POST['solicita']) ? (int) $_POST['solicita'] : 0;
    $periodos     = isset($_POST['periodos']) ? $_POST['periodos'] : [];
    $hoy          = date("Y-m-d");

    if ($solicita <= 0 || $opIncidencia <= 0 || !is_array($periodos) || count($periodos) === 0) {
        echo json_encode(['success' => false, 'message' => 'Datos de la solicitud incompletos.']);
        exit;
    }

    $sql = "INSERT INTO solicitudes
                (empleado, tipo, feinicio, fefin, fesolicitud, dias,
                 notasempleado, notajefe, estatus, solicita, comentarios,
                 autorizaRH, pago, Dgozados)
            VALUES (?, ?, ?, ?, ?, ?, ?, '', 1, ?, ?, 1, '', ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
        exit;
    }

    $insertados           = 0;
    $idRegistroReferencia = 0;

    $conn->begin_transaction();
    try {
        foreach ($periodos as $renglon) {
            $fechaInicial = isset($renglon['fechaInicial']) ? $renglon['fechaInicial'] : '';
            $fechaFinal   = isset($renglon['fechaFinal']) ? $renglon['fechaFinal'] : '';
            $noDias       = isset($renglon['noDias']) ? (int) $renglon['noDias'] : 0;
            if ($noDias == 0) {
                $noDias = 1;
            }

            $stmt->bind_param(
                "issssisisi",
                $solicita,      // empleado
                $opIncidencia,  // tipo
                $fechaInicial,  // feinicio
                $fechaFinal,    // fefin
                $hoy,           // fesolicitud
                $noDias,        // dias
                $notas,         // notasempleado
                $noEmpleado,    // solicita (quien captura)
                $comentarios,   // comentarios
                $noDias         // Dgozados
            );

            if ($stmt->execute()) {
                $insertados++;
                $idRegistroReferencia = $conn->insert_id;
            }
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        $insertados = 0;
    }

    $stmt->close();

    echo json_encode([
        'success'                => $insertados > 0,
        'insertados'             => $insertados,
        'id_registro_referencia' => $idRegistroReferencia,
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Accion no reconocida.']);
