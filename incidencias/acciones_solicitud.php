<?php
// Conexion a la base de datos
include '../conn.php';
mysqli_set_charset($conn, "utf8");
$noEmpleado_cookie = isset($_COOKIE['noEmpleado']) ? $_COOKIE['noEmpleado'] : null;
$opcion = $_POST["opcion"];
$noEmpleadoInc = isset($_POST["noEmpleadoInc"]) ? $_POST["noEmpleadoInc"] : $noEmpleado_cookie;
//FUNCION PARA MOSTRAR LOS EMPLEADOS
    if ($opcion == "empleados") {
        
        $sql = "SELECT * from usuarios WHERE estatus = 1 ORDER BY nombre";            
        $result = $conn->query($sql);
        
        $usuarios = array();
        
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = array(
                'nombre' => $row['nombre'],
                'noEmpleado' => $row['noEmpleado']            
            );
        }
        
        // Devolver los eventos en formato JSON
        
        echo json_encode($usuarios);
    }

//FUNCION PARA MOSTRAR LAS CLASIFICACIONES SEGUN EL TIPO
    if ($opcion == "clasficacion") {
        $tipo = $_POST["tipo"];
        $sql = "SELECT * from incidencias_clasificacion WHERE tipo = '$tipo' ORDER BY clasificacion";            
        
        $result = $conn->query($sql);
        
        $usuarios = array();
        
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = array(
                'id' => $row['id'],
                'clasificacion' => $row['clasificacion']            
            );
        }
        
        // Devolver los eventos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($usuarios);
    }

//FUNCION PARA GENERAR LA SOLICITUD
    if($opcion == "generarSolicitud"){
        $responsable = $_POST["responsable"];
        $clasificacion = $_POST["clasificacion"];
        $descripcion = $_POST["descripcion"];
        $fecha = date('Y-m-d H:i:s');
        $fechaIncidente = $_POST["fechaIncidente"];
        $fechaCierre = $_POST["fechaCierre"];
        $estatus = 'Abierta';
        $comentarios = $_POST["comentarios"];
        $tipo = $_POST["tipo"];
        $noEmpleado = $noEmpleado_cookie;

        $sqlInsert = "INSERT INTO incidencias_solicitudes(solicita, responsable, fecha_solicitud, fecha_incidente, fecha_estimada_cierre, comentarios_solicitud, comentarios_replica, comentarios_cierre, fecha_replica, tipo, clasificacion, estatus)
                                                    VALUES ('$noEmpleado', '$responsable', '$fecha', '$fechaIncidente', '$fechaCierre', '$comentarios', NULL, NULL, NULL, '$tipo', '$clasificacion', '$estatus')";
        echo $sqlInsert;
        if ($conn->query($sqlInsert) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Incidencia registrada con éxito.');
        } else {
            $response = array('status' => 'error', 'message' => 'Error al registrar la incidencia: ' . $conn->error);
        }
        
        // Devolver la respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }

//RESPONDER SOLICITUDES
    if($opcion == "responderSolicitud"){
        $idSolicitud = $_POST["idIncidencia"];
        $respuesta = $_POST["respuestaIncidencia"];
        $comentarios = $_POST["comentariosRespuesta"];
        $tipoSolicitud = $_POST["tipoSolicitud"];

        $fecha = date('Y-m-d H:i:s');

        $sqlUpdate = "";
        //Actualizar la solicitud dependiendo si es abierta o aceptada
        if($tipoSolicitud == "abierta"){
            $sqlUpdate = "UPDATE incidencias_solicitudes 
                        SET comentarios_replica = '$comentarios', fecha_replica = '$fecha', estatus = '$respuesta' 
                        WHERE id = $idSolicitud";    
        }
        else if($tipoSolicitud == "aceptada"){
            $sqlUpdate = "UPDATE incidencias_solicitudes 
                        SET comentarios_cierre = '$comentarios', fecha_cierre = '$fecha', estatus = '$respuesta' 
                        WHERE id = $idSolicitud";    
        }
        else if($tipoSolicitud == "enproceso"){
            $sqlUpdate = "UPDATE incidencias_solicitudes 
                        SET comentarios_cierre = '$comentarios', fecha_cierre = '$fecha', estatus = '$respuesta' 
                        WHERE id = $idSolicitud";    
        }
        
        
        //echo $sqlUpdate;
        if ($conn->query($sqlUpdate) === TRUE) {
            $response = array('status' => 'success', 'message' => 'Respuesta registrada con éxito.');
        } else {
            $response = array('status' => 'error', 'message' => 'Error al registrar la respuesta: ' . $conn->error);
        }
        
        // Devolver la respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }

//FUNCION PARA MOSTRAR LAS SOLICITUDES ABIERTAS 403 fer 521 hugo
    if($opcion == "solicitudesAbiertas"){
        if($noEmpleado_cookie == 403){
            $sql = "SELECT isol.*, u.nombre AS nombre_usuario, ur.nombre AS responsable, ic.clasificacion AS detalle_incidencia,
                    CASE
                        WHEN isol.solicita = $noEmpleado_cookie THEN 'Yosolicito'
                        WHEN u.jefe = $noEmpleado_cookie THEN 'SolicitaMiPersonal'
                        WHEN ur.jefe = $noEmpleado_cookie THEN 'ResponsableMiPersonal'
                        WHEN isol.responsable = $noEmpleado_cookie THEN 'SoyResponsable'
                        ELSE 'otro'
                    END AS solicita
                    FROM incidencias_solicitudes isol
                    INNER JOIN usuarios u ON isol.solicita = u.noEmpleado
                    INNER JOIN usuarios ur ON isol.responsable = ur.noEmpleado
                    INNER JOIN incidencias_clasificacion ic ON isol.clasificacion = ic.id
                    WHERE isol.estatus = 'Abierta' AND isol.tipo = 'Personal'                        
                    ORDER BY
                        isol.fecha_solicitud DESC"; 
        }else if($noEmpleado_cookie == 521){
            $sql = "SELECT isol.*, u.nombre AS nombre_usuario, ur.nombre AS responsable, ic.clasificacion AS detalle_incidencia,
                    CASE
                        WHEN isol.solicita = $noEmpleado_cookie THEN 'Yosolicito'
                        WHEN u.jefe = $noEmpleado_cookie THEN 'SolicitaMiPersonal'
                        WHEN ur.jefe = $noEmpleado_cookie THEN 'ResponsableMiPersonal'
                        WHEN isol.responsable = $noEmpleado_cookie THEN 'SoyResponsable'
                        ELSE 'otro'
                    END AS solicita
                    FROM incidencias_solicitudes isol
                    INNER JOIN usuarios u ON isol.solicita = u.noEmpleado
                    INNER JOIN usuarios ur ON isol.responsable = ur.noEmpleado
                    INNER JOIN incidencias_clasificacion ic ON isol.clasificacion = ic.id
                    WHERE isol.estatus = 'Abierta'
                        AND (
                            isol.solicita = $noEmpleado_cookie 
                            OR u.jefe = $noEmpleado_cookie
                            OR ur.jefe = $noEmpleado_cookie
                            OR isol.responsable = $noEmpleado_cookie
                        )
                    ORDER BY
                        isol.fecha_solicitud DESC";
        }else{
            $sql = "SELECT isol.*, u.nombre AS nombre_usuario, ur.nombre AS responsable,
                    CASE
                        WHEN isol.solicita = $noEmpleado_cookie THEN 'Yosolicito'
                        WHEN u.jefe = $noEmpleado_cookie THEN 'SolicitaMiPersonal'
                        WHEN ur.jefe = $noEmpleado_cookie THEN 'ResponsableMiPersonal'
                        WHEN isol.responsable = $noEmpleado_cookie THEN 'SoyResponsable'
                        ELSE 'otro'
                    END AS solicita,
                    ic.clasificacion AS detalle_incidencia 
                    FROM incidencias_solicitudes isol 
                    INNER JOIN usuarios u ON isol.solicita = u.noEmpleado
                    INNER JOIN usuarios ur ON isol.responsable = ur.noEmpleado
                    INNER JOIN incidencias_clasificacion ic ON isol.clasificacion = ic.id 
                    WHERE isol.estatus = 'Abierta' 
                        AND (
                            isol.solicita = $noEmpleado_cookie 
                            OR u.jefe = $noEmpleado_cookie
                            OR ur.jefe = $noEmpleado_cookie
                            OR isol.responsable = $noEmpleado_cookie
                        )
                    ORDER BY isol.fecha_solicitud DESC";
        }

        //echo $sql;
        $result = $conn->query($sql);
        
        $solicitudes = array();
        
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }
        
        // Devolver los eventos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($solicitudes);
    }

//FUNCION PARA MOSTRAR LAS SOLICITUDES ACEPTADAS    
    if($opcion == "SolicitudesAceptadas"){
        $sql = "SELECT isol.*, u.nombre AS nombre_usuario, ur.nombre AS responsable, ic.clasificacion AS detalle_incidencia,
                CASE
                    WHEN isol.solicita = $noEmpleado_cookie THEN 'Yosolicito'
                    WHEN u.jefe = $noEmpleado_cookie THEN 'SolicitaMiPersonal'
                    WHEN ur.jefe = $noEmpleado_cookie THEN 'ResponsableMiPersonal'
                    WHEN isol.responsable = $noEmpleado_cookie THEN 'SoyResponsable'
                    ELSE 'otro'
                END AS solicita
                FROM incidencias_solicitudes isol
                INNER JOIN usuarios u ON isol.solicita = u.noEmpleado
                INNER JOIN usuarios ur ON isol.responsable = ur.noEmpleado
                INNER JOIN incidencias_clasificacion ic ON isol.clasificacion = ic.id
                WHERE isol.estatus = 'Aceptada'
                    AND (
                        isol.solicita = $noEmpleado_cookie 
                        OR u.jefe = $noEmpleado_cookie
                        OR ur.jefe = $noEmpleado_cookie
                        OR isol.responsable = $noEmpleado_cookie

                    )
                ORDER BY
                    isol.fecha_solicitud DESC";
        //echo $sql;
        $result = $conn->query($sql);
        
        $solicitudes = array();
        
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = array(
                'id_solicitud' => $row['id'],
                'solicita' => $row['solicita'],
                'responsable' => $row['responsable'],
                'fecha_solicitud' => $row['fecha_solicitud'],
                'fecha_incidente' => $row['fecha_incidente'],
                'fecha_cierre' => $row['fecha_cierre'],
                'comentarios_solicitud' => $row['comentarios_solicitud'],
                'comentarios_replica' => $row['comentarios_replica'],
                'comentarios_cierre' => $row['comentarios_cierre'],
                'fecha_replica' => $row['fecha_replica'],
                'tipo' => $row['tipo'],
                'clasificacion' => $row['clasificacion'],
                'estatus' => $row['estatus'],
                'nombre_usuario' => $row['nombre_usuario'],
                'detalle_incidencia' => $row['detalle_incidencia']);
        }
        
        // Devolver los eventos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($solicitudes);
    }

//FUNCION PARA MOSTRAR LAS SOLICITUDES EN PROCESO
    if($opcion == "SolicitudesEnProceso"){
        $sql = "SELECT isol.*, u.nombre AS nombre_usuario, ur.nombre AS responsable, ic.clasificacion AS detalle_incidencia 
                FROM incidencias_solicitudes isol 
                INNER JOIN usuarios u ON isol.solicita = u.noEmpleado
                INNER JOIN usuarios ur ON isol.responsable = ur.noEmpleado
                INNER JOIN incidencias_clasificacion ic ON isol.clasificacion = ic.id 
                WHERE isol.estatus = 'EnProceso' ORDER BY isol.fecha_solicitud DESC";
        //echo $sql;
        $result = $conn->query($sql);
        
        $solicitudes = array();
        
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = array(
                'id_solicitud' => $row['id'],
                'solicita' => $row['solicita'],
                'responsable' => $row['responsable'],
                'fecha_solicitud' => $row['fecha_solicitud'],
                'fecha_incidente' => $row['fecha_incidente'],
                'fecha_cierre' => $row['fecha_cierre'],
                'comentarios_solicitud' => $row['comentarios_solicitud'],
                'comentarios_replica' => $row['comentarios_replica'],
                'comentarios_cierre' => $row['comentarios_cierre'],
                'fecha_replica' => $row['fecha_replica'],
                'tipo' => $row['tipo'],
                'clasificacion' => $row['clasificacion'],
                'estatus' => $row['estatus'],
                'nombre_usuario' => $row['nombre_usuario'],
                'detalle_incidencia' => $row['detalle_incidencia']);
        }
        
        // Devolver los eventos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($solicitudes);
    }

//FUNCION PARA MOSTRAR LAS SOLICITUDES CERRADAS
    if($opcion == "SolicitudesCerradas"){
        $sql = "SELECT isol.*, u.nombre AS nombre_usuario, ur.nombre AS responsable, ic.clasificacion AS detalle_incidencia,
                CASE
                    WHEN isol.solicita = $noEmpleado_cookie THEN 'Yosolicito'
                    WHEN u.jefe = $noEmpleado_cookie THEN 'SolicitaMiPersonal'
                    WHEN ur.jefe = $noEmpleado_cookie THEN 'ResponsableMiPersonal'
                    WHEN isol.responsable = $noEmpleado_cookie THEN 'SoyResponsable'
                    ELSE 'otro'
                END AS solicita
                FROM incidencias_solicitudes isol
                INNER JOIN usuarios u ON isol.solicita = u.noEmpleado
                INNER JOIN usuarios ur ON isol.responsable = ur.noEmpleado
                INNER JOIN incidencias_clasificacion ic ON isol.clasificacion = ic.id
                WHERE isol.estatus = 'Cerrada'
                    AND (
                        isol.solicita = $noEmpleado_cookie 
                        OR u.jefe = $noEmpleado_cookie
                        OR ur.jefe = $noEmpleado_cookie
                        OR isol.responsable = $noEmpleado_cookie

                    )
                ORDER BY
                    isol.fecha_solicitud DESC";
        //echo $sql;
        $result = $conn->query($sql);
        
        $solicitudes = array();
        
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = array(
                'id_solicitud' => $row['id'],
                'solicita' => $row['solicita'],
                'responsable' => $row['responsable'],
                'fecha_solicitud' => $row['fecha_solicitud'],
                'fecha_incidente' => $row['fecha_incidente'],
                'fecha_cierre' => $row['fecha_cierre'],
                'comentarios_solicitud' => $row['comentarios_solicitud'],
                'comentarios_replica' => $row['comentarios_replica'],
                'comentarios_cierre' => $row['comentarios_cierre'],
                'fecha_replica' => $row['fecha_replica'],
                'tipo' => $row['tipo'],
                'clasificacion' => $row['clasificacion'],
                'estatus' => $row['estatus'],
                'nombre_usuario' => $row['nombre_usuario'],
                'detalle_incidencia' => $row['detalle_incidencia']);
        }
        
        // Devolver los eventos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($solicitudes);
    }

    //FUNCION PARA MOSTRAR LAS SOLICITUDES RECHAZADAS
    if($opcion == "SolicitudesRechazadas"){

        $sql = "SELECT isol.*, u.nombre AS nombre_usuario, ur.nombre AS responsable, ic.clasificacion AS detalle_incidencia 
                FROM incidencias_solicitudes isol 
                INNER JOIN usuarios u ON isol.solicita = u.noEmpleado
                INNER JOIN usuarios ur ON isol.responsable = ur.noEmpleado
                INNER JOIN incidencias_clasificacion ic ON isol.clasificacion = ic.id 
                WHERE isol.estatus = 'Rechazada' ORDER BY isol.fecha_solicitud DESC";
        //echo $sql;
        $result = $conn->query($sql);
        
        $solicitudes = array();
        
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = array(
                'id_solicitud' => $row['id'],
                'solicita' => $row['solicita'],
                'responsable' => $row['responsable'],
                'fecha_solicitud' => $row['fecha_solicitud'],
                'fecha_incidente' => $row['fecha_incidente'],
                'fecha_cierre' => $row['fecha_cierre'],
                'comentarios_solicitud' => $row['comentarios_solicitud'],
                'comentarios_replica' => $row['comentarios_replica'],
                'comentarios_cierre' => $row['comentarios_cierre'],
                'fecha_replica' => $row['fecha_replica'],
                'tipo' => $row['tipo'],
                'clasificacion' => $row['clasificacion'],
                'estatus' => $row['estatus'],
                'nombre_usuario' => $row['nombre_usuario'],
                'detalle_incidencia' => $row['detalle_incidencia']);
        }
        
        // Devolver los eventos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($solicitudes);
    }

    if($opcion == "areaRegion"){
        $sql = "SELECT departamento.departamento as area, region.region FROM usuarios 
                INNER JOIN departamento ON usuarios.departamento = departamento.id
                INNER JOIN region ON usuarios.region = region.id
                WHERE noEmpleado  = $noEmpleadoInc";
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();

        header('Content-Type: application/json');
        echo json_encode($data);
    }


?>  