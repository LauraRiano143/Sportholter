<?php
session_start();
require_once 'conexion.php';

$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$emailEspecialista = isset($_SESSION['correo']) ? $_SESSION['correo'] : '';

try {
    // Obtener el ID del médico (especialista) usando el correo
    $pdoIdMedico = $conexion->prepare('SELECT num_documento, id_tipo FROM usuarios WHERE correo = ?');
    $pdoIdMedico->bindValue(1, $emailEspecialista);
    $pdoIdMedico->execute();
    $medicoData = $pdoIdMedico->fetch(PDO::FETCH_ASSOC);

    if ($medicoData['id_tipo'] == 2) {
        // El usuario es un especialista
        $campoDocumento = 'documento_especialista';
    } elseif ($medicoData['id_tipo'] == 3) {
        // El usuario es un paciente
        $campoDocumento = 'documento_paciente';
    }

    if ($fecha !== '') {
        $pdoConsulta = $conexion->prepare("SELECT u.primer_nombre, u.primer_apellido, u.num_documento, 
        CASE WHEN c.ECG IS NULL THEN 'No' ELSE 'Sí' END AS tiene_ECG, c.fecha_consulta 
        FROM usuarios u 
        JOIN consulta c ON u.num_documento = c.$campoDocumento 
        WHERE c.fecha_consulta = ?");
        $pdoConsulta->bindValue(1, $fecha);
    } else {
        $pdoConsulta = $conexion->prepare("SELECT u.primer_nombre, u.primer_apellido, u.num_documento, 
        CASE WHEN c.ECG IS NULL THEN 'No' ELSE 'Sí' END AS tiene_ECG, c.fecha_consulta 
        FROM usuarios u 
        JOIN consulta c ON u.num_documento = c.$campoDocumento");
    }

    $pdoConsulta->execute();

    $resultConsulta = $pdoConsulta->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultConsulta);
} catch(PDOException $error) {
    echo $error->getMessage();
    die();
}
?>
