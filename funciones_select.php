<?php
// Conexi贸n a la base de datos
include 'conn.php';

mysqli_set_charset($conn, "utf8");

$opcion = $_GET["opcion"];
$rol = $_GET["cookieRol"];
$usuario = $_GET["cookieNoEmpleado"];

$fesolicitud = $_POST["fsolicitud"];
$noEmpleado = $_POST["noEmpleado"];
$idSolicitud = $_POST["id"];
$opcion2 = $_POST["opcion"];
$Dgozados = $_POST["Dgozados"];

    if($opcion == "empleadoSolicita"){
        // Consulta a la base de datos
        if($rol == 1){
            $QUmarcas = "SELECT * FROM usuarios WHERE estatus = 1 order by nombre";
        }
        if($rol == 2){
            $QUmarcas = "SELECT * FROM usuarios";
        }
        if($rol == 3){
            $QUmarcas = "(SELECT u.noEmpleado, u.nombre FROM usuarios u
                        WHERE u.jefe = $usuario AND u.estatus = 1 order by u.nombre)
                        UNION
                        (SELECT noEmpleado, nombre FROM usuarios WHERE noEmpleado = $usuario)";
        }
        $res2 = mysqli_query($conn, $QUmarcas) or die(mysqli_error($conn));
        
        // Crear un array para almacenar los resultados
        $usuarios = array();
        while ($row2 = mysqli_fetch_array($res2)) {
            $usuarios[] = array(
                'noEmpleado' => $row2["noEmpleado"],
                'nombre' => $row2["nombre"]
            );
        }
        
        // Devolver los datos en formato JSON
        echo json_encode($usuarios);
    }
    
    if($opcion == "llenaTablaAutorizadas"){
        
        $sql = "SELECT s.id, s.tipo, DATE_FORMAT(s.feinicio, '%d/%m/%Y') as feinicio, DATE_FORMAT(s.fefin, '%d/%m/%Y') as fefin, s.notasempleado, s.notajefe, s.comentarios, s.estatus, s.dias, s.autorizaRH, s.Dgozados, s.empleado as noEmp, u.nombre as empleado,s.fesolicitud,DATE_FORMAT(s.fesolicitud, '%Y-%m-%d') AS FechaBien
                FROM solicitudes s  
                INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                WHERE s.estatus = 2 AND s.autorizaRH = 2
                ORDER BY s.fesolicitud DESC";
            
                 $res2 = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                
                // Crear un array para almacenar los resultados
                $registros = array();
                while ($row2 = mysqli_fetch_array($res2)) {
                    $registros[] = array(
                        'id' => $row2["id"],
                        'tSolicitud' => $row2["tipo"],
                        'Finicio' => $row2["feinicio"],
                        'FFin' => $row2["fefin"],
                        'fSolicitud' => $row2["fesolicitud"],
                        'ComentariosE' => $row2["notasempleado"],
                        'ComentariosJ' => $row2["notajefe"],
                        'Comentarios' => $row2["comentarios"],
                        'Estatus' => $row2["estatus"],
                        'usuario' => $row2["empleado"],
                        'noEmpleado' => $row2["noEmp"],
                        'noDias' => $row2["dias"],
                        'autorizaRH' => $row2["autorizaRH"],
                        'FechaBien' => $row2["FechaBien"],
                        'Dgozados' => $row2["Dgozados"]
                    );
                }
                // Devolver los datos en formato JSON
                echo json_encode($registros);
                
            
    }    
    if($opcion == "llenaTablaPorAutorizar"){
        $sql = "SELECT s.id, s.tipo, DATE_FORMAT(s.feinicio, '%d/%m/%Y') as feinicio, DATE_FORMAT(s.fefin, '%d/%m/%Y') as fefin, s.notasempleado, s.notajefe, s.comentarios, s.estatus, s.dias, s.autorizaRH, s.Dgozados, s.empleado as noEmp, u.nombre as empleado,s.fesolicitud,DATE_FORMAT(s.fesolicitud, '%Y-%m-%d') AS FechaBien,
                (SELECT dias FROM diasvacaciones WHERE anio =(TIMESTAMPDIFF(YEAR,u.fechaIngreso,CURDATE()) ) )AS diasD
                FROM solicitudes s 
                INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                WHERE (s.estatus = 1 AND s.autorizaRH = 1) OR (s.estatus = 1 AND s.autorizaRH = 2) OR (s.estatus = 2 AND s.autorizaRH = 1) order by fesolicitud DESC";
            
                 $res2 = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                
                // Crear un array para almacenar los resultados
                $registros = array();
                while ($row2 = mysqli_fetch_array($res2)) {
                    
                        $FechaIng = substr($row2["fechaIngreso"], 4, 6);
                        $anio = date("Y");
                        $fechaCompara = $anio.$FechaIng;
                        $hoy = date("Y-m-d");
                        $noEmp = $row2["noEmp"];
                        if ($fechaCompara <= $hoy){
                            $anioNext = $anio + 1;
                            $fechaPrev = $anio.$FechaIng;
                            $fechaNext = $anioNext.$FechaIng;
                            $QdiasSol = "SELECT IFNULL(SUM(dias), '0') as diasSol FROM solicitudes WHERE empleado = $noEmp AND (estatus = 2 && autorizaRH = 2) AND fesolicitud BETWEEN '$fechaPrev' AND '$fechaNext'";
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
                    
                    $registros[] = array(
                        'id' => $row2["id"],
                        'tSolicitud' => $row2["tipo"],
                        'Finicio' => $row2["feinicio"],
                        'FFin' => $row2["fefin"],
                        'fSolicitud' => $row2["fesolicitud"],
                        'ComentariosE' => $row2["notasempleado"],
                        'ComentariosJ' => $row2["notajefe"],
                        'Comentarios' => $row2["comentarios"],
                        'Estatus' => $row2["estatus"],
                        'usuario' => $row2["empleado"],
                        'noEmpleado' => $row2["noEmp"],
                        'noDias' => $row2["dias"],
                        'autorizaRH' => $row2["autorizaRH"],
                        'diasDisp' => $row2["diasD"] - $diasSol
                    );
                }
                // Devolver los datos en formato JSON
                echo json_encode($registros);
                
            
    }    
    if($opcion == "llenaTablaCanceladas"){
        $sql = "SELECT s.*, s.empleado as noEmp, u.nombre as empleado,s.fesolicitud
                FROM solicitudes s 
                INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                WHERE s.estatus = 3 OR s.autorizaRH = 3";
            
                 $res2 = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                
                // Crear un array para almacenar los resultados
                $registros = array();
                while ($row2 = mysqli_fetch_array($res2)) {
                    $registros[] = array(
                        'id' => $row2["id"],
                        'tSolicitud' => $row2["tipo"],
                        'Finicio' => $row2["feinicio"],
                        'FFin' => $row2["fefin"],
                        'fSolicitud' => $row2["fesolicitud"],
                        'ComentariosE' => $row2["notasempleado"],
                        'ComentariosJ' => $row2["notajefe"],
                        'Comentarios' => $row2["comentarios"],
                        'Estatus' => $row2["estatus"],
                        'usuario' => $row2["empleado"],
                        'noEmpleado' => $row2["noEmp"],
                        'noDias' => $row2["dias"]
                    );
                }
                // Devolver los datos en formato JSON
                echo json_encode($registros);
                
            
    }    
    
    if($opcion2 == "feSolicitudEdit") {
    
        $sqlcambiofeSo = "UPDATE solicitudes 
                          SET fesolicitud = '$fesolicitud', Dgozados = $Dgozados
                          WHERE empleado='$noEmpleado' AND id = $idSolicitud";
        $resultcambiofeSo = mysqli_query($conn, $sqlcambiofeSo);
        //echo $sqlcambiofeSo; 
        // Devolver los datos en formato JSON
        echo json_encode($resultcambiofeSo);     
        
    }
?>
