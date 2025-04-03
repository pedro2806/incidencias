<?php
    include '../conn.php';
    if($_COOKIE['noEmpleado'] == '' || $_COOKIE['noEmpleado'] == null){
        echo '<script>window.location.assign("index")</script>';
    }
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="inicio">
    <div class="sidebar-brand-icon rotate-n-1">
        <img class="sidebar-card-illustration mb-2" href="../inicio" src="../img/MESS_07_CuboMess_2.png" width="40" alt="Logo">
    </div>
</a>
<hr class="sidebar-divider my-0">
<!-- Nav Item - Dashboard -->
<li class="nav-item active">
    <a class="nav-link" href="../inicio">
        <i class="fas fa-fw fa-home"></i>
        <span>Volver a Incidencias</span>
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
    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-calendar-day"></i>
        <span>Sala de Juntas</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingUtilities">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="index">Ver calendario</a>
            <a class="collapse-item" href="modificarAgenda">Modificar/Eliminar Reserva</a>
        </div>
    </div>
</li>
<hr class="sidebar-divider d-none d-md-block">
</ul>
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button> 
</div>
</ul>