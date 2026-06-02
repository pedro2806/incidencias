<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sala de Juntas - Gestión de Reservas">
    <meta name="author" content="MESS Team">
    <meta name="keywords" content="Sala de Juntas, Reservas, Calendario, Gestión">
    <title>Sala de Juntas - Reservas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css' rel='stylesheet' />
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
    /* Estética Minimalista y Pro */
    :root {
        --glass-bg: #ffffff;
        --border-color: #e3e6f0;
        --primary-accent: #4e73df;
        --success-accent: #1cc88a;
    }

    .booking-toolbar {
        background: var(--glass-bg);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    .input-group-modern {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .input-group-modern label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #858796;
        letter-spacing: 0.05em;
        margin-left: 2px;
    }

    .form-control-pro {
        border: 1px solid #d1d3e2;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control-pro:focus {
        border-color: var(--primary-accent);
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
        outline: none;
    }

    .btn-action {
        height: 44px;
        margin-top: 25px; /* Alineación perfecta con los inputs */
        border-radius: 8px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        transition: all 0.3s;
        width: 100%;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(28, 200, 138, 0.2);
    }

    #calendar {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
</style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'menu.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include '../encabezado.php'; ?>
                
                <div class="container-fluid">                    
                    <!-- Toolbar de Reserva -->
                    <div class="booking-toolbar">
                        <div class="row align-items-end">
                            <div class="col-xl-3 col-lg-4 mb-2 mb-xl-0">
                                <div class="input-group-modern">
                                    <label for="fecha_hora_inicio">Comienza</label>
                                    <input class="form-control-pro" type="datetime-local" id="fecha_hora_inicio" name="fecha_hora_inicio" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-3 col-lg-4 mb-2 mb-xl-0">
                                <div class="input-group-modern">
                                    <label for="fecha_hora_fin">Termina</label>
                                    <input class="form-control-pro" type="datetime-local" id="fecha_hora_fin" name="fecha_hora_fin" required>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 mb-2 mb-xl-0">
                                <div class="input-group-modern">
                                    <label for="descripcion">Motivo / Asunto</label>
                                    <textarea class="form-control-pro" id="descripcion" name="descripcion" rows="1" placeholder="Breve descripción..." required></textarea>
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-12">
                                <button id="btnSolicitar" type="button" class="btn btn-success btn-action" onclick="validarReserva()">
                                    <i class="fas fa-calendar-plus mr-2"></i> Reservar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sección del Calendario -->
                    <div class="row">
                        <div class="col-12">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; MESS 2025</span>
                    </div>
                </div>
            </footer>
            </div>
        </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.9/index.global.min.js'></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
    $(document).ready(function () {
        verCalendario();
    });

    //Funcion para limitar la fecha y hora de inicio 
    document.addEventListener("DOMContentLoaded", function () {
        let inputFechaHoraInicio = document.getElementById("fecha_hora_inicio");
        let inputFechaHoraFin = document.getElementById("fecha_hora_fin");

        inputFechaHoraInicio.addEventListener("input", function () {
            let fechaSeleccionada = new Date(this.value);
            let hora = fechaSeleccionada.getHours();

            if (hora  <7 && hora >20) {
                alert (fechaSeleccionada+hora);
                Swal.fire({
                    title: `Por favor, selecciona una hora válida.`,
                    text: `Debe estar entre las 7:00 y las 19:00.`,
                    icon: "warning",
                });
                this.value = ""; // Borra el valor si está fuera del rango
            }
        });
        inputFechaHoraFin.addEventListener("input", function () {
            let fechaSeleccionada = new Date(this.value);
            let hora = fechaSeleccionada.getHours();

            if (hora <=7 && hora  >=20) {
                Swal.fire({
                    title: `Por favor, selecciona una hora válida fin.`,
                    text: `Debe estar entre las 7:00 y las 19:00.`,
                    icon: "warning",
                });
                this.value = ""; // Borra el valor si está fuera del rango
            }
        });
    });

    //Funcion para mostrar el calendario
    function verCalendario() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {        
            initialView: 'timeGrid', // Cambiar a vista diaria
            locale: 'es', // Configurar el idioma a español
            events: 'acciones_calendarioGral.php?opcion=rrhh', // Aquí llamas a tu PHP que devuelve las vacaciones en JSON
            editable: false,
            slotMinTime: '07:00:00', // Mostrar desde las 7am
            slotMaxTime: '20:00:00', // Mostrar hasta las 19pm
            eventTimeFormat: { //Formato de 24 horas
                hour: '2-digit',
                minute: '2-digit',
                hour12: false // Desactivar el formato de 12 horas
            },
            eventContent: function(info) {
                // Personalizar el contenido del evento
                var nombreEmpleado = info.event.title;
                var fechaInicio = info.event.start;
                var fechaFin = info.event.end;
                // Formatear la fecha y hora de inicio
                var diaInicio = fechaInicio.getDate();
                var mesInicio = fechaInicio.getMonth() + 1; 
                var añoInicio = fechaInicio.getFullYear();
                var horasInicio = fechaInicio.getHours();
                var minutosInicio = fechaInicio.getMinutes().toString().padStart(2, '0'); // Asegurar que los minutos tengan dos dígitos
                var fechaInicioFormateada = diaInicio + '/' + mesInicio + '/' + añoInicio + ' ' + horasInicio + ':' + minutosInicio;

                // Si se tiene una fecha de fin, formatear también                
                var fechaFinFormateada = '';
                if (fechaFin) {
                    var diaFin = fechaFin.getDate();
                    var mesFin = fechaFin.getMonth() + 1;
                    var añoFin = fechaFin.getFullYear();
                    var horasFin = fechaFin.getHours();
                    var minutosFin = fechaFin.getMinutes().toString().padStart(2, '0'); // Asegurar que los minutos tengan dos dígitos
                    fechaFinFormateada = '<br>Fin: ' + diaFin + '/' + mesFin + '/' + añoFin + ' ' + horasFin + ':' + minutosFin;
                }

                // Mostrar el nombre del empleado, la fecha y hora de inicio, y la fecha y hora de fin (si existe)
                var displayText = nombreEmpleado + '- Inicio: ' + horasInicio + ':' + minutosInicio + ' - ' + '- Fin: ' + horasFin + ':' + minutosFin;
                return { html: displayText };
            }
        });
        calendar.render();
    }

    //Funcion para Validar Reserva
    function validarReserva() {
        finicio = $('#fecha_hora_inicio').val();
        ffin = $('#fecha_hora_fin').val();
        descripcion = $('#descripcion').val();
        accion = "verificaReserva";
        
        $.ajax({
            url: 'acciones_agendarSala',
            type: 'POST',
            dataType: 'json',
            data:{ finicio, ffin, descripcion, accion},
            success: function (response) {
                if (response.success === false && finicio >= ffin) {
                    // Alerta especial si la fecha de inicio es mayor o igual a la fecha de fin
                    Swal.fire({
                        title: "Detalle en las fechas",
                        text: "Revisa tus fechas y horas de reserva.",
                        icon: "warning",
                        draggable: true
                    });
                } else if (response.success === false) {
                    // Alerta si hay un conflicto de horarios
                    Swal.fire({
                        title: "Conflicto de horario",
                        text: "Ya existe una reserva en este horario.",
                        icon: "warning",
                        draggable: true
                    });
                } else {
                    // Si no hay conflictos, genera la solicitud
                    generarSolicitud();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {                    
                Swal.fire({             
                    title: "Error",
                    text: "Error al verificar la reserva 2.",              
                    icon: "error",
                    draggable: true
                });                    
            }
        });   
    }

    //Funcion para generar la solicitud de reserva
    function generarSolicitud() {
        finicio = $('#fecha_hora_inicio').val();
        ffin = $('#fecha_hora_fin').val();
        descripcion = $('#descripcion').val();
        accion = "agregaSolicitud";
        
        $.ajax({
            url: 'acciones_agendarSala',
            type: 'POST',
            dataType: 'json',
            data:{ finicio, ffin, descripcion, accion},
            success: function (response) {
                Swal.fire({
                    title: "Reserva registrada con éxito!",
                    icon: "success",
                    draggable: true
                });
                verCalendario();
            },
            error: function (jqXHR, textStatus, errorThrown) {                    
                Swal.fire({
                    title: "ya existe este horario en reserva. Inténta un nuevo horario.",
                    icon: "error",
                    draggable: true
                });                    
            }
        });   
    }

    //Funcion para obtener el valor de la cookie
    function getCookie(name) {
        const cookies = new URLSearchParams(document.cookie.replace(/; /g, '&'));
        return cookies.get(name) || undefined;
    }

    //Funcion para Enviar Notificacion
    function enviaNotificacion() {
        let solicita = getCookie('noEmpleado');
        $.ajax({
            url: 'enviaNotificacion.',
            method: 'POST',
            dataType: 'json',
            data: {solicita},
            success: function(data) {
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: "Error al notificar por correo!",
                    icon: "error",
                    draggable: true
                });
            }
        });
    }
    </script>
</body>
</html>