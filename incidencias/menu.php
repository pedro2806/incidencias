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
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="../logout.php?sesion=LM"">
    <div class="sidebar-brand-icon rotate-n-1">
        <img class="sidebar-card-illustration mb-2" src="../img/MESS_07_CuboMess_2.png" width="40" alt="Logo">
    </div>
</a>
<hr class="sidebar-divider my-0">
<!-- Nav Item - Dashboard -->
<li class="nav-item active btn-warning">
    <a class="nav-link" href="../logout.php?sesion=LM"">
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
<?php
$usuariosRegistran = array(212, 14, 42, 161, 403, 183, 521, 276, 523, 71, 5, 360, 487, 19, 37, 206);

if (in_array($_COOKIE['noEmpleado'], $usuariosRegistran)) {
?>
    <li class="nav-item">
    <a class="nav-link" href="index">
        <i class="fas fa-fw fa-edit"></i>
        <span>Registrar Incidencias</span>
    </a>
</li>
<?php
}
?>

<li class="nav-item">
    <a class="nav-link" href="seguimiento_incidencias">
        <i class="fas fa-fw fa-location-arrow"></i>
        <span>Seguimiento incidencias</span>
    </a>
</li>

<?php
$usuariosRegistran = array(212, 14, 42, 161, 403, 183, 521, 276, 523, 71, 5, 360, 487, 19, 37, 206);
    if (in_array($_COOKIE['noEmpleado'], $usuariosRegistran)) {
?>   
    <li class="nav-item">
        <a class="nav-link" href="grafica_incidencias">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Resumen de los Registros</span>
        </a>
    </li>
<?php
    }
?>

<?php
$usuariosReporteInc = array(403, 183, 521, 276, 523, 5);
    if (in_array($_COOKIE['noEmpleado'], $usuariosReporteInc)) {
?>   
    <li class="nav-item">
        <a class="nav-link" href="detalle_incidencias">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Detalle incidencias</span>
        </a>
    </li>
<?php
    }
?>

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