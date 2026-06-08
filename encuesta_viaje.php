
    <!-- Custom fonts for this template-->
    <link href = "vendor/fontawesome-free/css/all.min.css" rel = "stylesheet" type = "text/css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.css">
    <!-- Custom styles for this template-->
    <link href = "css/sb-admin-2.min.css" rel = "stylesheet">

<body style="background-image: url('imagen_2025-08-15_150230841.png')">
<?php
$noEmpleado_cookie = $_GET['usuario'];
//$noEmpleado_cookie = isset($_COOKIE['noEmpleado']) ? $_COOKIE['noEmpleado'] : null;
?>

<form name="encuesta" id="encuesta" action="registra_voto.php" method="get" style="margin-left: 50px;
    margin-top: 20px; color: black;">
<p>
<strong>Estimado equipo Mess</strong><br>
Como cada año te invitamos a formar parte de nuestro tradicional viaje de integración, al:<br><br>
<strong>
Encantador pueblo de manzanillo<br>
</strong><br>
Es por ello que te invitamos a realizar la encuesta donde esta ocasión tendremos el beneficio de
poder elegir entre todos alguna de las siguientes opciones:
</p>


<fieldset>
  <input type="hidden" id="usuario" name="usuario" value="<?php echo $noEmpleado_cookie; ?>">

  <div>
    <input type="radio" id="a" name="voto" value="a" />
    <label for="a">Opción A: Viaje fin de semana 2 noches 3 días</label>
  </div>

  <div>
    <input type="radio" id="b" name="voto" value="b" />
    <label for="b">Opción B: Viaje entre semana 3 noches 4 días<br>
 <strong>Clausula: </sttrong>para esta opción considera que deberás pedir 1 día de tus
vacaciones</label>
  </div>

</fieldset>
<br>
  <button type="submit">Enviar</button>
</form>

</body>