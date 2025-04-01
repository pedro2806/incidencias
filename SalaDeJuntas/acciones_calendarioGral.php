<?php 
include '../conn.php';

$accion = $_GET['opcion'];

if($accion == 'rrhh'){
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
}

if($accion == 'login'){
    $Calendario_Login = "SELECT reservas.id, reservas.fecha_hora_inicio, reservas.fecha_hora_fin, salas.nombre_sala, usuarios.nombre AS nombre_usuario, reservas.descripcion
    FROM reservas 
    JOIN salas ON reservas.id_sala = salas.id_sala
    JOIN usuarios ON reservas.id_usuario = usuarios.noEmpleado
    WHERE DATE(reservas.fecha_hora_inicio) = CURDATE()";
    
    $result2 = $conn->query($Calendario_Login);
    $reservas = [];
    while ($row = $result2->fetch_assoc()) {
        $reservas[] = [
        'title' => $row['nombre_usuario'], // Mostrar el nombre el usuario
        'start' => $row['fecha_hora_inicio'],
        'end'   => $row['fecha_hora_fin'],
        'descripcion' => $row['descripcion'] // Mostrar la descripción de la reserva      
        ];
    }
    echo json_encode($reservas);
}
?>