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
                                // Saneo a entero: si la cookie 'antiguedad' viene vacía/ausente,
                                // queda 0 (anio = 0, válido) en vez de "WHERE anio = " (error de sintaxis).
                                $antiguedad = isset($_COOKIE['antiguedad']) ? (int) $_COOKIE['antiguedad'] : 0;

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
                            
                            Días solic: 
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
                                    
                                    $QdiasSol = "SELECT IFNULL(SUM(dias), '0') as diasSol 
                                                    FROM solicitudes 
                                                    WHERE empleado = $noEmp AND (estatus = 2 && autorizaRH = 2) 
                                                    AND fesolicitud BETWEEN '$fechaPrev' AND '$fechaNext' AND tipo = 1";
                                    $resdiasSol= mysqli_query( $conn, $QdiasSol ) or die (mysqli_error($conn));
                                    
                                    While ($rowSol = mysqli_fetch_array($resdiasSol)){
                                        $diasSol = $rowSol["diasSol"];
                                    }

                                    $anioAnt = $anio -1;
                                    $fechaAnt = $anioAnt.$FechaIng;
                                    $fechaSig = $anio.$FechaIng;

                                    $QdiasSolAnt ="SELECT IFNULL(SUM(dias), '0') as diasSol, (SELECT dias FROM diasvacaciones WHERE anio = $antiguedad-1) as vacLey
                                                    FROM solicitudes 
                                                    WHERE empleado = $noEmp AND (estatus = 2 && autorizaRH = 2) 
                                                    AND fesolicitud BETWEEN '$fechaAnt' AND '$fechaSig' AND tipo = 1";
                                    $resdiasSolAnt= mysqli_query( $conn, $QdiasSolAnt ) or die (mysqli_error($conn));
                                    While ($rowSolAnt = mysqli_fetch_array($resdiasSolAnt)){
                                        $diasSolAnt = $rowSolAnt["diasSol"];
                                        $vacLey = $rowSolAnt["vacLey"];
                                    }

                                }else{
                                    $anioPrev = $anio - 1;
                                    $fechaPrev = $anioPrev.$FechaIng;
                                    $fechaNext = $anio.$FechaIng;
                                    
                                    $QdiasSol = "SELECT SUM(dias) as diasSol FROM solicitudes WHERE empleado = $noEmp AND (estatus = 2 && autorizaRH = 2) AND fesolicitud BETWEEN '$fechaPrev' AND '$fechaNext' AND tipo = 1";
                                    $resdiasSol= mysqli_query( $conn, $QdiasSol ) or die (mysqli_error($conn));
                                    
                                    While ($rowSol = mysqli_fetch_array($resdiasSol)){
                                        $diasSol = $rowSol["diasSol"];
                                    }
                                    
                                    $anioAnt = $anio -2;;
                                    $fechaAnt = $anioAnt.$FechaIng;
                                    $fechaSig = $anioPrev.$FechaIng;
                                    $QdiasSolAnt ="SELECT IFNULL(SUM(dias), '0') as diasSol, (SELECT dias FROM diasvacaciones WHERE anio = $antiguedad-1) as vacLey
                                                    FROM solicitudes 
                                                    WHERE empleado = $noEmp AND (estatus = 2 && autorizaRH = 2) 
                                                    AND fesolicitud BETWEEN '$fechaAnt' AND '$fechaSig' AND tipo = 1";
                                    $resdiasSolAnt= mysqli_query( $conn, $QdiasSolAnt ) or die (mysqli_error($conn));
                                    While ($rowSolAnt = mysqli_fetch_array($resdiasSolAnt)){
                                        $diasSolAnt = $rowSolAnt["diasSol"];
                                        $vacLey = $rowSolAnt["vacLey"];
                                    }
                                }
                                $deuda = ($vacLey-$diasSolAnt)*(-1);
                                echo $diasSol;
                                
                                
                            ?>
                            
                            
                            días
                        </div>
                        <div class = "h5 mb-0 font-weight-bold text-gray-800">
                            Días disp: <?php echo $dias-$diasSol; echo ' días ';?>  
                            <input type="hidden" class="form-control" id="diasDisponibles" name="diasDisponibles" value="<?php echo $dias-$diasSol; ?>" readonly>
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
                                //echo '*Deuda: -'.$deuda . '*'; 
                            ?> 
                        </div>
                        <div class = "h5 mb-0 font-weight-bold text-gray-800">
                            Renovación Vac: <?php echo $fechaNext; ?>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>