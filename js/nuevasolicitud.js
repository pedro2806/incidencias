/**
 * Lógica de la página "Nueva solicitud" (nuevasolicitud_v2.php).
 *
 * Backend dedicado: acciones_nuevasolicitud.php
 *   - empleadosSolicita : pobla el select de empleados
 *   - jefeAutoriza      : muestra quién autoriza
 *   - agregarSolicitud  : guarda la solicitud
 * Otros endpoints reutilizados:
 *   - getInfoLoginMaster.php (getPlaca)         -> aviso de vehículo
 *   - acciones_notificaciones.php               -> notificación de vacaciones
 */

const BACKEND = 'acciones_nuevasolicitud.php';

let renglonCounter = 1;

$(document).ready(function () {
    empleadoSolicita();
    cargaJefeAutoriza();
    validaRol();
    validarAntiguedad();
    validarDiasDisponibles();
    $('#solicita').select2();
});

/* ------------------------- Renglones dinámicos ------------------------- */

function agregarRenglon() {
    renglonCounter++;

    const nuevoRenglon = document.createElement('div');
    nuevoRenglon.classList.add('row');
    nuevoRenglon.id = `renglon-${renglonCounter}`;

    nuevoRenglon.innerHTML = `
        <div class="col-sm-1"></div>
        <div class="col-sm-4">
            <div class="mb-1">
                <input type="date" class="form-control" id="fechaInicial-${renglonCounter}" name="fechaInicial[]" onchange="diasEntreFechas(${renglonCounter});" required>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="mb-1">
                <input type="date" class="form-control" id="fechaFinal-${renglonCounter}" name="fechaFinal[]" onchange="diasEntreFechas(${renglonCounter});" required>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="mb-1">
                <input type="number" class="form-control" id="noDias-${renglonCounter}" name="noDias[]" readonly>
            </div>
        </div>
    `;

    document.getElementById('renglones-container').appendChild(nuevoRenglon);
}

function eliminarUltimoRenglon() {
    if (renglonCounter > 1) {
        const ultimoRenglon = document.getElementById(`renglon-${renglonCounter}`);
        if (ultimoRenglon) {
            ultimoRenglon.remove();
            renglonCounter--;
        }
    } else {
        alert("Se tiene que capturar al menos un periodo.");
    }
}

/* ------------------------- Tipo de incidencia -------------------------- */

function tIncidencia(ti) {
    $('#opIncidencia').val(ti);
    if (ti == 1) {
        const noEmpleado = $('#solicita').val() || getCookie('noEmpleado');
        verificaVehiculo(noEmpleado);
    }
}

function verificaVehiculo(noEmpleado) {
    if (!noEmpleado) return;
    $.ajax({
        url: 'getInfoLoginMaster.php',
        method: 'POST',
        dataType: 'json',
        data: { accion: 'getPlaca', noEmpleado: noEmpleado },
        success: function (data) {
            if (data && data.success === true) {
                Swal.fire({
                    title: '¡Aviso Importante!',
                    html: 'Tienes un vehículo asignado con placa <b>' + data.placa + '</b>.<br><br>' +
                          'Durante los días que <b>no labores</b>, deberás dejar tu vehículo en las instalaciones de la empresa.',
                    icon: 'warning',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#3085d6'
                });
            }
        }
    });
}

/* ----------------------- Cálculo de días hábiles ----------------------- */

function diasEntreFechas(idRenglon) {
    const fechaInicialStr = $(`#fechaInicial-${idRenglon}`).val();
    const fechaFinalStr = $(`#fechaFinal-${idRenglon}`).val();

    if (!fechaInicialStr || !fechaFinalStr) return;

    // split + constructor (año, mes - 1, día) para evitar desfases de zona horaria
    const partesIni = fechaInicialStr.split('-');
    const partesFin = fechaFinalStr.split('-');

    const fechaDesde = new Date(partesIni[0], partesIni[1] - 1, partesIni[2]);
    const fechaHasta = new Date(partesFin[0], partesFin[1] - 1, partesFin[2]);

    let contador = 0;
    const tempFecha = new Date(fechaDesde);

    while (tempFecha <= fechaHasta) {
        const diaSemana = tempFecha.getDay(); // 0 = Domingo, 6 = Sábado
        if (diaSemana !== 0 && diaSemana !== 6) {
            contador++;
        }
        tempFecha.setDate(tempFecha.getDate() + 1);
    }

    $(`#noDias-${idRenglon}`).val(contador);
}

/* --------------------------- Carga inicial ----------------------------- */

function empleadoSolicita() {
    $.ajax({
        url: BACKEND,
        method: 'POST',
        dataType: 'json',
        data: { accion: 'empleadosSolicita' },
        success: function (data) {
            const select = $('#solicita');
            data.forEach(function (usuario) {
                const option = $('<option></option>')
                    .attr('value', usuario.noEmpleado)
                    .text(usuario.nombre);
                select.append(option);
            });
            seleccionaUsuario();
        },
        error: function () {
            Swal.fire({ title: "La solicitúd no se pudo procesar!", icon: "error", draggable: true });
        }
    });
}

function cargaJefeAutoriza() {
    $.ajax({
        url: BACKEND,
        method: 'POST',
        dataType: 'json',
        data: { accion: 'jefeAutoriza' },
        success: function (data) {
            if (data && data.success === true) {
                $('#autorizaJefe').text(data.jefe || '');
            }
        }
    });
}

/* -------------------------- Envío de solicitud ------------------------- */

function generarSolicitud() {
    const diasDisp = $('#diasDisponibles').val();
    if (diasDisp < 1) {
        Swal.fire({ title: "No tienes días disponibles para solicitar.", icon: "warning", draggable: true });
        return;
    }

    const opIncidencia = $('#opIncidencia').val();
    const notas = $('#notas').val();
    const comentarios = $('#comentarios').val();
    const solicita = $('#solicita').val();

    if (opIncidencia == '' || opIncidencia == null) {
        Swal.fire({ title: "Es necesario seleccionar el tipo de incidencia!", icon: "warning", draggable: true });
        return;
    }

    // Recolectar los periodos de las filas dinámicas
    const periodos = [];
    $('.dynamic-row .row').each(function (index, row) {
        periodos.push({
            fechaInicial: $(row).find('input[name="fechaInicial[]"]').val(),
            fechaFinal: $(row).find('input[name="fechaFinal[]"]').val(),
            noDias: $(row).find('input[name="noDias[]"]').val()
        });
    });

    $.ajax({
        url: BACKEND,
        method: 'POST',
        dataType: 'json',
        data: {
            accion: 'agregarSolicitud',
            opIncidencia: opIncidencia,
            notas: notas,
            comentarios: comentarios,
            solicita: solicita,
            periodos: periodos
        },
        success: function (data) {
            if (data && data.success === false) {
                Swal.fire({ title: "La solicitúd no se pudo procesar!", icon: "error", draggable: true });
                return;
            }

            const idRegistroReferencia = (data && data.id_registro_referencia)
                ? parseInt(data.id_registro_referencia, 10) : 0;

            function mostrarExito() {
                Swal.fire({ title: "¡La solicitud se procesó con éxito!", icon: "success", draggable: true })
                    .then(function () {
                        registrarNotificacionVacaciones(solicita, idRegistroReferencia, function () {
                            window.location.href = 'solicitudestatus';
                        });
                    });
            }

            // Aviso de vehículo antes de cerrar el flujo
            const noEmpleadoSolicita = solicita || getCookie('noEmpleado');
            $.ajax({
                url: 'getInfoLoginMaster.php',
                method: 'POST',
                dataType: 'json',
                data: { accion: 'getPlaca', noEmpleado: noEmpleadoSolicita },
                success: function (vehiculoData) {
                    if (vehiculoData && vehiculoData.success === true) {
                        Swal.fire({
                            title: '¡Recuerda!',
                            html: 'Tienes un vehículo asignado con placa <b>' + vehiculoData.placa + '</b>.<br><br>' +
                                  'Durante los días que <b>no labores</b>, deberás dejar tu vehículo en las instalaciones de la empresa.',
                            icon: 'warning',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#3085d6'
                        }).then(mostrarExito);
                    } else {
                        mostrarExito();
                    }
                },
                error: mostrarExito
            });
        },
        error: function () {
            Swal.fire({ title: "La solicitúd no se pudo procesar!", icon: "error", draggable: true });
        }
    });
}

function registrarNotificacionVacaciones(solicita, idRegistroReferencia, callback) {
    $.ajax({
        url: 'acciones_notificaciones.php',
        method: 'POST',
        dataType: 'json',
        data: {
            accion: 'registrarNotificacionVacaciones',
            solicita: solicita,
            id_registro_referencia: idRegistroReferencia
        },
        complete: function () {
            if (typeof callback === 'function') {
                callback();
            }
        }
    });
}

/* ----------------------------- Helpers UI ------------------------------ */

function seleccionaUsuario() {
    const cookieValue = getCookie('noEmpleado');
    if (cookieValue) {
        const selectElement = document.getElementById('solicita');
        selectElement.value = cookieValue;
        if (selectElement.selectedIndex >= 0) {
            selectElement.options[selectElement.selectedIndex].setAttribute('selected', true);
        }
    }
}

function validaRol() {
    const cookieRol = getCookie('rol');
    const nombre = getCookie('nombredelusuario');
    const etiqueta = document.getElementById('NSolicita');
    etiqueta.innerHTML = nombre;

    if (cookieRol == 1) {
        document.getElementById('Divsolicita').style.display = 'none';
        document.getElementById('DivsolicitaMss').style.display = 'none';
    }
}

function validarAntiguedad() {
    const antiguedad = getCookie('antiguedad');
    if (antiguedad < 1) {
        $("#btnSolicitar").prop("disabled", true);
        $("#mensaje").text("No puedes solicitar vacaciones hasta tener un año de antigüedad.");
    } else {
        $("#btnSolicitar").prop("disabled", false);
        $("#mensaje").text("MESS " + new Date().getFullYear());
    }
}

function validarDiasDisponibles() {
    const diasDisp = $('#diasDisponibles').val();
    if (diasDisp < 1) {
        $("#btnSolicitar").prop("disabled", true);
        $("#btnSolicitar").hide();
        $("#mensaje").text("No puedes solicitar vacaciones. * No tienes dias disponibles. *");
        $("#mensaje").addClass("badge text-bg-warning");
        $("#mensaje").css("font-size", "1.2rem");
    } else {
        $("#btnSolicitar").prop("disabled", false);
        $("#mensaje").text("MESS " + new Date().getFullYear());
        $("#mensaje").addClass("badge text-bg-primary");
    }
}

function getCookie(name) {
    const cookies = new URLSearchParams(document.cookie.replace(/; /g, '&'));
    return cookies.get(name) || undefined;
}
