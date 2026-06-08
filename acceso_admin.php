<?php
/**
 * Guard de acceso al bloque "Administración".
 *
 * Incluir como PRIMERA línea de cualquier vista de Administración:
 *     <?php include 'acceso_admin.php'; ?>
 *
 * Valida sesión por cookie + permiso en accesos_especiales
 * (sistema 'incidencias', opcion 'verAdministracion') y redirige si no pasa.
 *
 * Por qué en la vista y no solo en el menú: el menú únicamente OCULTA el enlace
 * (UX); esto impide el acceso directo por URL a alguien con sesión válida pero
 * sin permiso. Deja $conn disponible por si la vista lo necesita.
 */

include 'conn.php';

if ($_COOKIE['noEmpleado'] == '' || $_COOKIE['noEmpleado'] == null) {
    echo '<script>window.location.assign("index")</script>';
    exit;
}

$noEmpAcc = (int) $_COOKIE['noEmpleado'];
$stmtAcc = $conn->prepare("SELECT id FROM accesos_especiales WHERE noEmpleado = ? AND sistema = 'incidencias' AND opcion = 'verAdministracion' AND estatus = 1 LIMIT 1");
$stmtAcc->bind_param("i", $noEmpAcc);
$stmtAcc->execute();
if ($stmtAcc->get_result()->num_rows === 0) {
    $stmtAcc->close();
    echo '<script>window.location.assign("inicio")</script>';
    exit;
}
$stmtAcc->close();
