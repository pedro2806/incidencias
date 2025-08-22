<?php 

$conn = mysqli_connect("localhost", "mess_incidencias", "Pipmytrade123", "mess_rrhh");
//incidencias2023

    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }else{
    //echo "Connected successfully";
    }
?>

<?php
// Crear conexi贸n
$conn = new mysqli("localhost", "mess_incidencias", "Pipmytrade123", "mess_rrhh");
// Verificar si la conexi贸n fue exitosa
if ($conn->connect_error) {
    die("Error en la conexi贸n: " . $conn->connect_error);
    
}
?>