<?php
    include '../conn.php';
    if($_COOKIE['noEmpleado'] == '' || $_COOKIE['noEmpleado'] == null){
        echo '<script>window.location.assign("../index")</script>';
    }
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="../logout.php?sesion=LM">
    <div class="sidebar-brand-icon rotate-n-1">
        <img class="sidebar-card-illustration mb-2" src="../img/MESS_07_CuboMess_2.png" width="40" alt="Logo">
    </div>
</a>
<hr class="sidebar-divider my-0">
<!-- Nav Item - Dashboard -->
<li class="nav-item active btn-warning">
    <a class="nav-link" href="../logout.php?sesion=LM">
        <i class="fas fa-fw fa-backward"></i>
        <span>Volver</span>
    </a>
</li>
<!-- Divider -->
<hr class="sidebar-divider">
<!-- Heading -->
<div class="sidebar-heading">
    <span class="badge text-xl-white">Opciones</span>
</div>
<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a id="registrarIncidencias" style="display:none" class="nav-link" href="index">
        <i class="fas fa-fw fa-edit"></i>
        <span>Registrar Incidencias</span>
    </a>
</li>

<!--USUARIOS CON ACCESO= noEmpleado(212, 14, 42, 161, 403, 183, 521, 276, 523, 71, 5, 360, 487, 19, 37, 206, 263)-->
<li class="nav-item">
    <a class="nav-link" href="seguimiento_incidencias">
        <i class="fas fa-fw fa-location-arrow"></i>
        <span>Seguimiento incidencias</span>
    </a>
</li>


<!--USUARIOS CON ACCESO= noEmpleado(212, 14, 42, 161, 403, 183, 521, 276, 523, 71, 5, 360, 487, 19, 37, 206);-->
    <li class="nav-item">
        <a id="resumenRegistros" class="nav-link" href="grafica_incidencias">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Resumen de los Registros</span>
        </a>
    </li>

<!--USUARIOS CON ACCESO= noEmpleado(403, 183, 521, 276, 523, 5);-->
    <li class="nav-item">
        <a id="detalleIncidencias" class="nav-link" href="detalle_incidencias">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Detalle incidencias</span>
        </a>
    </li>


<li class = "nav-item">
    <a class = "nav-link" href = "#" data-toggle = "modal" data-target = "#logoutModalN">
        <i class = "fas fa-sign-out-alt text-gray-100"></i>
        Salir
    </a>
</li>

<hr class="sidebar-divider d-none d-md-block">

<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button> 
</div>
</ul>

<!-- Funciones Globales -->
<script src="../loginMaster/funcionesGlobales.js"></script>

<script>
    $(document).ready(function() {
        verificarAccesoRegistro();
        verificarAccesoResumen();
        verificarAccesoDetalle();
    });

    // Función para validar el acceso al registro de incidencias
    async function verificarAccesoRegistro() {
        // 1.Mandamos llamar nuestra función principal. Agregamos await para esperar la respuesta
        const respuesta = await validaOpciones2('incidencias', 'verRegistroIncidencias');
        // 2. Evaluamos la respuesta y aplicamos las acciones a realizar según el caso
        const cuantos = (respuesta && respuesta.status === 'success') 
                        ? parseInt(respuesta.data[0].cuantos) 
                        : 0;
        if (cuantos <= 0) {            
            $('#registrarIncidencias').hide();
        }else {
            $('#registrarIncidencias').show();
        }
    }

    // Función para validar el acceso al resumen de registros del sistema
    async function verificarAccesoResumen() {
        // 1.Mandamos llamar nuestra función principal. Agregamos await para esperar la respuesta
        const respuesta = await validaOpciones2('incidencias', 'verResumenRegistros');
        
        // 2. Evaluamos la respuesta y aplicamos las acciones a realizar según el caso
        const cuantos = (respuesta && respuesta.status === 'success') 
                        ? parseInt(respuesta.data[0].cuantos) 
                        : 0;

        if (cuantos <= 0) {            
            $('#resumenRegistros').hide();
        }else {
            $('#resumenRegistros').show();
        }
    }

    // Función para validar el acceso al detalle de incidencias
    async function verificarAccesoDetalle() {
        // 1.Mandamos llamar nuestra función principal. Agregamos await para esperar la respuesta
        const respuesta = await validaOpciones2('incidencias', 'verDetalleIncidencias');
        
        // 2. Evaluamos la respuesta y aplicamos las acciones a realizar según el caso
        const cuantos = (respuesta && respuesta.status === 'success') 
                        ? parseInt(respuesta.data[0].cuantos) 
                        : 0;

        if (cuantos <= 0) {            
            $('#detalleIncidencias').hide();
        }else {
            $('#detalleIncidencias').show();
        }
    }

    // Función para validar las opciones de acceso a las diferentes funcionalidades del sistema
    async function validaOpciones2(sistema, opcion) {
        return new Promise((resolve) => {
            $.ajax({
                url: '/loginMaster/acciones_globales.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    accion: 'ValidarPermisos',
                    sistema: sistema,
                    opcion: opcion,
                    noEmpleado: (document.cookie.match('(^|;)\\s*noEmpleado\\s*=\\s*([^;]+)') || [])[2] || ''
                },
                success: function(response) {
                    resolve(response);
                },
                error: function() {
                    resolve({ status: 'error', data: [{ cuantos: 0 }] });
                }
            });
        });
    }
</script>