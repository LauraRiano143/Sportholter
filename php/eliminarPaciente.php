<?php
session_start();
require_once 'conexion.php';

$nd_paciente = $_SESSION['documento'];

try {
    $pdo = $conexion->prepare('DELETE FROM consulta WHERE documento_paciente = ?');
    $pdo->bindValue(1, $nd_paciente);
    $pdo->execute();

    $result = $pdo->rowCount();

    // Verificar si se eliminaron registros en la tabla "consulta"
    if ($result > 0) {
        // Ahora puedes eliminar el paciente de la tabla "usuarios"
        $pdo = $conexion->prepare('DELETE FROM usuarios WHERE num_documento = ?');
        $pdo->bindValue(1, $nd_paciente);
        $pdo->execute();

        $result = $pdo->rowCount();
        echo $result > 0 ? "Registros eliminados correctamente." : "No se encontraron registros para eliminar.";
    } else {
        echo "No se encontraron registros para eliminar.";
    }
} catch(PDOException $error) {
    echo $error->getMessage();
    die();
}

?>
