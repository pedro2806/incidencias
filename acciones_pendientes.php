<?php
// acciones_pendientes.php
include "conn.php"; 

// Usamos $conn que es como la tienes en tu conn.php
if (!$conn) {
    die("Error: No se encontró la variable de conexión.");
}

$op = $_GET["op"] ?? '';

switch ($op) {
    case 'guardar':
        $tarea = mysqli_real_escape_string($conn, $_POST["tarea"]);
        $prioridad = mysqli_real_escape_string($conn, $_POST["prioridad"]);
        
        // Agregamos los campos que definimos en la estructura
        $sql = "INSERT INTO pendientes (tarea, prioridad, estado, fecha_registro) 
                VALUES ('$tarea', '$prioridad', 'Pendiente', NOW())";
        
        echo mysqli_query($conn, $sql) ? "1" : "0";
        break;

    case 'listar':
        $sql = "SELECT * FROM pendientes ORDER BY estado ASC, id DESC";
        $res = mysqli_query($conn, $sql);
        
        if (!$res) {
            echo "<tr><td colspan='5'>Error en tabla: " . mysqli_error($conn) . "</td></tr>";
            break;
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
                    <td>" . ($reg->fecha_registro ? date('d/m/Y H:i', strtotime($reg->fecha_registro)) : '---') . "</td>
                    <td>" . ($esRealizado ? "Finalizado" : "En curso") . "</td>
                </tr>";
        }
        break;

    case 'completar':
        $id = mysqli_real_escape_string($conn, $_POST["id"]);
        $sql = "UPDATE pendientes SET estado = 'Realizado', fecha_finalizado = NOW() WHERE id = '$id'";
        echo mysqli_query($conn, $sql) ? "1" : "0";
        break;
}
?>