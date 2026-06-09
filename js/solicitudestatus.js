/**
 * Lógica compartida de solicitudes / calendario.
 *
 * Backend: acciones_solicitudestatus.php
 *   - accion 'listar'          -> "Mis solicitudes" (solicitudestatus_v2.php)
 *   - accion 'calendarioJefes' -> calendario de vacaciones de jefes
 *                                 (calendarioVacacionesJefes_v2.php)
 *
 * Cada bloque se ejecuta solo si su elemento existe en la página, así el mismo
 * archivo sirve para ambas vistas sin chocar (una usa DataTables, otra FullCalendar).
 */

const BACKEND_ESTATUS = 'acciones_solicitudestatus.php';

// Idioma común para las tres DataTables
const DT_LANG_ES = {
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
    // Vista "Mis solicitudes": solo si existen las tablas (y DataTables cargado).
    if ($('#TporAutorizar').length) {
        cargarSolicitudes();
    }
    // Vista calendario de jefes: solo si existe el contenedor (y FullCalendar cargado).
    if (document.getElementById('calendar')) {
        initCalendarioJefes();
    }
});

/* ----------------------------- Helpers ----------------------------- */

function escapeHtml(value) {
    return $('<div>').text(value == null ? '' : value).html();
}

function badgeTipo(tipo) {
    if (tipo == 1) return '<span class="badge text-bg-success">Vacaciones</span>';
    if (tipo == 2) return '<span class="badge text-bg-info text-white">Permiso sin goce</span>';
    if (tipo == 3) return '<span class="badge text-bg-primary">Permiso con goce</span>';
    return '';
}

function badgeEstatus(estatus) {
    if (estatus == 1) return '<span class="badge text-bg-warning">Por autorizar</span>';
    if (estatus == 2) return '<span class="badge text-bg-success">Autorizada</span>';
    if (estatus == 3) return '<span class="badge text-bg-danger">Rechazada</span>';
    return '';
}

function badgeEstatusRH(rh) {
    if (rh == 1) return '<span class="badge text-bg-warning">Por autorizar RH</span>';
    if (rh == 2) return '<span class="badge text-bg-success">Autorizada RH</span>';
    if (rh == 3) return '<span class="badge text-bg-danger">Rechazada RH</span>';
    return '';
}

function badgeEstatusCancelada(estatus) {
    if (estatus == 1) return '<span class="badge text-bg-warning">Por autorizar</span>';
    if (estatus == 2) return '<span class="badge text-bg-success">Autorizada</span>';
    if (estatus == 3) return '<span class="badge text-bg-danger">Cancelada/Rechazada</span>';
    return '';
}

/* ------------------------- Carga de datos -------------------------- */

function cargarSolicitudes() {
    $.ajax({
        url: BACKEND_ESTATUS,
        method: 'POST',
        dataType: 'json',
        data: { accion: 'listar' },
        success: function (data) {
            if (!data || data.success !== true) {
                inicializarTablas();
                return;
            }
            renderPorAutorizar(data.porAutorizar || []);
            renderAutorizadas(data.autorizadas || []);
            renderCanceladas(data.canceladas || []);
            inicializarTablas();
        },
        error: function () {
            inicializarTablas();
        }
    });
}

function renderPorAutorizar(filas) {
    let html = '';
    filas.forEach(function (r) {
        html += '<tr>' +
            '<td>' + badgeTipo(r.tipo) + '<br>' + (r.fesolicitud) + '</td>' +
            '<td><span class="badge text-bg-dark"> ' + (r.dias) + ' días</span><br>' +
                (r.feinicio) + ' - ' + (r.fefin) + '</td>' +
            '<td>' + escapeHtml(r.notasempleado) + '</td>' +
            '<td>' + badgeEstatus(r.estatus) + badgeEstatusRH(r.autorizaRH) + '</td>' +
            '<td>' +
                '<a class="btn btn-outline-danger btn-circle btn-sm" href="#" data-toggle="modal" ' +
                'data-target="#modalAutoriza" onClick="llenaInfo(3,' + parseInt(r.id, 10) + ')">' +
                '<i class="fas fa-times"></i></a>' +
            '</td>' +
            '</tr>';
    });
    $('#TporAutorizar tbody').html(html);
}

function renderAutorizadas(filas) {
    let html = '';
    filas.forEach(function (r) {
        let tipoCol = badgeTipo(r.tipo);
        if (r.origen && r.origen !== '') {
            tipoCol += '<br><span class="badge text-bg-secondary">' + escapeHtml(r.origen) + '</span>';
        }
        html += '<tr>' +
            '<td>' + tipoCol + '</td>' +
            '<td>' + (r.fesolicitud) + '</td>' +
            '<td>' + (r.dias) + '</td>' +
            '<td>' + (r.feinicio) + ' - ' + (r.fefin) + '</td>' +
            '<td>' + escapeHtml(r.notasempleado) + '</td>' +
            '<td>' + escapeHtml(r.notajefe) + '</td>' +
            '<td>' + badgeEstatus(r.estatus) + '<br>' + badgeEstatusRH(r.autorizaRH) + '</td>' +
            '</tr>';
    });
    $('#Tautorizadas tbody').html(html);
}

function renderCanceladas(filas) {
    let html = '';
    filas.forEach(function (r) {
        html += '<tr>' +
            '<td>' + badgeTipo(r.tipo) + '</td>' +
            '<td>' + (r.fesolicitud) + '</td>' +
            '<td>' + (r.dias) + '</td>' +
            '<td>' + (r.feinicio) + ' - ' + (r.fefin) + '</td>' +
            '<td>' + escapeHtml(r.notasempleado) + '</td>' +
            '<td>' + escapeHtml(r.notajefe) + '</td>' +
            '<td><span class="badge text-bg-danger">Cancelada</span></td>' +
            '</tr>';
    });
    $('#Tcanceladas tbody').html(html);
}

function inicializarTablas() {
    $('#TporAutorizar').DataTable({ "language": DT_LANG_ES, "autoWidth": false });
    $('#Tautorizadas').DataTable({ "language": DT_LANG_ES, "autoWidth": false });
    $('#Tcanceladas').DataTable({ "language": DT_LANG_ES, "autoWidth": false });
}

/* --------------------- Acción del modal (cancelar) ----------------- */

function llenaInfo(estatus, id) {
    $('#idSolicitud').val(id);
    $('#estatusSol').val(estatus);
}

/* --------------------- Calendario de vacaciones (jefes) ------------ */

function initCalendarioJefes() {
    var calendarEl = document.getElementById('calendar');
    if (!calendarEl || typeof FullCalendar === 'undefined') return;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: 'acciones_solicitudestatus.php?accion=calendarioJefes',
        editable: false,
        eventDidMount: function (info) {
            // Color aleatorio por evento
            var randomColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
            info.el.style.backgroundColor = randomColor;
        },
        eventContent: function (info) {
            var nombre = info.event.title;
            var inicio = info.event.start.toISOString().split('T')[0];
            var finReal = "";

            if (info.event.end) {
                var d = new Date(info.event.end);
                d.setDate(d.getDate() - 1);
                finReal = d.toISOString().split('T')[0];
            }

            // Si inicio y fin coinciden, mostramos solo una fecha
            var textoFechas = (inicio === finReal)
                ? 'Día: ' + inicio
                : 'Inicio: ' + inicio + '    Fin: ' + finReal;

            return { html: '<b>' + nombre + '</b><br>' + textoFechas };
        }
    });

    calendar.render();
}
