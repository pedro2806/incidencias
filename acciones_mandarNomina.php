<?php
// Conexi贸n a la base de datos
include 'conn.php';

mysqli_set_charset($conn, "utf8");

$opcion = $_GET["opcion"];
    
    if($opcion == "llenaTablaPorPagar"){
        $sql = "SELECT s.id, s.empleado as noEmpleado, u.nombre, s.fesolicitud, s.feinicio, s.fefin, s.dias, s.notasempleado, s.notajefe, s.comentarios, s.tipo
                FROM solicitudes s 
                INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                WHERE s.pago = '' AND s.estatus = 2 AND s.autorizaRH = 2";
            
                 $res2 = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                
                // Crear un array para almacenar los resultados
                $registros = array();
                while ($row2 = mysqli_fetch_array($res2)) {

                    
                    $registros[] = array(
                        'id' => $row2["id"],
                        'noEmpleado' => $row2["noEmpleado"],
                        'nombre' => $row2["nombre"],
                        'fesolicitud' => $row2["fesolicitud"],
                        'feinicio' => $row2["feinicio"],
                        'fefin' => $row2["fefin"],
                        'dias' => $row2["dias"],
                        'notasempleado' => $row2["notasempleado"],
                        'notajefe' => $row2["notajefe"],
                        'comentarios' => $row2["comentarios"],
                        'tipo' => $row2["tipo"]
                    );
                }
                // Devolver los datos en formato JSON
                echo json_encode($registros);
                
            
    }
    
    if($opcion == "mandarNomina"){
     
        $sqlcambiofeSo = "UPDATE solicitudes s
                            SET s.pago = 'envioNomina'
                            WHERE s.pago = '' AND s.estatus = 2 AND s.autorizaRH = 2";
        $resultcambiofeSo = mysqli_query($conn, $sqlcambiofeSo);
        //echo $sqlcambiofeSo; 
        // Devolver los datos en formato JSON
        echo json_encode($resultcambiofeSo);     
        
    
    }
    
    if($opcion == "mandarPagado"){
     
        $sqlcambiofeSo = "UPDATE solicitudes s
                            SET s.pago = 'Si'
                            WHERE s.pago = 'envioNomina' AND s.estatus = 2 AND s.autorizaRH = 2";
        $resultcambiofeSo = mysqli_query($conn, $sqlcambiofeSo);
        //echo $sqlcambiofeSo; 
        // Devolver los datos en formato JSON
        echo json_encode($resultcambiofeSo);     
        
    
    }
    
    if($opcion == "llenaTablaEnNomina"){
        
        $sql = "SELECT s.id, s.empleado as noEmpleado, u.nombre, s.fesolicitud, s.feinicio, s.fefin, s.dias, s.notasempleado, s.notajefe, s.comentarios, s.tipo
                FROM solicitudes s 
                INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                WHERE s.pago = 'envioNomina' AND s.estatus = 2 AND s.autorizaRH = 2";
            
                 $res2 = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                
                // Crear un array para almacenar los resultados
                $registros = array();
                while ($row2 = mysqli_fetch_array($res2)) {
                    $registros[] = array(
                        'id' => $row2["id"],
                        'noEmpleado' => $row2["noEmpleado"],
                        'nombre' => $row2["nombre"],
                        'fesolicitud' => $row2["fesolicitud"],
                        'feinicio' => $row2["feinicio"],
                        'fefin' => $row2["fefin"],
                        'dias' => $row2["dias"],
                        'notasempleado' => $row2["notasempleado"],
                        'notajefe' => $row2["notajefe"],
                        'comentarios' => $row2["comentarios"],
                        'tipo' => $row2["tipo"]
                    );
                }
                // Devolver los datos en formato JSON
                echo json_encode($registros);
                
            
    } 
    
    if($opcion == "llenaTablaPagadas"){
        $sql = "SELECT s.id, s.empleado as noEmpleado, u.nombre, s.fesolicitud, s.feinicio, s.fefin, s.dias, s.notasempleado, s.notajefe, s.comentarios, s.tipo
                FROM solicitudes s 
                INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                WHERE s.pago = 'Si' AND s.estatus = 2 AND s.autorizaRH = 2";
            
                 $res2 = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                
                // Crear un array para almacenar los resultados
                $registros = array();
                while ($row2 = mysqli_fetch_array($res2)) {
                    $registros[] = array(
                        'id' => $row2["id"],
                        'noEmpleado' => $row2["noEmpleado"],
                        'nombre' => $row2["nombre"],
                        'fesolicitud' => $row2["fesolicitud"],
                        'feinicio' => $row2["feinicio"],
                        'fefin' => $row2["fefin"],
                        'dias' => $row2["dias"],
                        'notasempleado' => $row2["notasempleado"],
                        'notajefe' => $row2["notajefe"],
                        'comentarios' => $row2["comentarios"],
                        'tipo' => $row2["tipo"]
                    );
                }
                // Devolver los datos en formato JSON
                echo json_encode($registros);
                
            
    }    

?>
