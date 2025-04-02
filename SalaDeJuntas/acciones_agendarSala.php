<?php
include '../conn.php';

$noEmpleado = $_COOKIE['noEmpleado'];
$finicio =  date('Y-m-d H:i:s', strtotime($_POST["finicio"]));
$ffin =  date('Y-m-d H:i:s', strtotime($_POST["ffin"]));

$descripcion = $_POST['descripcion'];
$id = $_POST["id"];

$accion = $_POST["accion"];

if($accion == "agregaSolicitud") {
    
        $sql = "INSERT INTO reservas (id_usuario, id_sala, fecha_hora_inicio, fecha_hora_fin, estatus, descripcion) 
                VALUES ($noEmpleado, 1, '$finicio', '$ffin', 'Reservada', '$descripcion' )";
        $resultadoNuevaSolicitud = $conn->query($sql);
        
        echo json_encode($resultadoNuevaSolicitud);
    
}

if ($accion == "verificaReserva"){
    
    $sqlVerifica = "SELECT * FROM reservas 
                    WHERE id_sala = 1 
                    AND fecha_hora_inicio <= '$ffin' 
                    AND fecha_hora_fin >= '$finicio'";

    $resultadoVerifica = $conn->query($sqlVerifica);

    if ($resultadoVerifica->num_rows > 0) {
        echo json_encode(['success' => false]); // Hay conflicto
    } else {
        echo json_encode(['success' => true]); // No hay conflicto
    }
}

if($accion == "eliminaSolicitud") {
    
    $sqlBorrar = "UPDATE reservas
                  SET estatus = 'Cancelada'
                  WHERE id = $id";
    $resultadoElimina = $conn->query($sqlBorrar);
    
    echo json_encode($resultadoElimina);
}

if($accion == "actualizaSolicitud") {
    
    $sqlActualiza = "UPDATE reservas 
                     SET fecha_hora_inicio='$finicio', fecha_hora_fin='$ffin', descripcion='$descripcion' 
                     WHERE id=$id";
    $resultadoActualiza = $conn->query($sqlActualiza);
    
    echo json_encode($resultadoActualiza);
}

// Obtener información de la reserva para editar
if ($accion == "obtenerReserva") {
   
    $sqlObtenerInfo = "SELECT id, fecha_hora_inicio, fecha_hora_fin, descripcion 
                   FROM reservas 
                   WHERE id = $id AND id_usuario = $noEmpleado";
    $resultadoObtenerInfo = $conn->query($sqlObtenerInfo);

    if ($resultadoObtenerInfo->num_rows > 0) {
        $reserva = $resultadoObtenerInfo->fetch_assoc();
        echo json_encode([
            'success' => true,
            'data' => $reserva
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró la reserva o no tienes permisos para verla.'
        ]);
    }
}

// Obtener todas las reservas del usuario para mostrarlas en el calendario
if ($accion == "obtenerReservas") {
    $sqlObtenerReservas = "SELECT id, fecha_hora_inicio, fecha_hora_fin, descripcion 
                           FROM reservas 
                           WHERE id_usuario = $noEmpleado";
    $resultadoObtenerReservas = $conn->query($sqlObtenerReservas);

    $reservas = [];
    if ($resultadoObtenerReservas->num_rows > 0) {
        while ($row = $resultadoObtenerReservas->fetch_assoc()) {
            $reservas[] = $row;
        }
    }
    echo json_encode([
        'success' => true,
        'data' => $reservas
    ]);
}

if ($accion == "VerReservasCanceladas"){
    $sqlVerCanceladas = "SELECT reservas.id, reservas.fecha_hora_inicio, reservas.fecha_hora_fin, 
                                usuarios.nombre AS nombre_usuario, reservas.descripcion 
                         FROM reservas  
                         JOIN usuarios ON reservas.id_usuario = usuarios.noEmpleado 
                         WHERE reservas.estatus = 'Cancelada'";

    $result2 = $conn->query($sqlVerCanceladas);
    $reservas = [];
    while ($row = $result2->fetch_assoc()) {
        $reservas[] = [
        'id' => $row['id'], 
        'nombre_usuario' => $row['nombre_usuario'], 
        'fecha_hora_inicio' => $row['fecha_hora_inicio'],
        'fecha_hora_fin'   => $row['fecha_hora_fin'],
        'descripcion' => $row['descripcion']       
        ];
    }
    echo json_encode([
        'success' => true,
        'data' => $reservas
    ]);
}
?>