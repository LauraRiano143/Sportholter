<?php
require_once 'conexion.php';
session_start();

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
$email = isset($data['email']) ? $data['email'] : '';
$actividad = isset($data['actividad']) ? $data['actividad'] : '';
$frecuencia = isset($data['frecuencia']) ? $data['frecuencia'] : '';

$nd_especialista = $_SESSION['numero-documento']; 

try {
    $pdo = $conexion->prepare('UPDATE usuarios SET num_documento=?, correo=?, primer_nombre=?, segundo_nombre=?, primer_apellido=?, segundo_apellido=?, id_documento=?, ciudad_expedicion=?, fecha_nacimiento=?, telefono=?, id_genero=? WHERE num_documento=?');
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
    $pdo->bindValue(12, $n_documento); 

    $pdo->execute();

    if ($pdo->rowCount() === 0) {
        echo json_encode('false');
        exit();
    }

    $pdoConsulta = $conexion->prepare('UPDATE consulta SET documento_especialista=?, actividad_fisica=?, frecuencia_actividad=? WHERE documento_paciente=?');

    $pdoConsulta->bindValue(1, $nd_especialista);
    $pdoConsulta->bindValue(2, $actividad);
    $pdoConsulta->bindValue(3, $frecuencia);
    $pdoConsulta->bindValue(4, $n_documento);
    
    $pdoConsulta->execute();
    
    echo json_encode('true');

} catch(PDOException $error) {
    echo $error->getMessage();
    die();
}
?>
