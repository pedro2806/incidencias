<?php
    
    include '../conn.php';
    if($_COOKIE['noEmpleado'] == '' || $_COOKIE['noEmpleado'] == null){
        echo '<script>window.location.assign("index")</script>';
    }
?>
<!-- Sidebar -->
<ul class = "navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id = "accordionSidebar">

<!-- Sidebar - Brand -->
<a class = "sidebar-brand d-flex align-items-center justify-content-center" href = "inicio">
    <div class = "sidebar-brand-icon rotate-n-1">
        <img class = "sidebar-card-illustration mb-2" src = "../img/MESS_07_CuboMess_2.png" width = "40">
    </div>
</a>
<hr class = "sidebar-divider my-0">
<!-- Nav Item - Dashboard -->
<li class = "nav-item active">
    <a class = "nav-link" href = "../inicio">
        <i class = "fas fa-fw fa-home"></i>
        <span>Inicio</span></a>
</li>
<!-- Divider -->
<hr class = "sidebar-divider">
<!-- Heading -->
<div class = "sidebar-heading">
    <span class="badge text-xl-white">
        Opciones
    </span>
</div>
<!-- Nav Item - Pages Collapse Menu -->
<li class = "nav-item">
    <a class = "nav-link collapsed" href = "#" data-toggle = "collapse" data-target = "#collapseTwo" aria-expanded = "true" aria-controls = "collapseTwo">
        <i class="fas fa-fw fa-calendar-day"></i>
        <span>Sala de Juntas</span>
    </a>
    <div id = "collapseTwo" class = "collapse" aria-labelledby = "headingTwo" data-parent = "#accordionSidebar">
        <div class = "bg-white py-1 collapse-inner rounded">
            <h6 class = "collapse-header">Opciónes:</h6>
            <a class = "collapse-item" href = "index">Agendar Sala</a>
            <a class = "collapse-item" href = "modificarAgenda">Modificar/Eliminar Solicitud</a>
            <a class = "collapse-item" href = "calendarioGral">Ver calendario sala</a>        
        </div>
    </div>
</li>
<hr class = "sidebar-divider d-none d-md-block">
<div class = "text-center d-none d-md-inline">
    <button class = "rounded-circle border-0" id = "sidebarToggle"></button>
</div>
</ul>