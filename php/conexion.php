<?php
$host = 'localhost';
$port = '3306';
$dbname = 'sportholter';
$username = 'root';
$password = '';

try {
    $conexion = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch(PDOException $error) {
    echo "Error en la conexión: " . $error->getMessage();
    die();
}
?>
