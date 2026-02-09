<?php
    include '../conn.php';

    header('Content-Type: text/html; charset=utf-8');
    $deAsunto="Incidencia registrada sin respuesta";

    require("../PHPMailer-master/src/PHPMailer.php");
    require("../PHPMailer-master/src/SMTP.php");
    
    $sqlCorreo = "SELECT us.nombre, us.correo, inc.responsable, inc.fecha_solicitud, inc.comentarios_solicitud, inc.tipo
                    FROM incidencias_solicitudes inc
                    INNER JOIN usuarios us ON inc.responsable = us.id_usuario
                    WHERE inc.fecha_solicitud < DATE_SUB(NOW(), INTERVAL 3 DAY) 
                    AND inc.estatus = 'Abierta'";
                    //echo $sqlCorreo; 
    $resCorreo = $conn->query($sqlCorreo);
    
    while ($rowCorreo = $resCorreo->fetch_assoc()) {
            $correoResponsable = $rowCorreo["correo"];
            $nombreResponsable = $rowCorreo["nombre"];
            $tipo = $rowCorreo["tipo"];
            $comentarios_solicitud = $rowCorreo["comentarios_solicitud"];   
            $fecha_solicitud = $rowCorreo["fecha_solicitud"];
    
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    $mail->IsSMTP();
	
    $mail->SMTPDebug = 0; // PONER EN 0 SI NO QUIERES QUE SALGA EL LOG EN LA PANTALLA
                          //PONER EN 2 PARA DEPURACION DETALLADA
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // o 587
    $mail->IsHTML(true);
    
    
    $mail->Username = "mess.metrologia@gmail.com";//////////////////////////////////PONER CUENTA GMAIL
    $mail->Password = "hglidvwsxcbbefhe";////CONTRASENIA DE APLICACION GENERADA DESDE CONSOLA DE GOOGLE
    
    
    $mail->SetFrom("mess.metrologia@gmail.com", "Seguimiento Incidencias MESS");//////////PONER CUENTA GMAIL
    $mail->Subject = $deAsunto;
    $mail->Body = ' 
<html lang="es">
<head>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <center> 
        <img src="https://messbook.com.mx/mess_logooficial.jpg" style = "width: 20%">
    </center>
    
    <meta charset="UTF-8">
    <style>
        .header {
            background-color: #007BFF;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px 5px 0 0;
        }
    </style>
</head>
<body>
    <div class="header">
        Aviso de seguimiento a incidencia registrada
    </div>
        
    <center>
    <h3>
        '.utf8_decode($nombreResponsable).' tienes una incidencia a la que aun no das respuesta desde el '.$fecha_solicitud.'<br>
        Tipo: '.utf8_decode($tipo).'<br>
    </h3>   
    <h4>
        Comentarios: '.utf8_decode($comentarios_solicitud).'<br>
    </h4>
    <h3>
        <b>Por favor ingresa al sistema de incidencias para darle seguimiento.</b><br>
        <a href="https://messbook.com.mx/incidencias/incidencias" class="btn btn-outline-primary btn-block">
            <i class="fas fa-list fa-lg"></i><br>Revisar
        </a>
    </h3>
    </center> 
    
    <center>
    <p>Este es un mensaje autom&aacute;tico, por favor no responda a este correo.</p>
    </center>
    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src = "../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src = "../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src = "../js/sb-admin-2.min.js"></script>
</body>
</html>';

    //Envío de correo
    
        $correos = $correoResponsable; 
        //$correos .= ','.$correoJefe;
        $correos .= ',pedro.martinez@mess.com.mx'; //correo de pruebas aqui debe de ir el correo del Hugo
        $Arraycorreos  = explode (",", $correos);
        $mensaje = '';
        
        error_log("Correos recibidos: " . print_r($correos, true));
        error_log("Mensaje recibido: '$mensaje'");

        foreach ($Arraycorreos as $correo) {
            
            if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress(trim($correo));
            } else {
                error_log("Correo inválido: '$correo'");
            }
        }
        
        if(!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        } 
        else{
            
        ?>
            <div style="color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            position: relative;
            padding: .75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem;
            ">
            <?php
                echo $correoResponsable;
                echo $nombreResponsable;
            ?>
            Mensaje Enviado
            </div>
        <?php
        //header("location: https://messbook.com.mx/incidencias/incidencias");
        }
    echo json_encode(true);
}
?>