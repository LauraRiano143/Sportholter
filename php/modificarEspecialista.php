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
$c_expedicion = isset($data['ciudad-expedicion']) ? $data['ciudad-expedicion'] : '';
$f_nacimiento = isset($data['fecha-nacimiento']) ? $data['fecha-nacimiento'] : '';
$genero = isset($data['genero']) ? $data['genero'] : '';
$telefono = isset($data['telefono']) ? $data['telefono'] : '';
$email = isset($_SESSION['correo']) ? $_SESSION['correo'] : '';

try {
    $checkQuery = $conexion->prepare('SELECT * FROM usuarios WHERE correo = ?');
    $checkQuery->bindValue(1, $email);
    $checkQuery->execute();
    $existingUser = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if (!$existingUser) {
        echo json_encode('No hay datos');
        die();
    }

    $pdo = $conexion->prepare('UPDATE usuarios SET primer_nombre=?, segundo_nombre=?, primer_apellido=?, 
    segundo_apellido=?, id_documento=?, ciudad_expedicion=?, fecha_nacimiento=?, telefono=?, 
    id_genero=?, id_tipo=? WHERE correo=?');

    $pdo->bindValue(1, $p_nombre);
    $pdo->bindValue(2, $s_nombre);
    $pdo->bindValue(3, $p_apellido);
    $pdo->bindValue(4, $s_apellido);
    $pdo->bindValue(5, $t_documento);
    $pdo->bindValue(6, $c_expedicion);
    $pdo->bindValue(7, $f_nacimiento);
    $pdo->bindValue(8, $telefono);
    $pdo->bindValue(9, $genero);
    $pdo->bindValue(10, 2); 
    $pdo->bindValue(11, $email);

    $pdo->execute() or die(print($pdo->errorInfo()));
    $rowsAffected = $pdo->rowCount();

    if ($rowsAffected > 0) {
        echo json_encode('true');
    } else {
        echo json_encode('false');
    }

} catch(PDOException $error) {
    echo $error->getMessage();
    die();
}
?>
