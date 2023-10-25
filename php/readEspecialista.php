<?php

require_once 'conexion.php';
session_start();

$email = isset($_SESSION['correo']) ? $_SESSION['correo'] : '';


try {
    $pdo = $conexion->prepare('SELECT * FROM usuarios WHERE correo = ?');
    $pdo->bindValue(1, $email);
    $pdo->execute();

    $result = $pdo->fetch(PDO::FETCH_ASSOC);

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
