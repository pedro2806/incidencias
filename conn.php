<?php 

$conn = mysqli_connect("localhost", "webmess_incidencias", "Pipmytrade123", "webmess_rrhh");
//incidencias2023

    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }else{
    //echo "Connected successfully";
    }
?>

<?php
// Crear conexión
$conn = new mysqli("localhost", "webmess_incidencias", "Pipmytrade123", "webmess_rrhh");
// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
    
}
?>