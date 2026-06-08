/**
 * Lógica de la página "Solicitudes para revisar" (solicitudempleado_v2.php).
 * Vista del jefe: solicitudes de sus empleados a cargo.
 *
 * Backend dedicado: acciones_solicitudempleado.php (accion 'listar')
 */

const BACKEND_SOLEMP = 'acciones_solicitudempleado.php';

// Traducción al español para DataTables
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
    cargarSolicitudes();
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

function celdaDias(dias, feinicio, fefin) {
    return '<span class="badge text-bg-dark"><b>' + escapeHtml(dias) + ' días</b></span><br> ' +
        escapeHtml(feinicio) + ' - ' + escapeHtml(fefin);
}

/* ------------------------- Carga de datos -------------------------- */

function cargarSolicitudes() {
    $.ajax({
        url: BACKEND_SOLEMP,
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
        const id = parseInt(r.id, 10);
        const dias = parseInt(r.dias, 10);
        const empleado = parseInt(r.empleado, 10);
        const rh = parseInt(r.autorizaRH, 10);

        let tipoCol = badgeTipo(r.tipo) +
            '<span class="badge text-bg-primary"><b>' + escapeHtml(r.diasDisp) + ' días Disp.</b></span>';

        html += '<tr>' +
            '<td><b>' + escapeHtml(r.empleadoNom) + '</b></td>' +
            '<td>' + tipoCol + '</td>' +
            '<td><b>' + escapeHtml(r.fesolicitud) + '</b></td>' +
            '<td>' + celdaDias(r.dias, r.feinicio, r.fefin) + '</td>' +
            '<td>' + escapeHtml(r.notasempleado) + '</td>' +
            '<td>' + badgeEstatus(r.estatus) + badgeEstatusRH(r.autorizaRH) + '</td>' +
            '<td>' +
                '<a class="btn btn-outline-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#modalAutoriza" ' +
                'onClick="llenaInfo(2, ' + id + ', ' + dias + ', ' + empleado + ', ' + rh + ')">' +
                '<i class="fas fa-check"></i></a> ' +
                '<a class="btn btn-outline-danger btn-circle btn-sm" href="#" data-toggle="modal" data-target="#modalAutoriza" ' +
                'onClick="llenaInfo(3, ' + id + ', ' + dias + ', ' + empleado + ', ' + rh + ')">' +
                '<i class="fas fa-times"></i></a>' +
            '</td>' +
            '</tr>';
    });
    $('#TporAutorizar tbody').html(html);
}

function renderAutorizadas(filas) {
    let html = '';
    filas.forEach(function (r) {
        html += '<tr>' +
            '<td><b>' + escapeHtml(r.empleado) + '</b></td>' +
            '<td>' + badgeTipo(r.tipo) + '</td>' +
            '<td><b>' + escapeHtml(r.fesolicitud) + '</b></td>' +
            '<td>' + celdaDias(r.dias, r.feinicio, r.fefin) + '</td>' +
            '<td>' + escapeHtml(r.notajefe) + '</td>' +
            '<td>' + badgeEstatus(r.estatus) + badgeEstatusRH(r.autorizaRH) + '</td>' +
            '</tr>';
    });
    $('#Tautorizadas tbody').html(html);
}

function renderCanceladas(filas) {
    let html = '';
    filas.forEach(function (r) {
        html += '<tr>' +
            '<td><b>' + escapeHtml(r.empleado) + '</b></td>' +
            '<td>' + badgeTipo(r.tipo) + '</td>' +
            '<td><b>' + escapeHtml(r.fesolicitud) + '</b></td>' +
            '<td>' + celdaDias(r.dias, r.feinicio, r.fefin) + '</td>' +
            '<td>' + escapeHtml(r.notasempleado) + '</td>' +
            '<td>' + escapeHtml(r.notajefe) + '</td>' +
            '<td>' + badgeEstatus(r.estatus) + '</td>' +
            '</tr>';
    });
    $('#Tcanceladas tbody').html(html);
}

function inicializarTablas() {
    $('#TporAutorizar').DataTable({ "language": DT_LANG_ES });
    $('#Tautorizadas').DataTable({ "language": DT_LANG_ES });
    $('#Tcanceladas').DataTable({ "language": DT_LANG_ES });
}

/* --------------------- Acción del modal (autorizar/rechazar) -------- */

function llenaInfo(estatus, id, dias, empleado, rh) {
    $('#idSolicitud').val(id);
    $('#estatusSol').val(estatus);
    $('#ndias').val(dias);
    $('#nempleado').val(empleado);
    $('#estatusRH').val(rh);
}
