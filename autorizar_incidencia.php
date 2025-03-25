<?php
include 'conn.php';

$estatus = $_POST['estatusSol'];

$comentariosJefe = $_POST['comentariosJefe'];

$idSolicitud = $_POST['idSolicitud'];

$ndias = $_POST['ndias'];

$estatusRH = $_POST['estatusRH'];

$nempleado = $_POST['nempleado'];

$accion = $_POST['accion'];

if($accion == "estatusRH"){
    $sql = "UPDATE solicitudes SET notajefe = '$comentariosJefe', autorizaRH = $estatusRH WHERE id =  $idSolicitud";
    mysqli_query($conn, $sql);
}
else{
    if($accion == "estatusJ"){
        $sql = "UPDATE solicitudes SET notajefe = '$comentariosJefe', estatus = $estatusRH WHERE id =  $idSolicitud";
        mysqli_query($conn, $sql);
    }
    else{
        $sql = "UPDATE solicitudes SET notajefe = '$comentariosJefe', estatus = $estatus WHERE id =  $idSolicitud";
        mysqli_query($conn, $sql);
    }
}
/*
if($estatus == 2 && $estatusRH == 2){
    $sqlDias = "UPDATE usuarios SET diasdisponibles = (diasdisponibles - $ndias) WHERE noEmpleado =  $nempleado";
    mysqli_query($conn, $sqlDias);
}*/


echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
echo '<script>swal("¡Tu solicitud fue procesada con éxito!", "", "success");</script>';
if($accion == "estatusRH" || $accion == "estatusJ"){
    echo '<script>window.location.assign("listavacaciones")</script>';
}
else{
    echo '<script>window.location.assign("solicitudempleado")</script>';
}

?>