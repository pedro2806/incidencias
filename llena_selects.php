<?php
    include 'conn.php';
    
    $opcion = $_GET["opcion"];
    $idEmpleado = $_GET["idEmpleado"];
    
    //PUESTOS
    if($opcion == "puesto"){
        // Consulta a la base de datos
        $Qpuesto = "SELECT id, puesto from puesto";
        $res2 = mysqli_query($conn, $Qpuesto) or die(mysqli_error($conn));
        
        // Crear un array para almacenar los resultados
        $puestos = array();
        while ($row2 = mysqli_fetch_array($res2)) {
            $puestos[] = array(
                'id' => ($row2["id"]),
                'puestos' => ($row2["puesto"])
            );
        }
        echo json_encode($puestos);
    }

    //DEPARTAMENTO
    if($opcion == "departamento"){
        // Consulta a la base de datos
        $Qdepartamento = "SELECT id, departamento from departamento";
        $res2 = mysqli_query($conn, $Qdepartamento) or die(mysqli_error($conn));
        
        // Crear un array para almacenar los resultados
        $departamentos = array();
        while ($row2 = mysqli_fetch_array($res2)) {
            $departamentos[] = array(
                'id' => ($row2["id"]),
                'departamento' => ($row2["departamento"])
            );
        }
        echo json_encode($departamentos);
    }
    
    // REGIONES
    if ($opcion == "region") {
        $Qregion = "SELECT id, region FROM region";
        $res3 = mysqli_query($conn, $Qregion) or die(mysqli_error($conn));
    
        $regiones = array();
        while ($row3 = mysqli_fetch_array($res3)) {
            $regiones[] = array(
                'id' => $row3["id"],
                'region' => $row3["region"]
            );
        }
        echo json_encode($regiones);
    }
    
    // JEFES
    if ($opcion == "jefe") {
        $Qjefe = "SELECT noEmpleado, nombre FROM usuarios WHERE rol = 3 AND estatus = 1";
        
        $res4 = mysqli_query($conn, $Qjefe) or die(mysqli_error($conn));
    
        $jefes = array();
        while ($row4 = mysqli_fetch_array($res4)) {
            $jefes[] = array(
                'id' => $row4["noEmpleado"],
                'nombre' => $row4["nombre"]
            );
        }
        echo json_encode($jefes);
    }
    
    // DATOS USUARIO
    if ($opcion == "datosUsuario") {
        
        $QUsuario = "SELECT u.usuario, u.noEmpleado, u.nombre, u.correo, u.puesto as idPuesto,
                            p.puesto, u.region as idRegion, r.region, u.departamento as idDepartamento, d.id as departamento,
                            u.jefe as idJefe, u.jefe, u.rol, u.fechaIngreso, p.id as id_puesto, r.id as id_region
                        FROM usuarios u
                        INNER JOIN puesto p ON u.puesto = p.id
                        INNER JOIN region r ON u.region = r.id
                        INNER JOIN departamento d ON u.departamento = d.id 
                        where u.id = $idEmpleado";
        
        $resQUsuario = mysqli_query($conn, $QUsuario) or die(mysqli_error($conn));
    
        $dUsuario = array();
        while ($rowQUsuario = mysqli_fetch_array($resQUsuario)) {
            $dUsuario[] = array(
                
                'id' => $rowQUsuario["id"],
                'noEmpleado' => $rowQUsuario["noEmpleado"],
                'nombre' => $rowQUsuario["nombre"],
                'correo' => $rowQUsuario["correo"],
                'puesto' => $rowQUsuario["puesto"],
                'id_puesto' => $rowQUsuario["id_puesto"],
                'region' => $rowQUsuario["region"],
                'id_region' => $rowQUsuario["id_region"],
                'fechaIngreso' => $rowQUsuario["fechaIngreso"],
                'departamento' => $rowQUsuario["departamento"],
                'estatus' => $rowQUsuario["estatus"],
                'antiguedad' => $rowQUsuario["antiguedad"],
                'rol' => $rowQUsuario["rol"],
                'jefe' => $rowQUsuario["jefe"]
            );
        }
    
        echo json_encode($dUsuario);
    }
    
?>