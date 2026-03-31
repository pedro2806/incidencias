<?php

include 'conn.php';

$opIncidencia = $_POST['opIncidencia'];
$notas = $_POST['notas'];
$comentarios = $_POST['comentarios'];
$solicita = $_POST['solicita'];
$hoy = date("Y-m-d");
$noEmpleado = $_COOKIE['noEmpleado'];
$periodos = $_POST['periodos'];
$accion = $_POST["accion"];




if($accion == "agregaSolicitud") {
    $insertados = 0;
    $idRegistroReferencia = 0;

    foreach ($periodos as $renglon) {
        $fechaInicial = $renglon['fechaInicial'];
        $fechaFinal = $renglon['fechaFinal'];
        $noDias = $renglon['noDias'];
        
        if($noDias == 0){
            $noDias = 1;
        }
        
        $sql = "INSERT INTO solicitudes(empleado, tipo, feinicio, fefin, fesolicitud, dias, notasempleado, notajefe, estatus, solicita, comentarios, autorizaRH, pago, Dgozados) 
                        VALUES ($solicita, '$opIncidencia', '$fechaInicial', '$fechaFinal', '$hoy', $noDias, '$notas', '', 1, $noEmpleado, '$comentarios', 1,'',$noDias)";
        $resultadoNuevaSolicitud = mysqli_query($conn, $sql);

        if ($resultadoNuevaSolicitud) {
            $insertados++;
            $idRegistroReferencia = mysqli_insert_id($conn);
        }

    }
    
    echo json_encode([
        'success' => $insertados > 0,
        'insertados' => $insertados,
        'id_registro_referencia' => $idRegistroReferencia
    ]);
}

?>