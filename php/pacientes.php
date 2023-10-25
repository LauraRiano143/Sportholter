<?php
session_start();
require_once 'conexion.php';

$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';

try {
    if ($fecha !== '') {
        $pdoConsulta = $conexion->prepare('SELECT u.primer_nombre, u.primer_apellido, u.num_documento, 
        CASE WHEN c.ECG IS NULL THEN "No" ELSE "Sí" END AS tiene_ECG, c.fecha_consulta 
        FROM usuarios u JOIN consulta c ON u.num_documento = c.documento_paciente WHERE c.fecha_consulta = ?');
        $pdoConsulta->bindValue(1, $fecha);
    } else {
        $pdoConsulta = $conexion->prepare('SELECT u.primer_nombre, u.primer_apellido, u.num_documento, 
        CASE WHEN c.ECG IS NULL THEN "No" ELSE "Sí" END AS tiene_ECG, c.fecha_consulta 
        FROM usuarios u JOIN consulta c ON u.num_documento = c.documento_paciente');
    }

    $pdoConsulta->execute();

    $resultConsulta = $pdoConsulta->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultConsulta);
} catch(PDOException $error) {
    echo $error->getMessage();
    die();
}
?>
