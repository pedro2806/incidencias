<!-- Topbar -->
<nav class = "navbar navbar-expand navbar-light bg-white topbar mb-2 static-top shadow">
<!-- Enlace a Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Enlace a Bootstrap JS (necesario para el funcionamiento del modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Enlace a FontAwesome para los íconos (si usas íconos) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- Sidebar Toggle (Topbar) -->
<button id = "sidebarToggleTop" class = "btn btn-link d-md-none rounded-circle mr-3">
    <i class = "fa fa-bars"></i>
</button>

<!-- Topbar Search 
<form
    class = "d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
    <div class = "input-group">
        <input type = "text" class = "form-control bg-light border-0 small" placeholder = "Search for..."
            aria-label = "Search" aria-describedby = "basic-addon2">
        <div class = "input-group-append">
            <button class = "btn btn-primary" type = "button">
                <i class = "fas fa-search fa-sm"></i>
            </button>
        </div>
    </div>
</form>
-->

<!-- Topbar Navbar -->
<ul class = "navbar-nav ml-auto">

    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
    <li class = "nav-item dropdown no-arrow d-sm-none">
        <a class = "nav-link dropdown-toggle" href = "#" id = "searchDropdown" role = "button"
            data-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "false">
            <i class = "fas fa-search fa-fw"></i>
        </a>
        <!-- Dropdown - Messages -->
        <div class = "dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
            aria-labelledby = "searchDropdown">
            <form class = "form-inline mr-auto w-100 navbar-search">
                <div class = "input-group">
                    <input type = "text" class = "form-control bg-light border-0 small"
                        placeholder = "Search for..." aria-label = "Search"
                        aria-describedby = "basic-addon2">
                    <div class = "input-group-append">
                        <button class = "btn btn-primary" type = "button">
                            <i class = "fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </li>

    <li class="nav-item no-arrow">
        <button class="btn btn-link nav-link fw-bold text-dark" type="button" id="btnNotificaciones" onclick="mostrarNotificacionesFlotantes()">
            <span class="noti-icon-wrap">
                <i class="fas fa-bell text-dark"></i>
                <span id="badgeNotificaciones" class="badge rounded-pill bg-danger d-none noti-badge">0</span>
            </span>
        </button>
    </li>

    <div class = "topbar-divider d-none d-sm-block"></div>
    <!-- Nav Item - User Information -->
    <li class = "nav-item dropdown no-arrow">
        <a class = "nav-link dropdown-toggle" href = "#" id = "userDropdown" role = "button"
            data-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "false">
            <span class = "mr-2 d-none d-lg-inline text-gray-600 small">
                <?php echo $_COOKIE['nombredelusuario']?>
            </span>
            <?php
            $currentURL = $_SERVER['REQUEST_URI']; // Obtiene la ruta actual de la URL

            if (strpos($currentURL, "/incidencias/SalasDeJuntas/") !== false || 
                strpos($currentURL, "/incidencias/inicio") !== false) {
                echo '<img class="img-profile rounded-circle" 
                     src="/incidencias/img/undraw_profile.svg" 
                     style="width: 100%;">';
            } else {
                echo '<img class="img-profile rounded-circle" 
                     src="/incidencias/img/undraw_profile.svg"  
                     style="width: 100%;">';
            }
            ?>
        </a>
        <!-- Dropdown - User Information -->
        <div class = "dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby = "userDropdown">
            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                Cambiar Contraseña
            </button>

            <!--<a class = "dropdown-item" href = "#">
                <i class = "fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                Settings
            </a>
            <a class = "dropdown-item" href = "#">
                <i class = "fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                Activity Log
            </a>-->
            <div class = "dropdown-divider"></div>
            <a class = "dropdown-item" href = "#" data-toggle = "modal" data-target = "#logoutModalN">
                <i class = "fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Salir
            </a>
        </div>
    </li>

</ul>
    <!-- MODAL PARA CAMBIO DE CONTRASEÑA-->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Cambiar Contraseña</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body">
                <div class = "row">
                    <div class="col-sm-6">
                        <label>Contraseña Nueva:</label>
                        <input id="nuevapass" name="nuevapass" class="form-control" type="password" required>
                    </div>
                
                    <div class="col-sm-6">
                        <label>Confirmar Contraseña:</label>
                        <input id="confirmapass" name="confirmapass" class="form-control" type="password" required>
                        <label id="msgPassword" name ="msgPassword"></label>
                        
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-sm-1"></div>
                    <div class = "col-sm-6">
                        <input class="form-check-input" type="checkbox" id="showPassword">
                            <label class="form-check-label" for="showPassword">
                                Ver Contraseña
                            </label>
                        </input>
                    </div>
                    <div class = "col-sm-1">
                        <input type="hidden" id="noEmpleado" name ="noEmpleado"> </input>
                    </div>
                </div>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" OnClick = "validarContrasenas()">Confirmar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Logout Modal-->
    <div class = "modal fade" id = "logoutModalN" tabindex = "-1" role = "dialog" aria-labelledby = "exampleModalLabel"aria-hidden = "true">
        <div class = "modal-dialog" role = "document">
            <div class = "modal-content border-left-danger">
                <div class = "modal-header">
                    <h4 class = "modal-title" id = "exampleModalLabel"> Cerrar sesión </h4>
                    <button class = "close" type = "button" data-dismiss = "modal" aria-label = "Close">
                        <span aria-hidden = "true">X</span>
                    </button>
                </div>
                <div class = "modal-body"><h5><b>¿Estas seguro?</b></h5></div>
                <div class = "modal-footer">
                    <button class = "btn btn-warning" type = "button" data-dismiss = "modal">Cancelar</button>
                    <a class = "btn btn-danger" href = "logout">Salir</a>
                </div>
            </div>
        </div>
    </div>

    <div id="notificationStack" style="position: fixed; top: 80px; right: 20px; z-index: 1060; width: 360px;"></div>
    
    <script>
    function mostrarNotificacionesFlotantes() {
        cargarNotificaciones(true);
    }

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    function limpiarStackNotificaciones() {
        $('#notificationStack').empty();
    }

    function cargarContadorNotificaciones() {
        var badge = $('#badgeNotificaciones');

        $.ajax({
            url: 'acciones_notificaciones.php',
            method: 'POST',
            dataType: 'json',
            data: { accion: 'contarNotificaciones' },
            success: function(response) {
                if (!response || !response.success) {
                    return;
                }

                var total = parseInt(response.total || 0, 10);
                if (total > 0) {
                    badge.removeClass('d-none').text(total > 99 ? '99+' : total);
                } else {
                    badge.addClass('d-none').text('0');
                }
            }
        });
    }

    function cargarNotificaciones(mostrarFlotantes) {
        var badge = $('#badgeNotificaciones');

        $.ajax({
            url: 'acciones_notificaciones.php',
            method: 'POST',
            data: { accion: 'cargarNotificaciones' },
            dataType: 'json',
            success: function(response) {
                if (!response || !response.success) {
                    return;
                }

                var notificaciones = response.notificaciones || [];
                var total = parseInt(response.total || 0, 10);

                if (total > 0) {
                    badge.removeClass('d-none').text(total > 99 ? '99+' : total);
                } else {
                    badge.addClass('d-none').text('0');
                }

                if (mostrarFlotantes !== true) {
                    return;
                }

                limpiarStackNotificaciones();

                if (notificaciones.length > 0) {
                    notificaciones.forEach(function(notificacion) {
                        renderNotificacionFlotante(notificacion);
                    });
                } else {
                    renderNotificacionFlotante({
                        id: 0,
                        iniciales: 'OK',
                        nota: 'No tienes nuevas notificaciones.',
                        fecha_actualizacion: ''
                    });
                }
            }
        });
    }

    // Función para determinar el ícono de la notificación según el sistema
    function obtenerIconoNotificacion(sistema) {
        if (sistema.indexOf('incidencia') !== -1) {
            return 'fas fa-exclamation-triangle';
        }
        if (sistema.indexOf('ctrlVehicular') !== -1) {
            return 'fas fa-car';
        }
        if (sistema.indexOf('entradasEq') !== -1) {
            return 'fas fa-users';
        }
        if (sistema.indexOf('planeacion') !== -1) {
            return 'fas fa-calendar-alt';
        }
        if (sistema.indexOf('activos') !== -1) {
            return 'fas fa-box';
        }
        return 'fas fa-bell';
    }

    // Función para renderizar una notificación flotante
    function renderNotificacionFlotante(notificacion) {
        var stack = $('#notificationStack');
        var sistema = escapeHtml(notificacion.sistema || accion || 'General');
        var fecha = escapeHtml(notificacion.fecha_actualizacion || notificacion.fecha || '');
        var iconoSistema = obtenerIconoNotificacion(sistema.toLowerCase());
        var id = parseInt(notificacion.id, 10) || 0;
        var idRegistro = parseInt(notificacion.id_registro_referencia, 10) || 0;
        var sistema = escapeHtml(notificacion.sistema || 'General');
        var archivo = escapeHtml(notificacion.archivo || '');
        var recordar = escapeHtml(notificacion.recordar || '');
        var creadoPor = escapeHtml(notificacion.usuario_actualiza_nombre || notificacion.id_usuario_actualiza || '');

        var html = '';
        html += '<div class="toast show border-0 shadow-sm mb-3" data-notificacion-id="' + id + '" role="alert" aria-live="assertive" aria-atomic="true">';
        html += '  <div class="toast-body p-2">';
        html += '      <div class="d-flex justify-content-between align-items-center">';
        html += '          <div class="d-flex align-items-center flex-wrap">';
        html += '              <span class="badge rounded-pill bg-primary text-white px-3 py-2 mr-2 mb-1">';
        html += '                  <i class="' + iconoSistema + ' mr-2"></i>' + sistema;
        html += '              </span>';
        html += '              <div class="mb-1">';
        html += '                  <span class="text-dark font-weight-bold mr-3" style="font-size: .95rem; line-height:1.1;">' + creadoPor + ' - ' + recordar + '</span>';
        html += '                  <span class="text-muted" style="font-size: .90rem; white-space: nowrap;"><i class="far fa-calendar-alt mr-1"></i>' + fecha + '</span>';
        html += '              </div>';
        html += '          </div>';
        html += '          <button class="btn btn-sm btn-light border border-success text-success px-2 py-1" title="Marcar como leída" aria-label="Marcar como leída" onclick="marcarNotificacionLeida(' + id + ', ' + idRegistro + ', \'' + sistema + '\', \'' + archivo + '\', \'' + getCookie('noEmpleadoL') + '\')">';
        html += '              <i class="fas fa-check fa-sm"></i>';
        html += '          </button>';
        html += '      </div>';
        html += '  </div>';
        html += '</div>';

        var toast = $(html);
        stack.append(toast);

        setTimeout(function() {
            toast.fadeOut(10000, function() {
                $(this).remove();
            });
        }, 5000);
    }

    function construirUrlNotificacion(sistema, archivo, idRegistro) {
        var id = parseInt(idRegistro || 0, 10);
        var sis = (sistema || '').toLowerCase();
        var arc = (archivo || '').toLowerCase();

        if (sis === 'incidencias' && arc === 'solicitudestatus' && id > 0) {
            return 'solicitudestatus?id=' + id;
        }

        if (archivo && id > 0) {
            return archivo + '?id=' + id;
        }

        return '';
    }

    function marcarNotificacionLeida(idNotificacion, idRegistro, sistema, archivo) {
        if (!idNotificacion || parseInt(idNotificacion, 10) <= 0) {
            return;
        }

        $.ajax({
            url: 'acciones_notificaciones.php',
            method: 'POST',
            dataType: 'json',
            data: { accion: 'marcarLeida', idNotificacion: idNotificacion },
            success: function(response) {
                if (!response || !response.success) {
                    return;
                }

                var toast = $('#notificationStack [data-notificacion-id="' + parseInt(idNotificacion, 10) + '"]');
                if (toast.length > 0) {
                    toast.remove();
                }

                cargarContadorNotificaciones();

                var url = construirUrlNotificacion(sistema, archivo, idRegistro);
                if (url !== '') {
                    window.location.href = url;
                }
            }
        });
    }

    function registrarEventosNotificaciones() {
        $(document).off('click', '.btn-marcar-leida').on('click', '.btn-marcar-leida', function () {
            var id = parseInt($(this).data('id') || 0, 10);
            var registro = parseInt($(this).data('registro') || 0, 10);
            var sistema = $(this).data('sistema') || '';
            var archivo = $(this).data('archivo') || '';
            marcarNotificacionLeida(id, registro, sistema, archivo);
        });
    }

    // Función para mostrar/ocultar contraseñas
    document.getElementById('showPassword').addEventListener('change', function () {
        var passwordField = document.getElementById('nuevapass');
        var confirmPasswordField = document.getElementById('confirmapass');
        
        if (this.checked) {
          // Mostrar contraseñas (tipo 'text')
          passwordField.type = 'text';
          confirmPasswordField.type = 'text';
        } else {
          // Ocultar contraseñas (tipo 'password')
          passwordField.type = 'password';
          confirmPasswordField.type = 'password';
        }
    });
    
    //Funcion para validar las contraseñas
    function validarContrasenas() {
        var password = $('#nuevapass').val()
        var confirmPassword = $('#confirmapass').val()
        var error = document.getElementById("error");

        // Si las contraseñas no coinciden
        if (password !== confirmPassword) {
            $('#msgPassword').text("Las constraseñas no coinciden."); 
        } else {
            Confirmar();
        }
    }
    
    //Funcion para Enviar los datos
    function Confirmar(){
        var password = $('#nuevapass').val();
        var noEmpleado = $('#noEmpleado').val();
        var accion = "CambioPassword";
        
        $.ajax({
            url: 'acciones_contrasena.php',
            method: 'POST',
            async: false,
            dataType: 'json',
            data:{accion, password, noEmpleado},
            success: function(Registros) {
                Swal.fire({
                    title: "Confirmado!",
                    text: "Contraseña cambiada!",
                    icon: "success",
                    timer: 2000,
                    timerProgressBar: true
                }).then(function() {
                    // Limpiar los campos después de cerrar la alerta
                    $('#nuevapass').val('');
                    $('#confirmapass').val('');
                    $('#staticBackdrop').modal('hide');
                });
            },error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al aplicar el cambio', error);
            }
        });
    }

    //Funcion para leer cookies
    function getCookie(name) {
        let value = "; " + document.cookie;
        let parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
        return null; // Si no encuentra la cookie, retorna null
    }
    // Asignar el valor de la cookie al input
    window.addEventListener('load', function() {
        var cookieValue = getCookie("noEmpleado"); // Aquí "noEmpleadoCookie" es el nombre de la cookie
    
        // Verificar si la cookie existe y asignar el valor al input
        if (cookieValue) {
            document.getElementById("noEmpleado").value = cookieValue;
        }

        if (typeof window.jQuery !== 'undefined') {
            registrarEventosNotificaciones();
            cargarContadorNotificaciones();
        }
    });
    </script>
</nav>
<!-- End of Topbar -->