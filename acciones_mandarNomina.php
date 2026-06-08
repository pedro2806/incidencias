<?php
// Backend dedicado de "Control pagos nómina" (mandarNomina_v2.php) - vista RH.
// Conexión a la base de datos
include 'conn.php';

header('Content-Type: application/json; charset=utf-8');
mysqli_set_charset($conn, "utf8");

// Autorización server-side: el noEmpleado se resuelve por cookie, NO se confía
// en lo que mande el cliente por POST/GET. El permiso se valida contra la tabla
// accesos_especiales por el bloque completo de Administración (opcion
// 'verAdministracion'), mismo flujo que el menú y restarDias.
$noEmpleado = isset($_COOKIE['noEmpleado']) ? (int) $_COOKIE['noEmpleado'] : 0;
$stmtAcc = $conn->prepare("SELECT id FROM accesos_especiales WHERE noEmpleado = ? AND sistema = 'incidencias' AND opcion = 'verAdministracion' AND estatus = 1 LIMIT 1");
$stmtAcc->bind_param("i", $noEmpleado);
$stmtAcc->execute();
if ($stmtAcc->get_result()->num_rows === 0) {
    $stmtAcc->close();
    http_response_code(403);
    echo json_encode(array('error' => 'No autorizado'));
    exit;
}
$stmtAcc->close();

$opcion = isset($_GET["opcion"]) ? $_GET["opcion"] : '';

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

    // Listas con paginación SERVER-SIDE (DataTables): solo devuelve la página
    // visible. Se usa en mandarNomina_v2 para las 3 pestañas. El WHERE se elige
    // por whitelist (no se interpola nada del cliente en el SQL).
    if($opcion == "listaServerSide"){

        $listasWhere = array(
            'porPagar' => "s.pago = '' AND s.estatus = 2 AND s.autorizaRH = 2",
            'enNomina' => "s.pago = 'envioNomina' AND s.estatus = 2 AND s.autorizaRH = 2",
            'pagadas'  => "s.pago = 'Si' AND s.estatus = 2 AND s.autorizaRH = 2",
        );
        $lista = isset($_GET['lista']) ? $_GET['lista'] : '';
        if (!isset($listasWhere[$lista])) {
            echo json_encode(array('draw'=>0,'recordsTotal'=>0,'recordsFiltered'=>0,'data'=>array()));
            exit;
        }
        $baseWhere = $listasWhere[$lista];

        $draw   = isset($_GET['draw'])   ? (int) $_GET['draw']   : 0;
        $start  = isset($_GET['start'])  ? (int) $_GET['start']  : 0;
        $length = isset($_GET['length']) ? (int) $_GET['length'] : 10;
        $search = isset($_GET['search']['value']) ? trim($_GET['search']['value']) : '';

        // Total sin filtro
        $recordsTotal = 0;
        if ($rT = mysqli_query($conn, "SELECT COUNT(*) c FROM solicitudes s INNER JOIN usuarios u ON s.empleado = u.noEmpleado WHERE $baseWhere")) {
            $recordsTotal = (int) mysqli_fetch_assoc($rT)['c'];
        }

        // Búsqueda global (columnas de texto)
        $searchSql = '';
        $params = array();
        $types = '';
        if ($search !== '') {
            $searchSql = " AND (u.nombre LIKE ? OR s.empleado LIKE ? OR s.fesolicitud LIKE ? OR s.feinicio LIKE ? OR s.fefin LIKE ? OR s.comentarios LIKE ?)";
            $like = '%' . $search . '%';
            for ($i = 0; $i < 6; $i++) { $params[] = $like; $types .= 's'; }
        }

        // Total con filtro
        $recordsFiltered = $recordsTotal;
        if ($search !== '') {
            $stmtF = $conn->prepare("SELECT COUNT(*) c FROM solicitudes s INNER JOIN usuarios u ON s.empleado = u.noEmpleado WHERE $baseWhere $searchSql");
            $stmtF->bind_param($types, ...$params);
            $stmtF->execute();
            $recordsFiltered = (int) $stmtF->get_result()->fetch_assoc()['c'];
            $stmtF->close();
        }

        // Orden (whitelist por índice de columna; evita SQLi en ORDER BY)
        $cols = array(0=>'s.id',1=>'s.empleado',2=>'u.nombre',3=>'s.fesolicitud',4=>'s.feinicio',5=>'s.fefin',6=>'s.dias',7=>'s.notasempleado',8=>'s.notajefe',9=>'s.comentarios',10=>'s.tipo');
        $orderColIdx = isset($_GET['order'][0]['column']) ? (int) $_GET['order'][0]['column'] : 0;
        $orderBy     = isset($cols[$orderColIdx]) ? $cols[$orderColIdx] : 's.id';
        $orderDir    = (isset($_GET['order'][0]['dir']) && strtolower($_GET['order'][0]['dir']) === 'asc') ? 'ASC' : 'DESC';

        // Página
        $sqlData = "SELECT s.id, s.empleado AS noEmpleado, u.nombre, s.fesolicitud, s.feinicio, s.fefin, s.dias, s.notasempleado, s.notajefe, s.comentarios, s.tipo
                    FROM solicitudes s INNER JOIN usuarios u ON s.empleado = u.noEmpleado
                    WHERE $baseWhere $searchSql
                    ORDER BY $orderBy $orderDir";
        $typesData = $types;
        $paramsData = $params;
        if ($length != -1) {
            $sqlData .= " LIMIT ?, ?";
            $typesData .= 'ii';
            $paramsData[] = $start;
            $paramsData[] = $length;
        }

        $stmt = $conn->prepare($sqlData);
        if ($typesData !== '') {
            $stmt->bind_param($typesData, ...$paramsData);
        }
        $stmt->execute();
        $res = $stmt->get_result();

        $data = array();
        while ($row = $res->fetch_assoc()) {
            $tipoTxt = $row['tipo'] == 1 ? 'Vacaciones'
                     : ($row['tipo'] == 2 ? 'Permiso sin goce'
                     : ($row['tipo'] == 3 ? 'Permiso con goce' : ''));
            $data[] = array(
                $row['id'], $row['noEmpleado'], $row['nombre'], $row['fesolicitud'],
                $row['feinicio'], $row['fefin'], $row['dias'], $row['notasempleado'],
                $row['notajefe'], $row['comentarios'], $tipoTxt
            );
        }
        $stmt->close();

        echo json_encode(array(
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data
        ));

    }

?>
