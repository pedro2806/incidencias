<?php
// acciones_pendientes.php
include "conn.php"; 

// Recuperamos el número de empleado de la cookie
$noEmpleado_cookie = isset($_COOKIE['noEmpleado']) ? $_COOKIE['noEmpleado'] : null;

$op = $_GET["op"] ?? '';

switch ($op) {

    // 1. GUARDAR TAREA PRINCIPAL
    case 'guardar':
        if (!$noEmpleado_cookie) { echo "0"; break; }
        
        $tarea = mysqli_real_escape_string($conn, $_POST["tarea"]);
        $prioridad = mysqli_real_escape_string($conn, $_POST["prioridad"]);
        
        $sql = "INSERT INTO pendientes (noEmpleado, tarea, prioridad, estado, fecha_registro) 
                VALUES ('$noEmpleado_cookie', '$tarea', '$prioridad', 'Pendiente', NOW())";
        
        echo mysqli_query($conn, $sql) ? "1" : "0";
        break;

    // 2. LISTAR TAREAS (CON FILTRO DE FECHAS)
    case 'listar':
        $fecha_inicio = $_GET['f_inicio'] ?? '';
        $fecha_fin = $_GET['f_fin'] ?? '';
        
        $filtro_fecha = "";
        if(!empty($fecha_inicio) && !empty($fecha_fin)) {
            $filtro_fecha = " AND DATE(fecha_registro) BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
        }

        $sql = "SELECT * FROM pendientes 
                WHERE noEmpleado = '$noEmpleado_cookie' $filtro_fecha 
                ORDER BY estado ASC, id DESC";
        
        $res = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($res) == 0) {
            echo "<tr><td colspan='5' class='text-center py-4 text-muted'>No se encontraron registros en este periodo.</td></tr>";
            break;
        }

        while($reg = mysqli_fetch_object($res)){
            $esRealizado = ($reg->estado == 'Realizado');
            $claseFila = $esRealizado ? 'tarea-realizada table-light' : '';
            $badgeColor = ($reg->prioridad == 'Alta') ? 'danger' : (($reg->prioridad == 'Media') ? 'warning text-dark' : 'info');

            echo "<tr class='$claseFila'>
                    <td class='text-center'>";
            echo $esRealizado 
                ? "<i class='fas fa-check-circle text-success fa-lg'></i>" 
                : "<button class='btn btn-outline-success btn-sm' onclick='marcarRealizado($reg->id)' title='Finalizar'><i class='fas fa-check'></i></button>";
            echo "</td>
                    <td>
                        <div class='font-weight-bold'>$reg->tarea</div>
                        <small class='text-muted'><i class='far fa-clock'></i> Registrado: " . date('d/m/Y H:i', strtotime($reg->fecha_registro)) . "</small>
                    </td>
                    <td><span class='badge bg-$badgeColor'>$reg->prioridad</span></td>
                    <td>" . ($esRealizado ? "<small class='text-secondary font-weight-bold'>Terminado: ".date('d/m/Y', strtotime($reg->fecha_finalizado))."</small>" : "<span class='text-warning font-weight-bold'>En curso</span>") . "</td>
                    <td class='text-center'>
                        <div class='btn-group'>
                            <button class='btn btn-success btn-sm' onclick='abrirAvances($reg->id, \"".addslashes($reg->tarea)."\")' title='Ver Bitácora'>
                                <i class='fas fa-history'></i>
                            </button>
                        </div>
                    </td>
                </tr>";
        }
        break;

    // 3. GUARDAR UN AVANCE (COMENTARIO)
    case 'guardar_avance':
        $id_pendiente = mysqli_real_escape_string($conn, $_POST["id_pendiente"]);
        $comentario = mysqli_real_escape_string($conn, $_POST["comentario"]);
        
        $sql = "INSERT INTO pendientes_avances (id_pendiente, comentario, fecha_avance) 
                VALUES ('$id_pendiente', '$comentario', NOW())";
        
        echo mysqli_query($conn, $sql) ? "1" : "0";
        break;

    // 4. VER HISTORIAL DE AVANCES DE UNA TAREA
    case 'ver_avances':
        $id = mysqli_real_escape_string($conn, $_GET["id"]);
        $sql = "SELECT * FROM pendientes_avances WHERE id_pendiente = '$id' ORDER BY fecha_avance DESC";
        $res = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($res) > 0){
            while($reg = mysqli_fetch_object($res)){
                echo "<div class='avance-item mb-3 shadow-sm bg-white p-2 border-left-info'>
                        <div class='d-flex justify-content-between'>
                            <small class='text-primary font-weight-bold'>
                                <i class='far fa-calendar-alt'></i> ".date('d/m/Y H:i', strtotime($reg->fecha_avance))."
                            </small>
                        </div>
                        <div class='text-gray-800 mt-1'>$reg->comentario</div>
                    </div>";
            }
        } else {
            echo "<div class='alert alert-light text-center'>No hay avances registrados para esta actividad.</div>";
        }
        break;

    // 5. MARCAR COMO FINALIZADO
    case 'completar':
        $id = mysqli_real_escape_string($conn, $_POST["id"]);
        $sql = "UPDATE pendientes SET estado = 'Realizado', fecha_finalizado = NOW() 
                WHERE id = '$id' AND noEmpleado = '$noEmpleado_cookie'";
        echo mysqli_query($conn, $sql) ? "1" : "0";
        break;

    // 6. ELIMINAR TAREA Y SUS AVANCES (Por el ON DELETE CASCADE en la BD)
    case 'eliminar':
        $id = mysqli_real_escape_string($conn, $_POST["id"]);
        $sql = "DELETE FROM pendientes WHERE id = '$id' AND noEmpleado = '$noEmpleado_cookie'";
        echo mysqli_query($conn, $sql) ? "1" : "0";
        break;
}
?>