/**
 * Front de "Ver solicitudes de vacaciones" (verVacaciones_v2.php) - vista RH.
 *
 * Consume acciones_verVacaciones.php (POST, opcion):
 *   - llenaSelectPersonal  : empleados activos para el Select2
 *   - llenaTablaVacaciones : solicitudes del empleado filtrado
 *   - llenaTablaServicios  : OTs/servicios del ingeniero filtrado
 *
 * Badges en sintaxis Bootstrap 4 (badge badge-*); auth/empleado resueltos en backend.
 */

var idiomaDataTable = {
    "decimal": "",
    "emptyTable": "No hay información disponible",
    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
    "infoFiltered": "(Filtrado de _MAX_ registros totales)",
    "lengthMenu": "Mostrar _MENU_ registros",
    "loadingRecords": "Cargando...",
    "processing": "Procesando...",
    "search": "Buscar:",
    "zeroRecords": "No se encontraron resultados",
    "paginate": {
        "first": "Primero",
        "last": "Último",
        "next": "Siguiente",
        "previous": "Anterior"
    },
    "aria": {
        "sortAscending": ": activar para ordenar la columna de manera ascendente",
        "sortDescending": ": activar para ordenar la columna de manera descendente"
    }
};

$(document).ready(function () {

    $('#filtro-ingeniero').select2({
        placeholder: "Busca un ingeniero...",
        allowClear: true,
        width: '100%'
    });

    llenaSelectPersonal();

    $('#TvacacionesPersonal').DataTable({
        "language": idiomaDataTable,
        autoWidth: false,
        columnDefs: [
            {
                targets: 2,  // Fecha de solicitud
                render: function (data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        var date = new Date(data);
                        date.setMinutes(date.getMinutes() + date.getTimezoneOffset()); // corrige desfase UTC
                        return ('0' + date.getDate()).slice(-2) + '/' +
                               ('0' + (date.getMonth() + 1)).slice(-2) + '/' +
                               date.getFullYear();
                    }
                    return data;
                }
            }
        ]
    });

    $('#TSolAbiertas').DataTable({
        "language": idiomaDataTable,
        autoWidth: false
    });
});

function badgeTipo(tipo) {
    if (tipo == 1) { return '<span class="badge badge-success">Vacaciones</span>'; }
    if (tipo == 2) { return '<span class="badge badge-info text-white">Permiso sin goce</span>'; }
    if (tipo == 3) { return '<span class="badge badge-primary">Permiso con goce</span>'; }
    return '';
}

function badgeEstatus(registro) {
    var html = '';
    if (registro.Estatus == 1) { html += '<span class="badge badge-warning">Por autorizar</span>'; }
    if (registro.Estatus == 2) { html += '<span class="badge badge-success">Autorizada</span>'; }
    // Nota: el original comprueba registro.autoriza (no se devuelve), se conserva igual.
    if (registro.autoriza == 3) { html += '<span class="badge badge-danger">Rechazada/Cancelada</span>'; }

    if (registro.autorizaRH == 1) { html += '<span class="badge badge-warning">Por autorizar RH</span>'; }
    if (registro.autorizaRH == 2) { html += '<span class="badge badge-success">Autorizada RH</span>'; }
    if (registro.autorizaRH == 3) { html += '<span class="badge badge-danger">Rechazada/Cancelada RH</span>'; }
    return html;
}

function llenaTablaVacaciones() {
    var empleado = $('#filtro-ingeniero').val();

    $.ajax({
        url: 'acciones_verVacaciones.php',
        method: 'POST',
        dataType: 'json',
        data: { opcion: 'llenaTablaVacaciones', empleado: empleado },
        success: function (registros) {
            var table = $('#TvacacionesPersonal').DataTable();
            table.clear();

            var filas = [];
            registros.forEach(function (Registro) {
                filas.push([
                    '<b>' + Registro.usuario,
                    badgeTipo(Registro.tSolicitud),
                    Registro.FechaBien,
                    '<span class="badge badge-dark"><b>' + Registro.noDias + ' días</b></span>' +
                        '<span class="badge badge-light"><b>' + Registro.Dgozados + ' gozados</b></span>' +
                        '<span class="badge badge-warning"><b>' + Registro.diasDisp + ' Rest</b></span>' +
                        '<br> <b>' + Registro.Finicio + ' - ' + Registro.FFin + '</b>',
                    '<p style="margin-bottom: 0px; margin-top: 0px;"><small>Grales:' + Registro.Comentarios + '</small></p>' +
                        '<p style="margin-bottom: 0px; margin-top: 0px;"><small>Colab:' + Registro.ComentariosE + '</small></p>' +
                        '<p style="margin-bottom: 0px;"><small>Jefe:' + Registro.ComentariosJ + '</small></p>',
                    badgeEstatus(Registro)
                ]);
            });
            table.rows.add(filas).draw(false);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //console.error('Error al obtener vacaciones:', textStatus, errorThrown);
        }
    });

    llenaTablaServicios();
}

function llenaTablaServicios() {
    var empleado = $('#filtro-ingeniero').val();

    $.ajax({
        url: 'acciones_verVacaciones.php',
        method: 'POST',
        dataType: 'json',
        data: { opcion: 'llenaTablaServicios', empleado: empleado },
        success: function (registros) {
            var table = $('#TSolAbiertas').DataTable();
            table.clear();

            var filas = [];
            registros.forEach(function (Registro) {
                filas.push([
                    Registro.engineer + '<br>' + Registro.engineer2 + '<br>' + Registro.engineer3,
                    Registro.area,
                    Registro.ot,
                    Registro.start_date,
                    Registro.cliente,
                    Registro.ciudad,
                    Registro.estatus
                ]);
            });
            table.rows.add(filas).draw(false);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //console.error('Error al obtener los servicios:', textStatus, errorThrown);
        }
    });
}

function llenaSelectPersonal() {
    $.ajax({
        url: 'acciones_verVacaciones.php',
        method: 'POST',
        dataType: 'json',
        data: { opcion: 'llenaSelectPersonal' },
        success: function (registros) {
            var select = $('#filtro-ingeniero');
            select.empty();
            select.append('<option value="0">Selecciona un empleado</option>');
            registros.forEach(function (Registro) {
                select.append('<option value="' + Registro.noEmpleado + '">' + Registro.nombre + '</option>');
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //console.error('Error al obtener el personal:', textStatus, errorThrown);
        }
    });
}
