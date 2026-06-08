/**
 * Lógica de "Lista de Vacaciones" (listavacaciones_v2.php) - vista RH.
 *
 * Backend dedicado: acciones_listavacaciones.php
 *   - porAutorizar / autorizadas / canceladas / editarFeSolicitud
 *
 * Filtro por mes (#filtroMes, 'YYYY-MM'): por defecto el mes actual, para que
 * las listas se acoten y carguen rápido. "Ver todo" lo limpia (más lento).
 */

const BACKEND_LV = 'acciones_listavacaciones.php';

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

// Render d/m/Y para la columna de fecha (índice 2), corrigiendo desfase UTC.
const COLDEF_FECHA = [{
    targets: 2,
    render: function (data, type, row) {
        if (type === 'display' || type === 'filter') {
            var date = new Date(data);
            date.setMinutes(date.getMinutes() + date.getTimezoneOffset());
            return ('0' + date.getDate()).slice(-2) + '/' +
                   ('0' + (date.getMonth() + 1)).slice(-2) + '/' +
                   date.getFullYear();
        }
        return data;
    }
}];

/* --------------------------- Badges helpers ------------------------- */

function badgeTipo(t) {
    if (t == 1) return '<span class="badge text-bg-success">Vacaciones</span>';
    if (t == 2) return '<span class="badge text-bg-info text-white">Permiso sin goce</span>';
    if (t == 3) return '<span class="badge text-bg-primary">Permiso con goce</span>';
    return '';
}

function badgeEstatus(e) {
    if (e == 1) return '<span class="badge text-bg-warning">Por autorizar</span>';
    if (e == 2) return '<span class="badge text-bg-success">Autorizada</span>';
    if (e == 3) return '<span class="badge text-bg-danger">Rechazada</span>';
    return '';
}

function badgeEstatusRH(rh) {
    if (rh == 1) return '<span class="badge text-bg-warning">Por autorizar RH</span>';
    if (rh == 2) return '<span class="badge text-bg-success">Autorizada RH</span>';
    if (rh == 3) return '<span class="badge text-bg-danger">Rechazada/Cancelada RH</span>';
    return '';
}

/* ------------------------------ Init -------------------------------- */

// Mes seleccionado (formato 'YYYY-MM'); vacío = ver todo.
function mesSel() {
    return $('#filtroMes').val() || '';
}

$(document).ready(function () {
    llenaTablaPorAutorizar();

    $('#TporAutorizar').DataTable({
        "language": DT_LANG_ES,
        autoWidth: false,
        pageLength: 10,
        columnDefs: COLDEF_FECHA
    });
    $('#Tautorizadas').DataTable({
        "language": DT_LANG_ES,
        autoWidth: false,
        pageLength: 10,
        columnDefs: COLDEF_FECHA
    });
    $('#Tcanceladas').DataTable({
        "language": DT_LANG_ES,
        autoWidth: false,
        pageLength: 10,
        columnDefs: COLDEF_FECHA
    });
});

// El filtro de mes solo afecta Autorizadas y Canceladas (las listas pesadas).
// 'Por autorizar' siempre muestra todas las pendientes, no se recarga por mes.
function recargarListas() {
    llenaTablaAutorizadas();
    llenaTablaCanceladas();
}

// Quita el filtro de mes y recarga (muestra todo el histórico; más lento).
function verTodo() {
    $('#filtroMes').val('');
    recargarListas();
}

/* --------------------------- Carga de tablas ------------------------ */

function llenaTablaPorAutorizar() {
    $.ajax({
        url: BACKEND_LV,
        method: 'GET',
        dataType: 'json',
        data: { accion: 'porAutorizar' },
        success: function (registros) {
            var table = $('#TporAutorizar').DataTable();
            table.clear();

            var filas = [];
            registros.forEach(function (Registro) {
                filas.push([
                    '<b>' + Registro.usuario,
                    badgeTipo(Registro.tSolicitud) + '<span class="badge text-bg-dark"><b>' + Registro.diasDisp + ' días Disp.</b></span>',
                    Registro.fSolicitud,
                    '<span class="badge text-bg-dark"><b>' + Registro.noDias + ' días</b></span><br> <b>' + Registro.Finicio + ' - ' + Registro.FFin + '</b>',
                    'Grales:' + Registro.Comentarios + '<br>Colab:' + Registro.ComentariosJ + '<br>Jefe:' + Registro.ComentariosJ,
                    badgeEstatus(Registro.Estatus) + badgeEstatusRH(Registro.autorizaRH),
                    '<a class="btn btn-outline-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#modalAutoriza" onClick="llenaInfo(2,' + Registro.id + ', ' + Registro.Estatus + ',\'estatusRH\')">' +
                        '<i class="fas fa-check"></i>' +
                    '</a>' +
                    '<a class="btn btn-outline-danger btn-circle btn-sm" href="#" data-toggle="modal" data-target="#modalAutoriza" onClick="llenaInfo(3,' + Registro.id + ', ' + Registro.Estatus + ',\'estatusRH\')">' +
                        '<i class="fas fa-times"></i>' +
                    '</a>',
                    '<a class="btn btn-outline-success btn-circle btn-sm" href="#" data-toggle="modal" data-target="#modalAutoriza" onClick="llenaInfo(2,' + Registro.id + ', ' + Registro.Estatus + ',\'estatusJ\')">' +
                        '<i class="fas fa-check"></i>' +
                    '</a>'
                ]);
            });
            table.rows.add(filas).draw(false);
        }
    });
}

function llenaTablaAutorizadas() {
    $.ajax({
        url: BACKEND_LV,
        method: 'GET',
        dataType: 'json',
        data: { accion: 'autorizadas', mes: mesSel() },
        success: function (registros) {
            var table = $('#Tautorizadas').DataTable();
            table.clear();

            var filas = [];
            registros.forEach(function (Registro) {
                filas.push([
                    '<b>' + Registro.usuario,
                    badgeTipo(Registro.tSolicitud),
                    Registro.FechaBien,
                    '<span class="badge text-bg-dark"><b>' + Registro.noDias + ' días</b></span><span class="badge text-bg-light"><b>' + Registro.Dgozados + ' gozados</b></span><span class="badge text-bg-warning"><b>' + Registro.diasDisp + ' Rest</b></span><br> <b>' + Registro.Finicio + ' - ' + Registro.FFin + '</b>',
                    '<p style="margin-bottom: 0px; margin-top: 0px;"><small>Grales:' + Registro.Comentarios + '</small></p><p style="margin-bottom: 0px; margin-top: 0px;"><small>Colab:' + Registro.ComentariosE + '</small></p><p style="margin-bottom: 0px;"><small>Jefe:' + Registro.ComentariosJ + '</small></p>',
                    badgeEstatus(Registro.Estatus) + badgeEstatusRH(Registro.autorizaRH),
                    '<a class="btn btn-outline-primary btn-circle btn-sm" href="#" data-toggle="modal" data-target="#modalEdita" onClick="llenaInfoEditar(' + Registro.id + ',\'' + Registro.fSolicitud + '\', ' + Registro.noEmpleado + ',\'' + Registro.usuario + '\', ' + Registro.Dgozados + ')">' +
                        '<i class="fas fa-edit"></i>' +
                    '</a>'
                ]);
            });
            table.rows.add(filas).draw(false);
        }
    });
}

function llenaTablaCanceladas() {
    $.ajax({
        url: BACKEND_LV,
        method: 'GET',
        dataType: 'json',
        data: { accion: 'canceladas', mes: mesSel() },
        success: function (registros) {
            var table = $('#Tcanceladas').DataTable();
            table.clear();

            var filas = [];
            registros.forEach(function (Registro) {
                filas.push([
                    '<b>' + Registro.usuario,
                    badgeTipo(Registro.tSolicitud),
                    Registro.fSolicitud,
                    '<span class="badge text-bg-dark"><b>' + Registro.noDias + ' días</b></span><br> ' + Registro.Finicio + ' - ' + Registro.FFin,
                    'Grales:' + Registro.Comentarios + '<br>Colab:' + Registro.ComentariosJ + '<br>Jefe:' + Registro.ComentariosJ,
                    badgeEstatus(Registro.Estatus) + badgeEstatusRH(Registro.autorizaRH)
                ]);
            });
            table.rows.add(filas).draw(false);
        }
    });
}

/* ----------------------------- Modales ------------------------------ */

function llenaInfo(estatusRH, id, estatus, accion) {
    $('#idSolicitud').val(id);
    $('#estatusSol').val(estatus);
    $('#estatusRH').val(estatusRH);
    $('#accion').val(accion);
}

function llenaInfoEditar(id, fsolicitud, noEmpleado, usuario, Dgozados) {
    $('#idSolicitudEdit').val(id);
    $('#noEmpleadoEdit').val(noEmpleado);
    $('#fechaSolicitudEdit').val(fsolicitud);
    $('#NombreEmpleado').text(usuario);
    $('#Dgozados').val(Dgozados);
}

// Cierra un modal de forma robusta pese al doble Bootstrap (BS4+BS5 de encabezado)
// y al bloqueo "aria-hidden" del navegador: quita el foco, intenta el plugin y,
// como respaldo, lo oculta manualmente y limpia el backdrop.
function cerrarModal(sel) {
    if (document.activeElement && typeof document.activeElement.blur === 'function') {
        document.activeElement.blur();
    }
    var $m = $(sel);
    try { if (typeof $m.modal === 'function') { $m.modal('hide'); } } catch (e) {}
    $m.removeClass('show in').attr('aria-hidden', 'true').removeAttr('aria-modal').css('display', 'none');
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open').css({ 'padding-right': '', 'overflow': '' });
}

function guardarCambiosFeSolicitud() {
    const id = $('#idSolicitudEdit').val();
    const noEmpleado = $('#noEmpleadoEdit').val();
    const fsolicitud = $('#fechaSolicitudEdit').val();
    const Dgozados = $('#Dgozados').val();

    $.ajax({
        url: BACKEND_LV,
        method: 'POST',
        dataType: 'json',
        data: { accion: 'editarFeSolicitud', id, fsolicitud, noEmpleado, Dgozados },
        success: function () {
            cerrarModal('#modalEdita');       // cierra el modal de edición
            Swal.fire({
                title: "Confirmado!",
                text: "Se proceso la solicitud con éxito!",
                icon: "success"
            });
            llenaTablaAutorizadas();          // recarga la tabla donde se editó
        }
    });
}
