<?php
session_start();
require_once 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

$p_nombre = isset($data['primer-nombre']) ? $data['primer-nombre'] : '';
$s_nombre = isset($data['segundo-nombre']) ? $data['segundo-nombre'] : '';
$p_apellido = isset($data['primer-apellido']) ? $data['primer-apellido'] : '';
$s_apellido = isset($data['segundo-apellido']) ? $data['segundo-apellido'] : '';
$t_documento = isset($data['tipo-documento']) ? $data['tipo-documento'] : '';

$n_documento = isset($data['numero-documento']) ? $data['numero-documento'] : '';
$_SESSION['numero-documento'] = $n_documento;
$nd_especialista = $_SESSION['numero-documento'];

$c_expedicion = isset($data['ciudad-expedicion']) ? $data['ciudad-expedicion'] : '';
$f_nacimiento = isset($data['fecha-nacimiento']) ? $data['fecha-nacimiento'] : '';
$genero = isset($data['genero']) ? $data['genero'] : '';
$telefono = isset($data['telefono']) ? $data['telefono'] : '';
$email = isset($_SESSION['correo']) ? $_SESSION['correo'] : '';

try {
    $pdo = $conexion->prepare('INSERT INTO usuarios(num_documento, correo, primer_nombre, segundo_nombre,
    primer_apellido, segundo_apellido, id_documento, ciudad_expedicion, fecha_nacimiento, telefono,
    id_genero, id_tipo) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)');
    $pdo->bindValue(1, $n_documento);
    $pdo->bindValue(2, $email); 
    $pdo->bindValue(3, $p_nombre);
    $pdo->bindValue(4, $s_nombre);
    $pdo->bindValue(5, $p_apellido);
    $pdo->bindValue(6, $s_apellido);
    $pdo->bindValue(7, $t_documento);
    $pdo->bindValue(8, $c_expedicion);
    $pdo->bindValue(9, $f_nacimiento);
    $pdo->bindValue(10, $telefono);
    $pdo->bindValue(11, $genero);
    $pdo->bindValue(12, 2); 

    $pdo->execute() or die(print($pdo->errorInfo()));
    echo json_encode('true');
} catch(PDOException $error) {
    echo $error->getMessage();
}
?>
