<?php
include 'conn.php';
mysqli_set_charset($conn, "utf8mb4"); // Si usas MySQLi
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=UTF-8');
    $solicita = $_POST['solicita'];
    
    $sql_jefe= "SELECT (SELECT correo FROM usuarios WHERE noEmpleado = U.jefe) as correoJefe, U.nombre
                FROM usuarios U
                WHERE noEmpleado = $solicita";
    $resul_jefe = $conn->query($sql_jefe);
    
    while ($row2 = $resul_jefe->fetch_assoc()) {
        $jefe = $row2["correoJefe"];
        $solicitaNombre = $row2["nombre"];
    }
    
    $solicitaN = mb_convert_encoding($solicitaNombre, 'UTF-8', 'ISO-8859-1');
    $deAsunto="Solicitud del sistema de incidencias.";
    
    require_once("PHPMailer-master/src/PHPMailer.php");
    require_once("PHPMailer-master/src/SMTP.php");
    

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    $mail->IsSMTP();
	
    $mail->SMTPDebug = 0; // PONER EN 0 SI NO QUIERES QUE SALGA EL LOG EN LA PANTALLA
                          //PONER EN 2 PARA DEPURACION DETALLADA
    //$mail->Debugoutput = 'html';                        //PARA VER DEBUG
    
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // o 587
    $mail->IsHTML(true);
    
    $mail->Username = "mess.metrologia@gmail.com";//////////////////////////////////PONER CUENTA GMAIL
    $mail->Password = "hglidvwsxcbbefhe";////CONTRASENIA DE APLICACION GENERADA DESDE CONSOLA DE GOOGLE
    
    $mail->SetFrom("mess.metrologia@gmail.com", "Solicitud sistema de incidencias");
    $mail->Subject = $deAsunto;
    $mail->Body = ' 
<html>
<head>
    <center> 
        <img width="20%" id="m_-3753487164271908945_x0000_i1025" src="https://www.mess.com.mx/incidencias/img/MESS_05_Imagotipo_1.png" class="CToWUd a6T" data-bit="iit" tabindex="0">
        <br><hr>
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
        Aviso de Nueva Solicitud
    </div>
        
    <center>
    <h1>
         '.utf8_decode($solicitaNombre).' acaba de mandar una solicitud a travez del sistema de incidencias
    </h1>
    <br><br>
    <h2>
        Para validar la solicitud por favor entra al sistema de incidencias.<br> 
        <a href="https://www.mess.com.mx/incidencias"> Ver Solicitud</a>
    </h2>
    </center> <br><br><br><br>
    
    <center>
    <p>Este es un mensaje autom&aacute;tico, por favor no responda a este correo.</p>
    </center>
</body>
</html>';

    //Envío de correo
  
    if (isset($jefe)) {
        //$correos = $jefe; 
        //$Arraycorreos  = explode (",", $correos);
        
        $correos = $jefe; 
        $correos .= ',fernanda.hernandez@mess.com.mx'; // Concatenar el nuevo correo
        $Arraycorreos = explode(",", $correos);

        
        error_log("Correos recibidos: " . print_r($correos, true));

        foreach ($Arraycorreos as $correo) {
            
            if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress(trim($correo));
            } else {
                error_log("Correo inv&aacute;realido: '$correo'");
            }
        }
    } else {
        
    }
        
        if(!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            echo json_encode(["status" => "error", "message" => "Faltan datos"]);
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
            Mensaje Enviado
            </div>
        <?php
            echo json_encode(["status" => "success", "message" => "Mensaje enviado correctamente."]);
        }
?>