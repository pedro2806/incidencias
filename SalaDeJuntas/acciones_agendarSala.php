<?php

include '../conn.php';


$noEmpleado = $_COOKIE['noEmpleado'];
$finicio = $_POST["finicio"];
$ffin = $_POST["ffin"];
$accion = $_POST["accion"];

if($accion == "agregaSolicitud") {
    
    
        $fechaInicial = $renglon['fechaInicial'];
        $fechaFinal = $renglon['fechaFinal'];
        $noDias = $renglon['noDias'];
        
        $sql = "INSERT INTO reservas (id_usuario, id_sala, fecha_hora_inicio, fecha_hora_fin, estatus) VALUES ($noEmpleado, 1, '$finicio', '$ffin', 'Reservada' )";
        $resultadoNuevaSolicitud = mysqli_query($conn, $sql);

        
    echo json_encode($resultadoNuevaSolicitud);

}

?>