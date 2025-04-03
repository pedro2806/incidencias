<?php 
include 'conn.php';
    if($_COOKIE['noEmpleado'] == '' || $_COOKIE['noEmpleado'] == null){
        echo '<script>window.location.assign("index")</script>';
    }
?>
<!-- Content Row -->
<div class = "row">

    <!-- Earnings (Monthly) Card Example -->
    <div class = "col-xl-3 col-md-6 mb-1">
        <div class = "card border-left-primary shadow h-60 py-0">
            <div class = "card-body">
                <div class = "row no-gutters align-items-center">
                    <div class = "col mr-2">
                        <div class = "text-md font-weight-bold text-primary text-uppercase mb-1">
                        </div>                                            
                        <div class = "h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $valor = $_COOKIE['nombredelusuario'].'  <br>  '; echo 'No. '.$_COOKIE['noEmpleado']?>
                        </div>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    
    <!-- Earnings (Monthly) Card Example -->
    <div class = "col-xl-3 col-md-6 mb-1">
        <div class = "card border-left-info shadow h-60 py-0">
            <div class = "card-body">
                <div class = "row no-gutters align-items-center">
                    <div class = "col mr-2">
                        <div class = "h5 mb-0 font-weight-bold text-gray-800">
                            Antigüedad: <?php echo $valor = $_COOKIE['antiguedad'];?>  años
                        </div>
                        <div class = "h5 mb-0 font-weight-bold text-gray-800">
                            Vac. por ley: <?php
                                $antiguedad = $_COOKIE['antiguedad'];
                                
                                $Qdias = "SELECT * FROM diasvacaciones WHERE anio = $antiguedad";
                                $resdias= mysqli_query( $conn, $Qdias ) or die (mysqli_error($conn));
                                
                                While ($row3 = mysqli_fetch_array($resdias)){
                                    $dias = $row3["dias"];
                                }
                                echo $dias;
                            ?> días
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <!-- DIAS SOL Y DIAS DISPO -->
    <div class = "col-xl-3 col-md-6 mb-1">
        <div class = "card border-left-warning h-60 py-0">
            <div class = "card-body">
                <div class = "row no-gutters align-items-center">
                    <div class = "col mr-2">
                        <div class = "h5 mb-0 font-weight-bold text-gray-800">
                            
                            Días sol: 
                            <?php
                                $noEmp = $_COOKIE['noEmpleado'];
                                
                                $Qdias = "SELECT * FROM usuarios WHERE noEmpleado = $noEmp";
                                $resdias= mysqli_query( $conn, $Qdias ) or die (mysqli_error($conn));
                                
                                While ($row3 = mysqli_fetch_array($resdias)){
                                    $FechaI = $row3["fechaIngreso"];
                                }
                                
                                $FechaIng = substr($FechaI, 4, 6);
                                
                                $anio = date("Y");
                                
                                $fechaCompara = $anio.$FechaIng;
                                
                                $hoy = date("Y-m-d");
                                if ($fechaCompara <= $hoy){
                                    $anioNext = $anio + 1;
                                    $fechaPrev = $anio.$FechaIng;
                                    $fechaNext = $anioNext.$FechaIng;
                                    
                                    $QdiasSol = "SELECT IFNULL(SUM(dias), '0') as diasSol FROM solicitudes WHERE empleado = $noEmp AND (estatus = 2 && autorizaRH = 2) AND fesolicitud BETWEEN '$fechaPrev' AND '$fechaNext' AND tipo = 1";
                                    $resdiasSol= mysqli_query( $conn, $QdiasSol ) or die (mysqli_error($conn));
                                    
                                    While ($rowSol = mysqli_fetch_array($resdiasSol)){
                                        $diasSol = $rowSol["diasSol"];
                                    }
                                }else{
                                    $anioPrev = $anio - 1;
                                    $fechaPrev = $anioPrev.$FechaIng;
                                    $fechaNext = $anio.$FechaIng;
                                    
                                    $QdiasSol = "SELECT SUM(dias) as diasSol FROM solicitudes WHERE empleado = $noEmp AND (estatus = 2 && autorizaRH = 2) AND fesolicitud BETWEEN '$fechaPrev' AND '$fechaNext'";
                                    $resdiasSol= mysqli_query( $conn, $QdiasSol ) or die (mysqli_error($conn));
                                    
                                    While ($rowSol = mysqli_fetch_array($resdiasSol)){
                                        $diasSol = $rowSol["diasSol"];
                                    }
                                }
                                echo $diasSol;
                                
                                
                            ?>
                            
                            
                             días
                        </div>
                        <div class = "h5 mb-0 font-weight-bold text-gray-800">
                            Días Disp: <?php echo $dias-$diasSol; ?>  días
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FECHA DE RENOVACION -  DIAS GOZADOS -->
    <div class = "col-xl-3 col-md-6 mb-1">
        <div class = "card border-left-success h-60 py-0">
            <div class = "card-body">
                <div class = "row no-gutters align-items-center">
                    <div class = "col mr-2">
                        <div class = "h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                                $noEmp = $_COOKIE['noEmpleado'];
                                
                                $Qdias = "SELECT * FROM usuarios WHERE noEmpleado = $noEmp";
                                $resdias= mysqli_query( $conn, $Qdias ) or die (mysqli_error($conn));
                                
                                While ($row3 = mysqli_fetch_array($resdias)){
                                    $FechaI = $row3["fechaIngreso"];
                                }
                                
                                $FechaIng = substr($FechaI, 4, 6);
                                
                                $anio = date("Y");
                                
                                $fechaCompara = $anio.$FechaIng;
                                
                                $hoy = date("Y-m-d");
                                if ($fechaCompara <= $hoy){
                                    $anioNext = $anio + 1;
                                    $fechaPrev = $anio.$FechaIng;
                                    $fechaNext = $anioNext.$FechaIng;
                                    
                                    $QdiasSol = "SELECT IFNULL(SUM(dias), '0') as diasSol FROM solicitudes WHERE empleado = $noEmp AND (estatus = 2 && autorizaRH = 2) AND fesolicitud BETWEEN '$fechaPrev' AND '$fechaNext'  AND tipo = 1";
                                    $resdiasSol= mysqli_query( $conn, $QdiasSol ) or die (mysqli_error($conn));
                                    
                                    While ($rowSol = mysqli_fetch_array($resdiasSol)){
                                        $diasSol = $rowSol["diasSol"];
                                    }
                                }else{
                                    $anioPrev = $anio - 1;
                                    $fechaPrev = $anioPrev.$FechaIng;
                                    $fechaNext = $anio.$FechaIng;
                                    
                                    $QdiasSol = "SELECT SUM(dias) as diasSol FROM solicitudes WHERE empleado = $noEmp AND (estatus = 2 && autorizaRH = 2) AND fesolicitud BETWEEN '$fechaPrev' AND '$fechaNext'";
                                    $resdiasSol= mysqli_query( $conn, $QdiasSol ) or die (mysqli_error($conn));
                                    
                                    While ($rowSol = mysqli_fetch_array($resdiasSol)){
                                        $diasSol = $rowSol["diasSol"];
                                    }
                                }
                            ?>

                        </div>
                        <div class = "h5 mb-0 font-weight-bold text-gray-800">
                            Fecha Renovación Vac: <?php echo $fechaNext; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>