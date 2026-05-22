<?php
header('Content-Type: application/json');
include 'conn.php';

$accion = $_POST["accion"];

$noEmpleado = $_POST["noEmpleado"];
$correo = $_POST["correo"];

//Validar Sistemas x Usuario
    if($accion == "ValidarOpciones"){
        $sql = "SELECT * FROM accesos WHERE noEmpleado = $noEmpleado";

        $result = $conn->query($sql);
        
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

//MODIFICAR Usuario 
    if($accion == "getInfo") {
        
        $sql = "SELECT TIMESTAMPDIFF(YEAR, u.fechaIngreso, CURDATE()) AS antiguedad,
                d.departamento AS departamento,
                p.puesto AS puesto,
                j.nombre AS jefe,
                u.foto AS foto,
                COALESCE((
                    SELECT dv.dias
                    FROM diasvacaciones dv
                    WHERE dv.anio = TIMESTAMPDIFF(YEAR, u.fechaIngreso, CURDATE())
                    LIMIT 1
                ), 0) AS diasdisponibles,
                u.fechaIngreso,
                IFNULL(SUM(
                    CASE
                        WHEN s.estatus = 2 AND s.autorizaRH = 2 AND s.tipo = 1
                        THEN s.dias ELSE 0
                    END
                ), 0) AS diasSol
                FROM usuarios u
                LEFT JOIN usuarios j ON u.jefe = j.noEmpleado
                LEFT JOIN departamento d ON u.departamento = d.id
                LEFT JOIN puesto p ON u.puesto = p.id
                LEFT JOIN solicitudes s
                    ON u.noEmpleado = s.empleado
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
                WHERE u.noEmpleado = $noEmpleado
                GROUP BY u.noEmpleado";

        $result = $conn->query($sql);
        //echo $sql;
        if ($result && $result->num_rows > 0) {
            $info= [];
            while ($row = $result->fetch_assoc()) {
                $info[] = $row;
            }
            echo json_encode(['status' => 'success', 'info' => $info]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron actividades planeadas o error en la consulta.']);
        }
    }

// Listado de empleados para el Directorio
    if ($accion == 'listarEmpleados') {
        // u.nave se devuelve crudo (número); cuando exista la tabla `nave`
        // habrá que hacer LEFT JOIN nave n ON u.nave = n.id y devolver n.nombre.
        // Teléfonos: tabla `telefono` 1:N (un empleado puede tener varios).
        // Se concatenan todos en un solo string ya formateado, sin filtrar tipo.
        $sql = "SELECT u.noEmpleado,
                       COALESCE(
                           NULLIF(TRIM(CONCAT_WS(' ', u.nombres, u.apellidos)), ''),
                           u.nombre
                       ) AS nombre,
                       u.foto,
                       u.correo,
                       d.departamento AS area,
                       p.puesto       AS puesto,
                       u.nave         AS nave,
                       tel.telefono   AS telefono
                FROM usuarios u
                LEFT JOIN departamento d ON u.departamento = d.id
                LEFT JOIN puesto p       ON u.puesto       = p.id
                LEFT JOIN (
                    SELECT t.noEmpleado,
                           GROUP_CONCAT(
                               CONCAT(
                                   TRIM(t.telefono),
                                   IF(t.extension IS NOT NULL AND t.extension <> '',
                                      CONCAT(' ext. ', t.extension), '')
                               )
                               ORDER BY t.idTelefono
                               SEPARATOR ' / '
                           ) AS telefono
                    FROM telefono t
                    WHERE t.noEmpleado > 0
                      AND t.telefono IS NOT NULL
                      AND t.telefono <> ''
                    GROUP BY t.noEmpleado
                ) tel ON tel.noEmpleado = u.noEmpleado
                WHERE u.estatus = 1
                ORDER BY nombre ASC";

        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $info = [];
            while ($row = $result->fetch_assoc()) { $info[] = $row; }
            echo json_encode(['status' => 'success', 'info' => $info]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron empleados activos.']);
        }
    }

// Obtener id_usuario y placa
    if ($accion == 'getPlaca') {
        $noEmpleado = isset($_POST['noEmpleado']) ? $_POST['noEmpleado'] : '';

        if (empty($noEmpleado)) {
            echo json_encode(['success' => false, 'message' => 'noEmpleado no recibido.']);
            exit;
        }

        // 1. Obtener id_usuario desde la tabla usuarios
        $sqlUsuario = "SELECT id_usuario FROM usuarios WHERE noEmpleado = $noEmpleado LIMIT 1";
        $resultUsuario = $conn->query($sqlUsuario);

        if ($resultUsuario && $resultUsuario->num_rows > 0) {
            $rowUsuario = $resultUsuario->fetch_assoc();
            $id_usuario = $rowUsuario['id_usuario'];

            // 2. Obtener placa desde la tabla inventario usando id_usuario
            $sqlPlaca = "SELECT placa FROM mess_control_vehicular.inventario WHERE id_usuario = $id_usuario ORDER BY id_vehiculo DESC LIMIT 1";
            $resultPlaca = $conn->query($sqlPlaca);

            if ($resultPlaca && $resultPlaca->num_rows > 0) {
                $rowPlaca = $resultPlaca->fetch_assoc();
                echo json_encode(['success' => true, 'placa' => $rowPlaca['placa']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se encontró placa para este usuario.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró id_usuario para este noEmpleado.']);
        }
    }
?>