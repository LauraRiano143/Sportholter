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
        $campoDocumentoEspecialista = 'documento_especialista';
        $campoDocumentoPaciente = 'documento_paciente';
    } elseif ($medicoData['id_tipo'] == 3) {
        $campoDocumentoEspecialista = 'documento_paciente';
        $campoDocumentoPaciente = 'documento_paciente';
    }

    if ($fecha !== '') {
        $pdoConsulta = $conexion->prepare("SELECT u.primer_nombre, u.primer_apellido, u.num_documento, 
        CASE WHEN c.ECG IS NULL THEN 'No' ELSE 'Sí' END AS tiene_ECG, c.fecha_consulta 
        FROM usuarios u 
        JOIN consulta c ON u.num_documento = c.$campoDocumentoPaciente 
        WHERE c.fecha_consulta = ? AND c.$campoDocumentoEspecialista = ?");
        $pdoConsulta->bindValue(1, $fecha);
        $pdoConsulta->bindValue(2, $medicoData['num_documento']);
    } else {
        $pdoConsulta = $conexion->prepare("SELECT u.primer_nombre, u.primer_apellido, u.num_documento, 
        CASE WHEN c.ECG IS NULL THEN 'No' ELSE 'Sí' END AS tiene_ECG, c.fecha_consulta 
        FROM usuarios u 
        JOIN consulta c ON u.num_documento = c.$campoDocumentoPaciente 
        WHERE c.$campoDocumentoEspecialista = ?");
        $pdoConsulta->bindValue(1, $medicoData['num_documento']);
    }

    $pdoConsulta->execute();

    $resultConsulta = $pdoConsulta->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultConsulta);
} catch(PDOException $error) {
    echo $error->getMessage();
    die();
}
?>
