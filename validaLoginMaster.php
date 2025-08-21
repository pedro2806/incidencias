<?php
include 'conn.php';

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

if ($accion == 'getPlaca') {
    $noEmpleado = isset($_POST['noEmpleado']) ? $_POST['noEmpleado'] : '';
    if (empty($noEmpleado)) {
        echo json_encode(['success' => false, 'message' => 'noEmpleado no recibido.']);
        exit;
    }
    $sqlUsuario = "SELECT id_usuario FROM usuarios WHERE noEmpleado = $noEmpleado";
    $resultUsuario = $conn->query($sqlUsuario);

    if ($resultUsuario && $resultUsuario->num_rows > 0) {
        $rowUsuario = $resultUsuario->fetch_assoc();
        $id_usuario = $rowUsuario['id_usuario'];

        // Obtener todas las placas y modelos en un solo array
        $sqlPlaca = "SELECT placa, modelo FROM inventario WHERE id_usuario = $id_usuario";
        $resultPlaca = $conn->query($sqlPlaca);

        $vehiculos = [];
        if ($resultPlaca && $resultPlaca->num_rows > 0) {
            while ($rowPlaca = $resultPlaca->fetch_assoc()) {
                $vehiculos[] = $rowPlaca['placa'] . ' - ' . $rowPlaca['modelo'];
            }
            echo json_encode(['success' => true, 'vehiculos' => $vehiculos]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró placa para este usuario.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró id_usuario para este noEmpleado.']);
    }
    exit;
}

// Validación de usuario (otro caso)
$id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : '';
$nombredelusuario = isset($_POST['nombredelusuario']) ? $_POST['nombredelusuario'] : '';
$noEmpleado = isset($_POST['noEmpleado']) ? $_POST['noEmpleado'] : '';
$rol = isset($_POST['rol']) ? $_POST['rol'] : '';
$usuario = isset($_POST['correo']) ? $_POST['correo'] : '';

if (empty($id_usuario) || empty($nombredelusuario) || empty($noEmpleado) || empty($rol)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
} else {
    $Qempresas  =  "SELECT  *, TIMESTAMPDIFF(YEAR,fechaIngreso,CURDATE()) AS antiguedad, rol FROM usuarios WHERE usuario  = '".$usuario."' AND estatus = 1";
    $res2 =  mysqli_query( $conn, $Qempresas ) or die (mysqli_error($conn));
    $nr = mysqli_num_rows($res2);

    while ($row2 = mysqli_fetch_array($res2)){
        $nombreEmpleado = $row2["nombre"];
        $noEmpleado = $row2["noEmpleado"];
        $antiguedad = $row2["antiguedad"];
        $diasD = $row2["diasdisponibles"];
        $rol = $row2["rol"];
    }

    if($nr == 1){
        if (!isset($_COOKIE['antiguedad']) || $_COOKIE['antiguedad'] != $antiguedad) {
            setcookie('antiguedad', $antiguedad, time() + 604800, "/", "", false, true);
        }
        if (!isset($_COOKIE['nombredelusuario']) || $_COOKIE['nombredelusuario'] != $nombreEmpleado) {
            setcookie('nombredelusuario', $nombreEmpleado, time() + 604800, "/", "", false, true);
        }
        if (!isset($_COOKIE['noEmpleado']) || $_COOKIE['noEmpleado'] != $noEmpleado) {
            setcookie('noEmpleado', $noEmpleado, time() + 604800, "/", "", false, true);
        }
        if (!isset($_COOKIE['diasD']) || $_COOKIE['diasD'] != $diasD) {
            setcookie('diasD', $diasD, time() + 604800, "/", "", false, true);
        }
        if (!isset($_COOKIE['rol']) || $_COOKIE['rol'] != $rol) {
            setcookie('rol', $rol, time() + 604800, "/", "", false, true);
        }
        if (!isset($_COOKIE['id_usuario']) || $_COOKIE['id_usuario'] != $id_usuario) {
            setcookie('id_usuario', $id_usuario, time() + 604800, "/", "", false, true);
        }

        session_start();
        $_SESSION['nombredelusuario'] = $nombreEmpleado;
        $_SESSION['noEmpleado'] = $noEmpleado;
        $_SESSION['rol'] = $rol;
        $_SESSION['correo'] = $usuario;
        $_SESSION['id_usuario'] = $id_usuario;

        echo json_encode(['success' => true]);
        exit;
    }
    // Si no hay usuario válido
    echo json_encode([
        'success' => false,
        'message' => 'Usuario no válido.',
    ]);
    exit;
}
?>