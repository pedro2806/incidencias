/**
 * Front de "Control pagos nómina" (mandarNomina_v2.php) - vista RH.
 *
 * Las 3 tablas usan paginación SERVER-SIDE (DataTables) contra
 * acciones_mandarNomina.php (opcion=listaServerSide, lista=porPagar|enNomina|pagadas):
 * el backend devuelve solo la página visible + búsqueda + orden.
 *
 * Acciones de proceso (GET, opcion):
 *   - mandarNomina  : pasa solicitudes autorizadas a "envioNomina"
 *   - mandarPagado  : marca solicitudes "envioNomina" como pagadas
 *
 * El backend resuelve noEmpleado/rol del lado servidor (cookie + accesos_especiales),
 * NO se envían desde el cliente.
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

/**
 * Inicializa una tabla server-side. `extra` permite sobreescribir/añadir opciones
 * (ej. botones de export y pageLength para enNomina).
 */
function initTablaServerSide(tablaId, lista, extra) {
    var config = {
        language: idiomaDataTable,
        processing: true,
        serverSide: true,
        autoWidth: false,   // ajusta columnas al contenedor (sin desalinear encabezado)
        order: [[0, 'desc']],
        ajax: {
            url: 'acciones_mandarNomina.php',
            type: 'GET',
            data: function (d) {
                d.opcion = 'listaServerSide';
                d.lista = lista;
            }
        }
    };
    if (extra) { Object.assign(config, extra); }
    return $('#' + tablaId).DataTable(config);
}

var tablaPorPagar, tablaEnNomina, tablaPagadas;

$(document).ready(function () {

    tablaPorPagar = initTablaServerSide('TporPagar', 'porPagar');

    // enNomina carga todas sus filas (pageLength -1) para que la exportación a
    // Excel incluya el listado completo y no solo la página visible.
    tablaEnNomina = initTablaServerSide('TenNomina', 'enNomina', {
        pageLength: -1,  // por defecto "Todos" para que el export a Excel incluya todo el listado
        lengthMenu: [[-1, 10, 25, 50], ['Todos', 10, 25, 50]]  // incluye el selector "Mostrar N registros"
    });

    // Botón de exportación (oculto): se construye explícitamente para poder
    // dispararlo desde el botón HTML naranja con exportarExcelNomina().
    new $.fn.dataTable.Buttons(tablaEnNomina, {
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Exportar a Excel',
                filename: function () {
                    var date = new Date();
                    var year = date.getFullYear();
                    var month = ('0' + (date.getMonth() + 1)).slice(-2);
                    var day = ('0' + date.getDate()).slice(-2);
                    var hours = ('0' + date.getHours()).slice(-2);
                    var minutes = ('0' + date.getMinutes()).slice(-2);
                    var seconds = ('0' + date.getSeconds()).slice(-2);
                    // Solicitudes por pagar -YYYY-MM-DD-HH-MM-SS
                    return 'Solicitudes por pagar -' + year + '-' + month + '-' + day + '-' + hours + '-' + minutes + '-' + seconds;
                },
                title: 'Solicitudes por pagar'
            }
        ]
    });

    tablaPagadas = initTablaServerSide('Tpagadas', 'pagadas');
});

// Dispara la exportación a Excel del botón (oculto) de DataTables desde el
// botón HTML naranja visible, junto a "Marcar como pagado".
function exportarExcelNomina() {
    if (tablaEnNomina) {
        tablaEnNomina.button(0).trigger();
    }
}

function recargarTablas() {
    if (tablaPorPagar) { tablaPorPagar.ajax.reload(null, false); }
    if (tablaEnNomina) { tablaEnNomina.ajax.reload(null, false); }
    if (tablaPagadas)  { tablaPagadas.ajax.reload(null, false); }
}

function mandarNomina() {
    procesar("mandarNomina", "Estás seguro de generar el reporte?", "Se cambiaran a por pagar las solicitudes");
}

function mandarPagado() {
    procesar("mandarPagado", "Estás seguro de marcar como pagados?", "Se pondrán como pagadas las solicitudes");
}

function procesar(opcion, title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-primary btn-sm mx-1',
            cancelButton: 'btn btn-danger btn-sm mx-1'
        },
        cancelButtonText: '<i class="fas fa-times mr-1"></i>Cancelar',
        confirmButtonText: '<i class="fas fa-check mr-1"></i>Procesar solicitudes'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'acciones_mandarNomina.php',
                method: 'GET',
                dataType: 'json',
                data: { opcion },
                success: function (Registros) {
                    Swal.fire({
                        title: "Confirmado!",
                        text: "Se procesaron las solicitudes con éxito!",
                        icon: "success"
                    }).then(function () {
                        recargarTablas();
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //swal.fire('Error al aplicar el cambio.', error);
                }
            });
        }
    });
}
