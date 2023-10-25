<?php
session_start();
require_once 'conexion.php';

$email = isset($_SESSION['correo']) ? $_SESSION['correo'] : '';

try {
    $pdo = $conexion->prepare('DELETE FROM registro WHERE correo = ?');
    $pdo->bindValue(1, $email);
    $pdo->execute();

    $pdo = $conexion->prepare('DELETE FROM usuarios WHERE correo = ?');
    $pdo->bindValue(2, $email);
    $pdo->execute();

    $result = $pdo->rowCount();
    echo $result > 0 ? "Registros eliminados correctamente." : "No se encontraron registros para eliminar.";
} catch(PDOException $error) {
    echo $error->getMessage();
    die();
}
?>
