 <!-- Custom fonts for this template-->
    <link href = "vendor/fontawesome-free/css/all.min.css" rel = "stylesheet" type = "text/css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.css">
    <!-- Custom styles for this template-->
    <link href = "css/sb-admin-2.min.css" rel = "stylesheet">

<?php

include 'conn.php';
mysqli_set_charset($conn, "utf8");

$usuario = $_GET['usuario'];
$voto = $_GET['voto'];
$fecha = date("Y-m-d");

        $sql = "INSERT INTO encuesta_viaje (usuario, voto, fecha) VALUES('$usuario','$voto','$fecha') ON DUPLICATE KEY UPDATE voto='$voto', fecha='$fecha'";
        //mysqli_query($conn, $sql);
        
        $conn->query($sql); 

?>

<center>
    <br>    
    <br>    
    <div class="alert alert-success" role="alert">
          Gracias por tu voto!
    </div>
    
</center>