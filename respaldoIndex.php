<!DOCTYPE html>
<html lang = "sp">
<head>
    <meta charset = "utf-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE = edge">
    <meta name = "viewport" content = "width = device-width, initial-scale = 1, shrink-to-fit = no">
    <meta name = "description" content = "">
    <meta name = "author" content = "">

    <title>MESS - Modulo de solicitud de vacaciones</title>

    <!-- Custom fonts for this template-->
    <link href = "vendor/fontawesome-free/css/all.min.css" rel = "stylesheet" type = "text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.css">
    <!-- Custom styles for this template-->
    <link href = "css/sb-admin-2.min.css" rel = "stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.css">
</head>
<body class = "bg-gradient-primary">

    <div class = "container">

        <!-- Outer Row -->
        <div class = "row justify-content-center">

            <div class = "col-xl-10 col-lg-12 col-md-9">

                <div class = "card o-hidden border-0 shadow-lg my-5">
                    <div class = "card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class = "row">
                            <div class = "col-sm-6 d-none d-sm-block bg-login-image">
                                <br>
                                <center>
                                    <b>
                                        RR HH<br>
                                        Incidencias
                                    </b>
                                </center>
                            </div>
                            <div class = "col-lg-6">
                                <div class = "p-5">
                                    <div class = "text-center">
                                        <h1 class = "h4 text-gray-900 mb-4">Bienvenido</h1>
                                    </div>
                                    <br>
                                    <form class = "user" method = "POST">
                                        <!--Se cambio type por text. Sebas 20/03/2025
                                        <div class = "form-group">
                                            <input type = "email" class = "form-control form-control-user" id = "InputEmail" name = "InputEmail" aria-describedby = "emailHelp" placeholder = "Usuario">
                                        </div>
                                        -->
                                        <div class = "form-group">
                                            <input type = "text" class = "form-control form-control-user" id = "InputEmail" name = "InputEmail" aria-describedby = "emailHelp" placeholder = "Usuario">
                                            <span>@mess.com.mx</span>
                                        </div>
                                        <div class = "form-group">
                                            <input type = "password" class = "form-control form-control-user" id = "InputPassword" name = "InputPassword" placeholder = "Contraseña">
                                        </div>
                                        <div class = "form-group">
                                            <div class = "custom-control custom-checkbox small">
                                                <input type = "checkbox" class = "custom-control-input" id = "customCheck">
                                                <label class = "custom-control-label" for = "customCheck">Recordar usuario y contraseña</label>
                                            </div>
                                        </div>
                                        <center>
                                            <input class = "btn btn-primary" type = "submit" name = "btningresar" value = "   Acceder   "/>                                       
                                        </center>
                                        <br>
                                        <hr>
                                    </form>                      
                                        <div class = "text-center" id='calendar'></div>
                                        <br> 
                                        <hr>   
                                        <div class = "text-center">
                                            <center>
                                                <p class="alert alert-info">
                                                    Soporte del sistema:
                                                    <a href="mailto:sebastian.gutierrez@mess.com.mx">sebastian.gutierrez@mess.com.mx</a> 
                                                    o 
                                                    <a href="mailto:pedro.martinez@mess.com.mx">pedro.martinez@mess.com.mx</a>
                                                </p>
                                            </center>
                                            <!--<a class = "small" href = "forgot-password">Olvide mi contraseña</a>-->
                                        </div>                                        
                                        <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <?php
        session_start();
        if(isset($_SESSION['nombredelusuario']))
        {
            header('location: inicio');
        }

        if(isset($_POST['btningresar']))
        {        
            include 'conn.php';
            
            $usuario = $_POST['InputEmail'];
            $pass = $_POST['InputPassword'];
            $usuario = explode('@', $usuario)[0];
            
            $Qempresas  =  "SELECT  *, TIMESTAMPDIFF(YEAR,fechaIngreso,CURDATE()) AS antiguedad, rol FROM usuarios WHERE usuario  = '".$usuario."@mess.com.mx' and password  =  '".$pass."' AND estatus = 1";
            $res2 =  mysqli_query( $conn, $Qempresas ) or die (mysqli_error($conn));
            $nr = mysqli_num_rows($res2);
            
            While ($row2 = mysqli_fetch_array($res2)){
                $nombreEmpleado = $row2["nombre"];
                $noEmpleado = $row2["noEmpleado"];
                $antiguedad = $row2["antiguedad"];
                $diasD = $row2["diasdisponibles"];
                $rol = $row2["rol"];
                
                
            }
    
            if($nr == 1)
            {            
                
                echo '<script>document.cookie = "antiguedad='.$antiguedad.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
                echo '<script>document.cookie = "nombredelusuario='.$nombreEmpleado.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
                echo '<script>document.cookie = "noEmpleado='.$noEmpleado.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
                echo '<script>document.cookie = "diasD='.$diasD.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
                echo '<script>document.cookie = "rol='.$rol.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
                
                
                
                   
                echo '<script>window.location.assign("inicio")</script>';
                
            }
            else if ($nr  ==  0)
            {
                echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
                echo '<script>swal("Usuario o contraseña incorrectos!", "Vuelve a intentar!", "error");</script>';
                
            }
            
        }
    ?>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src = "vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src = "js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.js"></script>
    <script src = "vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function () {
        verCalendarioLogin();
    });

        function verCalendarioLogin() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {        
                initialView: 'listWeek', // Cambiar a vista diaria
                events: 'SalaDeJuntas/acciones_calendarioGral.php?opcion=login', // Aquí llamas a tu PHP que devuelve las vacaciones en JSON
                editable: false,
                locale: 'es',
                eventContent: function(info) {
                    // Personalizar el contenido del evento
                    var nombreEmpleado = info.event.title;
                    var fechaInicio = info.event.start;
                    var fechaFin = info.event.end;

                    // Formatear la fecha y hora de inicio
                    var diaInicio = fechaInicio.getDate();
                    var mesInicio = fechaInicio.getMonth() + 1; // Los meses comienzan desde 0
                    var añoInicio = fechaInicio.getFullYear();
                    var horasInicio = fechaInicio.getHours();
                    var minutosInicio = fechaInicio.getMinutes().toString().padStart(2, '0');

                    var fechaInicioFormateada = diaInicio + '/' + mesInicio + '/' + añoInicio + ' ' + horasInicio + ':' + minutosInicio;

                    // Si se tiene una fecha de fin, formatear también
                    var fechaFinFormateada = '';
                    if (fechaFin) {
                        var diaFin = fechaFin.getDate();
                        var mesFin = fechaFin.getMonth() + 1;
                        var añoFin = fechaFin.getFullYear();
                        var horasFin = fechaFin.getHours();
                        var minutosFin = fechaFin.getMinutes().toString().padStart(2, '0');

                        fechaFinFormateada = '<br>Fin: ' + diaFin + '/' + mesFin + '/' + añoFin + ' ' + horasFin + ':' + minutosFin;
                    }

                    // Mostrar el nombre del empleado, la fecha y hora de inicio, y la fecha y hora de fin (si existe)
                    var displayText = nombreEmpleado + 'Inicio: ' + fechaInicioFormateada + fechaFinFormateada;

                    return { html: displayText };
                }
            });
            calendar.render();
        } 
    </script>
</body>
</html>