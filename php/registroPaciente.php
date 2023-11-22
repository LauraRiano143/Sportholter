<?php
require_once 'conexion.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);

// Verifica si el especialista ha iniciado sesión
if (isset($_SESSION['numero-documento'])) {
    $nd_especialista = $_SESSION['numero-documento'];
} else {
    // Si el especialista no ha iniciado sesión, muestra un mensaje de error o redirige a la página de inicio de sesión
    echo json_encode('Especialista no ha iniciado sesion');
    exit;
}

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
$email = isset($data['email']) ? $data['email'] : '';
$actividad = isset($data['actividad']) ? $data['actividad'] : '';
$frecuencia = isset($data['frecuencia']) ? $data['frecuencia'] : '';
$fechaActual = date('Y-m-d');

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
    $pdo->bindValue(12, 3);

    $pdo->execute() or die(print_r($pdo->errorInfo()));

    $consultaId = $conexion->lastInsertId();

    $pdo = $conexion->prepare('INSERT INTO consulta(fecha_consulta, documento_paciente,
    documento_especialista, actividad_fisica, frecuencia_actividad) VALUES(?,?,?,?,?)');

    $pdo->bindValue(1, $fechaActual);
    $pdo->bindValue(2, $n_documento);
    $pdo->bindValue(3, $nd_especialista);
    $pdo->bindValue(4, $actividad);
    $pdo->bindValue(5, $frecuencia);

    $pdo->execute() or die(print_r($pdo->errorInfo()));
    echo json_encode('true');

} catch (PDOException $error) {
    echo $error->getMessage();
    die();
}
?>
