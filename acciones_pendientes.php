<?php
// acciones_pendientes.php
include "conn.php"; 

// Capturamos la cookie del empleado
$noEmpleado_cookie = isset($_COOKIE['noEmpleado']) ? $_COOKIE['noEmpleado'] : null;

$op = $_GET["op"] ?? '';

switch ($op) {
    case 'guardar':
        $tarea = mysqli_real_escape_string($conn, $_POST["tarea"]);
        $prioridad = mysqli_real_escape_string($conn, $_POST["prioridad"]);
        
        // Guardamos incluyendo el noEmpleado de la cookie
        $sql = "INSERT INTO pendientes (noEmpleado, tarea, prioridad, estado, fecha_registro) 
                VALUES ('$noEmpleado_cookie', '$tarea', '$prioridad', 'Pendiente', NOW())";
        
        echo mysqli_query($conn, $sql) ? "1" : "0";
        break;

    case 'listar':
        // FILTRO CRÍTICO: Solo mostramos lo que pertenece al empleado logueado
        $sql = "SELECT * FROM pendientes 
                WHERE noEmpleado = '$noEmpleado_cookie' 
                ORDER BY estado ASC, id DESC";
        
        $res = mysqli_query($conn, $sql);
        
        if (!$res) {
            echo "<tr><td colspan='5'>Error: " . mysqli_error($conn) . "</td></tr>";
            break;
        }

        if (mysqli_num_rows($res) == 0) {
            echo "<tr><td colspan='5' class='text-center text-muted'>No tienes pendientes registrados.</td></tr>";
        }

        while($reg = mysqli_fetch_object($res)){
            $esRealizado = ($reg->estado == 'Realizado');
            $claseFila = $esRealizado ? 'tarea-realizada' : '';
            $badgeColor = ($reg->prioridad == 'Alta') ? 'danger' : (($reg->prioridad == 'Media') ? 'warning text-dark' : 'info');

            echo "<tr class='$claseFila'>
                    <td class='text-center'>";
            echo $esRealizado 
                ? "<i class='fas fa-check-circle text-success fa-lg'></i>" 
                : "<button class='btn btn-outline-success btn-sm' onclick='marcarRealizado($reg->id)'><i class='fas fa-check'></i></button>";
            echo "</td>
                    <td>$reg->tarea</td>
                    <td><span class='badge bg-$badgeColor'>$reg->prioridad</span></td>
                    <td><small>" . date('d/m/Y H:i', strtotime($reg->fecha_registro)) . "</small></td>
                    <td>" . ($esRealizado ? "Finalizado" : "Activo") . "</td>
                </tr>";
        }
        break;

    case 'completar':
        $id = mysqli_real_escape_string($conn, $_POST["id"]);
        // Por seguridad, podríamos validar que el ID pertenezca al noEmpleado_cookie
        $sql = "UPDATE pendientes SET estado = 'Realizado', fecha_finalizado = NOW() 
                WHERE id = '$id' AND noEmpleado = '$noEmpleado_cookie'";
        echo mysqli_query($conn, $sql) ? "1" : "0";
        break;
}
?>