<?php 
include '../conn.php';

$sql = "SELECT reservas.id, reservas.fecha_hora_inicio, reservas.fecha_hora_fin, salas.nombre_sala, usuarios.nombre AS nombre_usuario 
FROM reservas 
JOIN salas ON reservas.id_sala = salas.id_sala
JOIN usuarios ON reservas.id_usuario = usuarios.noEmpleado";


$result = $conn->query($sql);

$reservas = [];
while ($row = $result->fetch_assoc()) {
$reservas[] = [
'title' => $row['nombre_sala'] . ' - ' . $row['nombre_usuario'], // Mostrar el nombre de la sala y el usuario
'start' => $row['fecha_hora_inicio'],
'end'   => $row['fecha_hora_fin']
];
}

echo json_encode($reservas);
?>