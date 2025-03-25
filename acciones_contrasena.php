<?php
header('Content-Type: application/json');
include 'conn.php';
mysqli_set_charset($conn, "utf8mb4");

$accion = $_POST["accion"];

$noEmpleado = $_POST["noEmpleado"];
$password = $_POST["password"];
$id_empleado = $_POST['id_empleado'];

$idEmpleado = $_POST["idEmpleado"];
$nombre = $_POST["nombre"];
$correo = $_POST["correo"];
$puesto = $_POST["puesto"];
$region = $_POST["region"];
$departamento = $_POST["departamento"];
$fechaIngreso = $_POST["fechaIngreso"];
$rol = $_POST["rol"];
$jefe = $_POST["jefe"];

/*----------------------------------------------------------------------------*/

//Cambiar Contraseña
    if($accion == "CambioPassword") {
     
        $sqlcambiopass = "UPDATE usuarios 
                          SET password = '$password'
                          WHERE noEmpleado='$noEmpleado'";
        $resultcambiopass = mysqli_query($conn, $sqlcambiopass);
         
        // Devolver los datos en formato JSON
        echo json_encode($resultcambiopass);     
    }

//MODIFICAR Usuario 
    if($accion == "ModificarUS") {
     
        $sqlcambioUS = "UPDATE usuarios 
                          SET nombre = '$nombre',
                              correo = '$correo',
                              puesto = '$puesto',
                              region = '$region',
                              departamento = '$departamento',
                              fechaIngreso = '$fechaIngreso',
                              rol = '$rol',
                              jefe = '$jefe'
                          WHERE id ='$idEmpleado'";
                   
        $resultcambioUS= mysqli_query($conn, $sqlcambioUS);
        
        echo json_encode($resultcambioUS);     
    }
    
//MODIFICAR Usuario 
    if($accion == "BajaUS") {

        $sqlBajaUs = "UPDATE usuarios 
                          SET estatus = 0
                          WHERE id ='$idEmpleado'";
              
        $resultBajaUS= mysqli_query($conn, $sqlBajaUs);
        
        echo json_encode($resultBajaUS);     
    }
    
?>