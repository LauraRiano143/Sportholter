<?php
session_start();
require_once 'conexion.php';

$nd_paciente = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';

try {
    $pdoConsulta = $conexion->prepare('SELECT u.primer_nombre, u.primer_apellido, u.num_documento, c.fecha_consulta, 
    CASE WHEN c.ECG IS NULL THEN "No" ELSE "Sí" END AS tiene_ECG FROM usuarios u JOIN consulta c 
    ON u.num_documento = c.documento_paciente WHERE u.num_documento = ?');
    
    $pdoConsulta->bindValue(1, $nd_paciente);

    $pdoConsulta->execute();

    $resultConsulta = $pdoConsulta->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultConsulta);
} catch(PDOException $error) {
    echo $error->getMessage();
    die();
}

?>
