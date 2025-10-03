<?php
include 'conn.php';

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

if ($accion == 'getPlaca') {
    include '../ControlVehicular/conn.php';
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
    mysqli_close($conn);
}

// Validación de usuario (otro caso)
$id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : (isset($_POST['id_usuarioSJ']) ? $_POST['id_usuarioSJ'] : '');
$nombredelusuario = isset($_POST['nombredelusuario']) ? $_POST['nombredelusuario'] : (isset($_POST['nombredelusuarioSJ']) ? $_POST['nombredelusuarioSJ'] : '');
$noEmpleado = isset($_POST['noEmpleado']) ? $_POST['noEmpleado'] : (isset($_POST['noEmpleadoSJ']) ? $_POST['noEmpleadoSJ'] : '');
//$rol = isset($_POST['rol']) ? $_POST['rol'] : (isset($_POST['rolSJ']) ? $_POST['rolSJ'] : '');
$usuario = isset($_POST['correo']) ? $_POST['correo'] : ( isset($_POST['correoSJ']) ? $_POST['correoSJ'] : '');
$sistema = isset($_POST['sistema']) ? $_POST['sistema'] : (isset($_POST['sistemaSJ']) ? $_POST['sistemaSJ'] : '');

if (empty($id_usuario) || empty($noEmpleado)) {
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
        echo '<script>document.cookie = "antiguedad='.$antiguedad.';expires=" + new Date(Date.now() + 86400000).toUTCString() + ";SameSite=Lax;";</script>';
        echo '<script>document.cookie = "nombredelusuario='.$nombreEmpleado.';expires=" + new Date(Date.now() + 86400000).toUTCString() + ";SameSite=Lax;";</script>';
        echo '<script>document.cookie = "noEmpleado='.$noEmpleado.';expires=" + new Date(Date.now() + 86400000).toUTCString() + ";SameSite=Lax;";</script>';
        echo '<script>document.cookie = "diasD='.$diasD.';expires=" + new Date(Date.now() + 86400000).toUTCString() + ";SameSite=Lax;";</script>';
        echo '<script>document.cookie = "rol='.$rol.';expires=" + new Date(Date.now() + 86400000).toUTCString() + ";SameSite=Lax;";</script>';
        echo '<script>document.cookie = "SesionLogin=LoginMaster; expires=" + new Date(Date.now() + 99999000).toUTCString() + ";SameSite=Lax;";</script>';
        if($sistema == "saladeJuntas"){ 
            echo '<script>window.location.assign("SalaDeJuntas/")</script>';                
        } else{
            echo '<script>window.location.assign("inicio")</script>';                
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