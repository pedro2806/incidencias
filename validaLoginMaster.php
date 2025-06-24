<?php
// validaLoginMaster.php

// Get POST data
$id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : '';
$nombredelusuario = isset($_POST['nombredelusuario']) ? $_POST['nombredelusuario'] : '';
$noEmpleado = isset($_POST['noEmpleado']) ? $_POST['noEmpleado'] : '';
$rol = isset($_POST['rol']) ? $_POST['rol'] : '';

// Simple validation (you can expand this as needed)
if (empty($id_usuario) || empty($nombredelusuario) || empty($noEmpleado) || empty($rol)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}
else{

    $Qempresas  =  "SELECT  *, TIMESTAMPDIFF(YEAR,fechaIngreso,CURDATE()) AS antiguedad, rol FROM usuarios WHERE usuario  = '".$usuario."@mess.com.mx' and password  =  '".$pass."' AND estatus = 1";
            $res2 =  mysqli_query( $conn, $Qempresas ) or die (mysqli_error($conn));
            $nr = mysqli_num_rows($res2);
            
            While ($row2 = mysqli_fetch_array($res2)){
                $nombreEmpleado = $row2["nombre"];
                $noEmpleado = $row2["noEmpleado"];
                $antiguedad = $row2["antiguedad"];
                $diasD = $row2["diasdisponibles"];
                $rol = $row2["rol"];
                
                
            }
    
            if($nr == 1)
            {                            
                echo '<script>document.cookie = "antiguedad='.$antiguedad.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
                echo '<script>document.cookie = "nombredelusuario='.$nombreEmpleado.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
                echo '<script>document.cookie = "noEmpleado='.$noEmpleado.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
                echo '<script>document.cookie = "diasD='.$diasD.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
                echo '<script>document.cookie = "rol='.$rol.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';

                echo '<script>window.location.assign("inicio")</script>';
                
            }    
}

// Aquí puedes agregar la lógica para validar el usuario, por ejemplo, consultar en la base de datos

// Ejemplo de respuesta exitosa
echo json_encode([
    'success' => true,
    'message' => 'Usuario validado correctamente.',
    'data' => [
        'id_usuario' => $id_usuario,
        'nombredelusuario' => $nombredelusuario,
        'noEmpleado' => $noEmpleado,
        'rol' => $rol
    ]
]);
?>