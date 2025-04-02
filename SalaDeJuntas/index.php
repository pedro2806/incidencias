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
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'menu.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include '../encabezado.php'; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Sala de Juntas</h1>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-lg-3">
                            <label for="fecha_hora_inicio">Fecha y hora de inicio:</label>
                            <input class="form-control" type="datetime-local" id="fecha_hora_inicio" name="fecha_hora_inicio" required>
                        </div>
                        <div class="col-xl-3 col-lg-3">
                            <label for="fecha_hora_fin">Fecha y hora de fin:</label>
                            <input class="form-control" type="datetime-local" id="fecha_hora_fin" name="fecha_hora_fin" required>
                        </div>
                        <div class="col-xl-3 col-lg-3">
                            <label for="descripcion">Motivo:</label>
                            <textarea class="form-control" type="text-area" id="descripcion" name="descripcion" required></textarea><br><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-lg-3">
                            <button id="btnSolicitar" type="button" class="btn btn-success" onclick="generarSolicitud()">Reservar</button>
                        </div>
                    </div>
                    <div class="row">
                        <div id="calendar"></div>
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

    function verCalendario() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {        
            initialView: 'timeGridDay', // Cambiar a vista diaria
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
                var displayText = nombreEmpleado + '<br>Inicio: ' + fechaInicioFormateada + fechaFinFormateada;

                return { html: displayText };
            }
        });

        calendar.render();
    }

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
    </script>
</body>

</html>