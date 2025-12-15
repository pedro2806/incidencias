//FUCNION PARA ENVIAR NOTIFICACION AL RESPONSABLE
/*function enviaNotificacion(responsable, tipo) {
    $.ajax({
        url: 'enviaNotificacion.php',
        method: 'POST',
        dataType: 'json',
        data: {
            responsable,tipo
        },
        success: function(data) {
            // Notificación opcional
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Error opcional
        }
    });
}*/

//FUNCION PARA RESPONDER LA SOLICITUD
function GuardaRespuesta() {
    const idIncidencia = $('#idIncidencia').val();
    const comentariosRespuesta = $('#comentariosIncidencia').val();
    const respuestaIncidencia = $('#respuestaIncidencia').val();
    const tipoSolicitud = $('#tipoSolicitud').val();

    $.ajax({
        url: 'acciones_solicitud.php',
        method: 'POST',
        dataType: 'json',
        data: {
            opcion: 'responderSolicitud',
            idIncidencia,
            comentariosRespuesta,
            respuestaIncidencia,
            tipoSolicitud
        },
        success: function(data) {
            $('#responderIncidenciaModal').modal('hide');
            Swal.fire({
                title: "Respuesta enviada correctamente!",
                icon: "success",
                draggable: true
            }).then(() => {
                // Recargar la tabla de solicitudes abiertas
                SolicitudesAbiertas();
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $('#responderIncidenciaModal').modal('hide');
            Swal.fire({
                title: "La respuesta no se pudo enviar!",
                icon: "error",
                draggable: true
            });
        }
    });
}

//FUNCION PARA MOSTRAR LAS SOLICITUDES ABIERTAS
function SolicitudesAbiertas() {
    
    manejarVisibilidadDeTablas("#TSolAbiertas_wrapper");
    obtenerYRenderizarSolicitudes("solicitudesAbiertas", "#TSolAbiertas tbody");

}

//FUNCION PARA MOSTRAR LAS SOLICITUDES ACEPTADAS
function SolicitudesAceptadas() {

    manejarVisibilidadDeTablas("#TSolAceptadas_wrapper");
    obtenerYRenderizarSolicitudes("SolicitudesAceptadas", "#TSolAceptadas tbody");
}

//FUNCION PARA MOSTRAR LAS SOLICITUDES EN PROCESO
function SolicitudesEnProceso() {

    manejarVisibilidadDeTablas("#TSolEnProceso_wrapper");
    obtenerYRenderizarSolicitudes("SolicitudesEnProceso", "#TSolEnProceso tbody");
}

//FUNCION PARA MOSTRAR LAS SOLICITUDES CERRADAS
function SolicitudesCerradas() {

    manejarVisibilidadDeTablas("#TSolCerradas_wrapper");
    obtenerYRenderizarSolicitudes("SolicitudesCerradas", "#TSolCerradas tbody");
}

//FUNCION PARA MOSTRAR LAS SOLICITUDES RECHAZADAS
function SolicitudesRechazadas() {
    manejarVisibilidadDeTablas("#TSolRechazadas_wrapper");
    obtenerYRenderizarSolicitudes("SolicitudesRechazadas", "#TSolRechazadas tbody");
}

//FUNCIONES PARA MOSTRAR LAS SOLICITUDES SEGUN EL TIPO DE USUARIO
function SolicitudesAbiertasPorTipoUsuario() {
    const tipoUsuario = getCookie('tipoUsuario');
    if (tipoUsuario === 'Empleado') {
        SolicitudesAbiertas();
    } else if (tipoUsuario === 'Jefe') {
        SolicitudesAbiertas();
    } else if (tipoUsuario === 'Responsable') {
        SolicitudesAbiertas();
    }   
}

// FUNCIÓN PARA MANEJAR LA VISIBILIDAD DE LAS TABLAS
function manejarVisibilidadDeTablas(tablaAMostrar) {
    // Oculta todas las tablas
    $("#TSolAbiertas_wrapper, #TSolAceptadas_wrapper, #TSolEnProceso_wrapper, #TSolCerradas_wrapper, #TSolRechazadas_wrapper").hide();
    
    // Muestra solo la tabla deseada
    $(tablaAMostrar).show();
}

// FUNCIÓN PARA OBTENER Y RENDERIZAR LAS SOLICITUDES
function obtenerYRenderizarSolicitudes(opcion, tablaSeleccionada) {
    $.ajax({
        url: 'acciones_solicitud.php',
        method: 'POST',
        dataType: 'json',
        data: {opcion},
        success: function(data) {
            // Lógica de Renderizado: Procesa los datos y los inserta en la tabla
            renderizarTabla(tablaSeleccionada, data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Lógica de Manejo de Errores
            mostrarMensajeDeError();
        }
    });
}

// FUNCIÓN PARA RENDERIZAR LA TABLA
function renderizarTabla(selectorTabla, data) {
    const tabla = $(selectorTabla);
    tabla.empty(); // Limpia el contenido actual de la tabla

    // Mapa de acciones para evitar if/else anidados

    // Mapa de acciones según el tipo de tabla
    const accionesPorTipo = (() => {
        if (selectorTabla === "#TSolAbiertas tbody") {
            return {
                'Yosolicito': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
                'SoyResponsable': { estilo: 'secondary', boton: '<button class="btn btn-outline-primary btn-sm" onclick="abrirModalResponder(this.dataset.id,\'abierta\')" data-id="idSol"><i class="fas fa-reply"></i> Responder</button>' },
                'ResponsableMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
                'SolicitaMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
            };
        } else if (selectorTabla === "#TSolAceptadas tbody") {
            return {
                'Yosolicito': { estilo: 'secondary', boton: '<button class="btn btn-outline-primary btn-sm" onclick="abrirModalResponder(this.dataset.id,\'aceptada\')" data-id="idSol"><i class="fas fa-reply"></i> Responder</button>' },
                'SoyResponsable': { estilo: 'light', boton: '<span class="badge text-bg-success">Solicitud aceptada</span>' },
                'ResponsableMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
                'SolicitaMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
            };
        } else if (selectorTabla === "#TSolEnProceso tbody") {
            return {
                'Yosolicito': { estilo: 'secondary', boton: '<button class="btn btn-outline-primary btn-sm" onclick="abrirModalResponder(this.dataset.id,\'aceptada\')" data-id="idSol"><i class="fas fa-reply"></i> Responder</button>' },
                'SoyResponsable': { estilo: 'light', boton: '<button class="btn btn-outline-primary btn-sm" onclick="abrirModalResponder(this.dataset.id,\'aceptada\')" data-id="idSol"><i class="fas fa-reply"></i> Responder</button>' },
                'ResponsableMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
                'SolicitaMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
            };
        } else if (selectorTabla === "#TSolCerradas tbody") {
            return {
                'Yosolicito': { estilo: 'secondary', boton: '<span class="badge text-bg-info">Cerrada</span>' },
                'SoyResponsable': { estilo: 'secondary', boton: '<span class="badge text-bg-info">Cerrada</span>' },
                'ResponsableMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
                'SolicitaMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
            };
        } else if (selectorTabla === "#TSolRechazadas tbody") {
            return {
                'SoyResponsable': { estilo: 'danger', boton: '<span class="badge text-bg-danger">Rechazada</span>' },
                'Yosolicito': { estilo: 'danger', boton: '<button class="btn btn-outline-primary btn-sm" onclick="abrirModalResponder(this.dataset.id,\'aceptada\')" data-id="idSol"><i class="fas fa-reply"></i> Responder</button>' },
                'ResponsableMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
                'SolicitaMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
            };
        } else {
            // Fallback para tablas desconocidas
            return {
                'Yosolicito': { estilo: 'light', boton: '<span class="badge text-bg-secondary">Sin acción</span>' },
                'SoyResponsable': { estilo: 'light', boton: '<span class="badge text-bg-secondary">Sin acción</span>' },
                'ResponsableMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
                'SolicitaMiPersonal': { estilo: 'light', boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' },
            };
        }
    })();

data.forEach(function (solicitud) {
    // Obtiene la acción y el estilo del mapa, con un fallback si no se encuentra
    const accion = accionesPorTipo[solicitud.solicita] || { 
        estilo: 'secondary', 
        boton: '<span class="badge text-bg-primary">En espera de respuesta</span>' 
    };
    
    let comentarios = '';

    // Lógica para determinar el contenido de comentarios usando if/else if
    if (selectorTabla === "#TSolAbiertas tbody") {
        // Solo comentario de la solicitud
        comentarios = `Comentarios Sol: ${solicitud.comentarios_solicitud || 'Sin comentarios'}`;
    } else if (selectorTabla === "#TSolAceptadas tbody") {
        // Comentario de solicitud y respuesta
        comentarios = `<b>Coment. Sol:</b>${solicitud.comentarios_solicitud}<br><b>Coment. Resp: </b>${solicitud.comentarios_replica}`;
    } else {
        // #TSolCerradas o cualquier otro caso: Solicitud, Respuesta y Cierre
        comentarios = `<b>Coment. Sol:</b>${solicitud.comentarios_solicitud}<br><b>Coment. Resp: </b>${solicitud.comentarios_replica}<br><b>Coment. Cierre: </b>${solicitud.comentarios_cierre}`;
    }

    // Reemplaza el ID en el HTML del botón
    // Asegúrate de que 'this.dataset.id' sea un marcador de posición válido en accion.boton
    const botonFinal = accion.boton.replace('this.dataset.id', solicitud.id_solicitud);

    const comentariosEscapados = comentarios.replace(/"/g, "&quot;"); // Escapa comillas dobles usando entidad HTML
    
    // Construcción de la fila de la tabla
    const fila = `
        <tr class='table-${accion.estilo}'>
            <td>${solicitud.nombre_usuario}</td>
            <td>${solicitud.responsable}</td>
            <td>${solicitud.fecha_incidente}</td>
            <td>${solicitud.fecha_cierre}</td>
            <td>${solicitud.tipo}</td>
            <td>${solicitud.detalle_incidencia}</td>
            <td>
                <button class='btn btn-outline-primary btn-sm' 
                    onclick='verComentarios("${comentariosEscapados}")'>
                <i class='fas fa-comments'></i>
            </button>
            </td>
            <td>${botonFinal}</td>
        </tr>`;
    
    tabla.append(fila);
});

}

// FUNCION PARA MOSTRAR MENSAJE DE ERROR
function mostrarMensajeDeError() {
    Swal.fire({
        title: "La solicitud no se pudo procesar!",
        icon: "error",
        draggable: true
    });
}

//FUNCION PARA ABRIR EL MODAL PARA RESPONDER LA SOLICITUD
function abrirModalResponder(idIncidencia, tipoSolicitud) {
    
    $('#tipoSolicitud').val(tipoSolicitud);
    $('#idIncidencia').val(idIncidencia);
    // Limpia los campos del modal
    $('#comentariosIncidencia').val('');
    $('#respuestaIncidencia').empty();

    let opciones = '';
    if (tipoSolicitud === 'abierta') {
        opciones = `
            <option value="">Seleccione una respuesta</option>
            <option value="Aceptada">Aceptar</option>
            <option value="Rechazada">Rechazar</option>
        `;
    } else if (tipoSolicitud === 'aceptada') {
        opciones = `
            <option value="">Seleccione una respuesta</option>
            <option value="EnProceso">En proceso</option>
            <option value="Cerrada">Cerrada</option>
            <option value="Rechazada">Rechazada</option>
        `;
    }
    $('#respuestaIncidencia').html(opciones);

    $('#responderIncidenciaModal').modal('show');
    $('#idIncidencia').val(idIncidencia);

}

//FUNCION PARA OBTENER EL VALOR DE LA COOKIE
function getCookie(name) {
    let value = "; " + document.cookie;
    let parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}

function aplicarEstiloDataTable(tablaId, ordenColumna) {
    
        $(tablaId).DataTable({
                "responsive": true,
                "language": {
                    //"url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                },
                "order": false,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                "pageLength": 10,                
                "searching": false
        });
    
}

//FUNCION PARA VER COMENTARIOS DE LA INCIDENCIA
function verComentarios(comentarios) {
    Swal.fire({
        title: 'Comentarios de la Incidencia',
        html: comentarios,
        icon: 'info',
        confirmButtonText: 'Cerrar',
        draggable: true
    });
}