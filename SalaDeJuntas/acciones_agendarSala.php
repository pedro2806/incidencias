<?php
include '../conn.php';

$noEmpleado = $_COOKIE['noEmpleado'];
$finicio = $_POST["finicio"];
$ffin = $_POST["ffin"];

$descripcion = $_POST['descripcion'];
$id = $_POST["id"];

$accion = $_POST["accion"];

if($accion == "agregaSolicitud") {

    // Validar si ya existe una reserva en el horario seleccionado
    $sqlVerifica = "SELECT * FROM reservas 
                     WHERE id_sala = 1 
                     AND ((fecha_hora_inicio <= '$finicio' AND fecha_hora_fin > '$finicio') OR
                         (fecha_hora_inicio < '$ffin' AND fecha_hora_fin >= '$ffin') OR
                         (fecha_hora_inicio >= '$finicio' AND fecha_hora_fin <= '$ffin'))";

    $resultadoVerifica = $conn->query($sqlVerifica);

    if (mysqli_num_rows($resultadoVerifica) > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe una reserva en el horario seleccionado. Por favor, elige otro horario.']);
    } else {
        $sql = "INSERT INTO reservas (id_usuario, id_sala, fecha_hora_inicio, fecha_hora_fin, estatus, descripcion) 
                VALUES ($noEmpleado, 1, '$finicio', '$ffin', 'Reservada', '$descripcion' )";
        $resultadoNuevaSolicitud = $conn->query($sql);
        
        echo json_encode($resultadoNuevaSolicitud);
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
?>