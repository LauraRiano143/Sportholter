<?php
session_start();
require_once 'conexion.php';

$nd_paciente = $_SESSION['documento'];

try {
    $pdoUsuarios = $conexion->prepare('SELECT * FROM usuarios WHERE num_documento = ?');
    $pdoUsuarios->bindValue(1, $nd_paciente);
    $pdoUsuarios->execute();

    $pdoConsulta = $conexion->prepare('SELECT * FROM consulta WHERE documento_paciente = ?');
    $pdoConsulta->bindValue(1, $nd_paciente);
    $pdoConsulta->execute();

    $resultUsuarios = $pdoUsuarios->fetch(PDO::FETCH_ASSOC);
    $resultConsulta = $pdoConsulta->fetch(PDO::FETCH_ASSOC);

    $result = array_merge($resultUsuarios, $resultConsulta);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(array()); // Devolver un arreglo vacío si no hay registros encontrados
    }
} catch(PDOException $error) {
    echo $error->getMessage();
    die();
}
?>
