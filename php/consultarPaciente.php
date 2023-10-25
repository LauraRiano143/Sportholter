<?php
session_start();
require_once 'conexion.php';

$documento = isset($_POST['documento']) ? $_POST['documento'] : '';
$_SESSION['documento'] = $documento;
$nd_paciente=$_SESSION['documento'];

if ($conexion) {
    $pdo = $conexion->prepare('SELECT COUNT(*) AS count FROM usuarios WHERE num_documento = :documento AND id_tipo = 3');
    $pdo->bindValue(':documento', $documento);
    $pdo->execute();

    $count = $pdo->fetchColumn();

    if ($count > 0) {
        echo json_encode(array('existe' => true));
    } else {
        echo json_encode(array('existe' => false));
    }
} else {
    echo json_encode(array('error' => 'Error en la conexión a la base de datos'));
    die();
}
?>
