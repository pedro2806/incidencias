<?php 

$conn = new mysqli("localhost", "mess_incidencias", "Pipmytrade123", "mess_control_vehicular");
//incidencias2023
  if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
  }
?>

<?php
// Crear conexión
$conn = new mysqli("localhost", "mess_incidencias", "Pipmytrade123", "mess_control_vehicular");
// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
    
}
?>