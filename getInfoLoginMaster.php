<?php
header('Content-Type: application/json');
include 'conn.php';

$accion = $_POST["accion"];

$noEmpleado = $_POST["noEmpleado"];
$correo = $_POST["correo"];


//MODIFICAR Usuario 
    if($accion == "getInfo") {
        
        $sql = "SELECT 
                    TIMESTAMPDIFF(YEAR, u.fechaIngreso, CURDATE()) AS antiguedad,
                    d.departamento,
                    j.nombre AS jefe,
                    (
                        SELECT dv.dias 
                        FROM diasvacaciones dv 
                        WHERE dv.anio = TIMESTAMPDIFF(YEAR, u.fechaIngreso, CURDATE())
                        LIMIT 1
                    ) AS diasdisponibles,
                    u.fechaIngreso,
                    IFNULL(SUM(s.dias), 0) AS diasSol
                FROM usuarios u
                INNER JOIN usuarios j ON u.jefe = j.noEmpleado
                INNER JOIN departamento d ON u.departamento = d.id
                INNER JOIN solicitudes s ON u.noEmpleado = s.empleado
                WHERE 
                    u.noEmpleado = $noEmpleado
                    AND s.estatus = 2
                    AND s.autorizaRH = 2
                    AND s.tipo = 1
                    AND s.fesolicitud BETWEEN
                        (CASE
                            WHEN MAKEDATE(YEAR(CURDATE()), DAYOFYEAR(u.fechaIngreso)) > CURDATE()
                            THEN MAKEDATE(YEAR(CURDATE()) - 1, DAYOFYEAR(u.fechaIngreso))
                            ELSE MAKEDATE(YEAR(CURDATE()), DAYOFYEAR(u.fechaIngreso))
                        END)
                    AND
                        (CASE
                            WHEN MAKEDATE(YEAR(CURDATE()), DAYOFYEAR(u.fechaIngreso)) > CURDATE()
                            THEN MAKEDATE(YEAR(CURDATE()), DAYOFYEAR(u.fechaIngreso))
                            ELSE MAKEDATE(YEAR(CURDATE()) + 1, DAYOFYEAR(u.fechaIngreso))
                        END)
                GROUP BY 
                    antiguedad, d.departamento, j.nombre, diasdisponibles, u.fechaIngreso";
    
        $result = $conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            $info= [];
            while ($row = $result->fetch_assoc()) {
                $info[] = $row;
            }
            //echo json_encode(['status' => 'success', 'info' => $info]);
            echo json_encode($info);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron actividades planeadas o error en la consulta.']);
        }
    }
    
    if($accion == "ValidarOpciones"){
        $sql = "SELECT noEmpleado, sistema, estatus FROM accesos WHERE noEmpleado = $noEmpleado";
    
        $result = $conn->query($sql);
        //echo $sql;
        if ($result && $result->num_rows > 0) {
            $info= [];
            while ($row = $result->fetch_assoc()) {
                $info[] = $row;
            }
            echo json_encode($info);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron coincidencias.']);
        }
    }
    
?>