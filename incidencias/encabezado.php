<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-2 static-top shadow">

<!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto">

    <!-- Toggle de tema claro/oscuro -->
    <li class="nav-item d-flex align-items-center mr-2">
        <button id="themeToggle" type="button" class="theme-toggle-btn" title="Cambiar tema">
            <i class="fas fa-moon"></i>
        </button>
    </li>
    <script>
    (function () {
        var btn = document.getElementById('themeToggle');
        function applyTheme(theme) {
            var icon = btn ? btn.querySelector('i') : null;
            if (theme === 'dark') {
                document.body.classList.add('theme-dark');
                if (icon) { icon.classList.remove('fa-moon'); icon.classList.add('fa-sun'); }
            } else {
                document.body.classList.remove('theme-dark');
                if (icon) { icon.classList.remove('fa-sun'); icon.classList.add('fa-moon'); }
            }
            try { localStorage.setItem('mess-theme', theme); } catch (e) {}
        }
        // Lee localStorage para persistir el tema entre páginas
        var saved = '';
        try { saved = localStorage.getItem('mess-theme') || ''; } catch (e) {}
        applyTheme(saved === 'dark' ? 'dark' : 'light');
        if (btn) {
            btn.addEventListener('click', function () {
                applyTheme(document.body.classList.contains('theme-dark') ? 'light' : 'dark');
            });
        }
    })();
    </script>

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Info del usuario -->
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                <?php echo htmlspecialchars($_COOKIE['nombredelusuario'] ?? '', ENT_QUOTES); ?>
            </span>
            <img class="img-profile rounded-circle" src="/incidencias/img/undraw_profile.svg" style="width:32px;height:32px;">
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalCambiarPassword">
                <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                Cambiar Contraseña
            </button>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModalN">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Salir
            </a>
        </div>
    </li>

</ul>

<!-- Modal cambiar contraseña -->
<div class="modal fade" id="modalCambiarPassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">Contraseña Nueva</label>
                        <input id="nuevapass" class="form-control" type="password">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input id="confirmapass" class="form-control" type="password">
                        <small id="msgPassword" class="text-danger"></small>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="showPassword">
                            <label class="form-check-label" for="showPassword">Ver contraseña</label>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="noEmpleado">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="validarContrasenas()">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal logout -->
<div class="modal fade" id="logoutModalN" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cerrar sesión</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"><b>¿Estás seguro?</b></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger" href="../logout.php?sesion=LM">Salir</a>
            </div>
        </div>
    </div>
</div>

<script>
// ── Mostrar / ocultar contraseña ──────────────────────────────────────────
document.getElementById('showPassword').addEventListener('change', function () {
    var t = this.checked ? 'text' : 'password';
    document.getElementById('nuevapass').type = t;
    document.getElementById('confirmapass').type = t;
});

function validarContrasenas() {
    var p1 = $('#nuevapass').val(), p2 = $('#confirmapass').val();
    if (p1 !== p2) {
        $('#msgPassword').text('Las contraseñas no coinciden.');
        return;
    }
    $('#msgPassword').text('');
    var accion = 'CambioPassword', password = p1, noEmpleado = $('#noEmpleado').val();
    $.ajax({
        url: 'acciones_contrasena.php', method: 'POST', dataType: 'json',
        data: { accion, password, noEmpleado },
        success: function () {
            Swal.fire({ title: 'Contraseña cambiada', icon: 'success', timer: 2000, timerProgressBar: true })
                .then(function () {
                    $('#nuevapass,#confirmapass').val('');
                    $('#modalCambiarPassword').modal('hide');
                });
        },
        error: function () { Swal.fire({ title: 'Error al cambiar contraseña', icon: 'error' }); }
    });
}

// ── Cookie helper ─────────────────────────────────────────────────────────
function getCookie(name) {
    var cookies = new URLSearchParams(document.cookie.replace(/; /g, '&'));
    return cookies.get(name) || undefined;
}

// ── Inicialización al cargar ──────────────────────────────────────────────
window.addEventListener('load', function () {
    var campo = document.getElementById('noEmpleado');
    if (campo) { var v = getCookie('noEmpleado'); if (v) campo.value = v; }
});
</script>
</nav>
<!-- End of Topbar -->
