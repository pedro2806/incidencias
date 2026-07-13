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
                "targets": [0], // Oculta la columna del ID (la primera posición)
                "visible": false,
                "searchable": false // Evita que el ID afecte las búsquedas globales si no lo deseas
            },
            {
                "targets": [7], // La columna de acciones no debe ordenarse al dar clic
                "orderable": false
            }
        ],
        "drawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip({
                container: 'body'
            });
        }
    });

    $('#TSolAbiertas').DataTable({
        "language": idiomaDataTable,
        autoWidth: false
    });
});

function badgeTipo(tipo) {
    // Colores planos y sutiles para los tipos de solicitud
    if (tipo == 1) { return '<span class="badge badge-light text-dark border">Vacaciones</span>'; }
    if (tipo == 2) { return '<span class="badge badge-light text-muted border">Permiso sin goce</span>'; }
    if (tipo == 3) { return '<span class="badge badge-light text-primary border">Permiso con goce</span>'; }
    return '';
}

function badgeEstatus(registro) {
    var html = '<div class="d-flex flex-column gap-1">'; // Contenedor vertical ordenado
    
    if (registro.Estatus == 1) { html += '<span class="badge badge-warning-soft text-warning mb-1">Por autorizar</span>'; }
    if (registro.Estatus == 2) { html += '<span class="badge badge-success">Autorizada</span>'; }
    if (registro.Estatus == 3) { html += '<span class="badge badge-danger">Rechazada</span>'; }

    if (registro.autorizaRH == 1) { html += '<span class="badge badge-light text-warning border">Pendiente RH</span>'; }
    if (registro.autorizaRH == 2) { html += '<span class="badge badge-light text-success border">Autorizada RH</span>'; }
    if (registro.autorizaRH == 3) { html += '<span class="badge badge-light text-danger border">Rechazada RH</span>'; }
    
    html += '</div>';
    return html;
}

function llenaTablaVacaciones() {
    var empleado = $('#filtro-ingeniero').val();

    var acciones = '<div class="d-flex justify-content-center gap-1">' +
        '<button class="btn btn-sm btn-outline-primary" title="Permutar" onclick="permutarSolicitud(this)"><i class="fas fa-check"></i></button>' +        
        '</div>';

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
                // Estatus de pago discreto y profesional                
                let badgePagoProvisional = '<span class="badge badge-light text-primary border font-weight-normal">Pendiente Pago</span>';

                if (Registro.pago === 'Si') { 
                    badgePagoProvisional = '<span class="badge badge-light text-success border font-weight-normal">Pagado</span>'; 
                }             
                
                // Construcción limpia del tooltip
                const textoTooltip = 'Grales: ' + (Registro.Comentarios || 'Ninguno') + '\n' +
                                     'Colab: ' + (Registro.ComentariosE || 'Ninguno') + '\n' +
                                     'Jefe: ' + (Registro.ComentariosJ || 'Ninguno');

                // Icono minimalista en gris secundario
                const columnaComentariosHtml = '<div class="text-center">' +
                        '<i class="far fa-comment-alt text-secondary" ' +
                        'style="cursor: pointer; font-size: 1rem;" ' +
                        'data-toggle="tooltip" ' +
                        'data-placement="top" ' +
                        'title="' + textoTooltip + '">' +
                        '</i>' +
                    '</div>';
                
                filas.push([
                    // Columna 0: ID de solicitud (oculto, para referencia interna)
                    Registro.id,
                    // Columna 1: Empleado (Texto principal firme pero limpio)
                    '<span class="text-dark font-weight-bold">' + Registro.usuario + '</span>',                                 
                    
                    // Columna 2: Fecha y Tipo agrupados sutilmente
                    '<span class="text-dark font-weight-normal">' + Registro.FechaBien + '</span><br>' + 
                    '<div class="mt-1">' + badgeTipo(Registro.tSolicitud) + '</div>',
                    
                    // Columna 3: No. Días / Periodo (Jerarquía visual sin exceso de fondos de color)
                    '<span class="text-dark font-weight-bold" style="font-size: 0.9rem;">' + Registro.Finicio + ' - ' + Registro.FFin + '</span><br>' +
                    '<small class="text-muted">' +
                        '<b>' + Registro.noDias + '</b> Solicitado(s) · ' +
                        '<b>' + Registro.Dgozados + '</b> Gozados · ' +
                        '<span class="text-primary"><b>' + Registro.diasDisp + '</b> Restan</span>' +
                    '</small>',
                        
                    // Columna 4: Comentarios (Icono)
                    columnaComentariosHtml,
                        
                    // Columna 5: Estatus de Solicitud
                    badgeEstatus(Registro),

                    // Columna 6: Estatus de Pago
                    badgePagoProvisional,
                    // Columna 7:
                    acciones    
                ]);
            });
            table.rows.add(filas).draw(false);
        },
        error: function (jqXHR, textStatus, errorThrown) {}
    });

    llenaTablaServicios();
}

//Permutar solicitud - Se cambian fechas de vacaciones a otro periodo, se mantiene el mismo estatus de solicitud y estatus de pago. 
// Aqui se procede abrir un swal para que el usuario ingrese las nuevas fechas de vacaciones y se haga la actualización en la base de datos.
function permutarSolicitud(button) {
    var row = $(button).closest('tr');
    var table = $('#TvacacionesPersonal').DataTable();
    var data = table.row(row).data();

    //fechas anteriores de la solicitud
    const fechaInicioAnterior = data[3].split(' - ')[0];
    const fechaFinAnterior = data[3].split(' - ')[1];

    swal.fire({
        title: 'Permutar solicitud',
        html: '<p>Ingresa las nuevas fechas de vacaciones:</p>' +
            '<p>Fechas actuales: <b>' + fechaInicioAnterior + ' - ' + fechaFinAnterior + '</b></p>' +
            '<input type="date" id="nuevaFechaInicio" class="swal2-input" placeholder="Nueva fecha de inicio (YYYY-MM-DD)">' +
            '<input type="date" id="nuevaFechaFin" class="swal2-input" placeholder="Nueva fecha de fin (YYYY-MM-DD)">',
        showCancelButton: true,
        confirmButtonText: 'Permutar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const nuevaFechaInicio = document.getElementById('nuevaFechaInicio').value;
            const nuevaFechaFin = document.getElementById('nuevaFechaFin').value;

            if (!nuevaFechaInicio || !nuevaFechaFin) {
                swal.showValidationMessage('Por favor ingresa ambas fechas.');
                return false;
            }   

            return { nuevaFechaInicio, nuevaFechaFin };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { nuevaFechaInicio, nuevaFechaFin } = result.value;

            $.ajax({
                url: 'acciones_verVacaciones.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    opcion: 'permutarSolicitud',
                    idSolicitud: data[0], // Asumiendo que el ID de la solicitud está en la primera columna
                    nuevaFechaInicio: nuevaFechaInicio,
                    nuevaFechaFin: nuevaFechaFin
                },
                success: function (response) {
                    if (response.success) {
                        swal.fire('Éxito', 'La solicitud ha sido permutada correctamente.', 'success');
                        llenaTablaVacaciones(); // Refresca la tabla después de permutar
                    } else {
                        swal.fire('Error', 'Hubo un problema al permutar la solicitud.', 'error');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    swal.fire('Error', 'Hubo un problema al permutar la solicitud.', 'error');
                }
            });
        }
    });
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
