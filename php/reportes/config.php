<?php
/**conexion a BD */
$usuario  = "root";
$password = "";
$port = '3306';
$servidor = "localhost";
$basededatos = "sportholter";

$con = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al Servidor");
mysqli_query($con,"SET SESSION collation_connection ='utf8_unicode_ci'");
$db = mysqli_select_db($con, $basededatos) or die("Upps! Error en conectar a la Base de Datos");

try {
    $conexion = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch(PDOException $error) {
    echo "Error en la conexión: " . $error->getMessage();
    die();
}
//bd_groomers
//minitienda