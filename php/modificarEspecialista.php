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
    $resultado = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {
        echo json_encode(['success' => false, 'error' => 'No hay datos']);
        die();
    }

    if ($resultado['id_documento'] != $t_documento || $resultado['num_documento'] != $n_documento) {
        echo json_encode(['success' => false, 'error' => 'No se puede modificar el tipo de documento ni el número de documento.']);
        die();
    }

    $fechaNacimiento = new DateTime($f_nacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNacimiento)->y;

    if ($edad < 18) {
        echo json_encode(['success' => false, 'error' => 'La persona debe tener al menos 18 años']);
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
    $pdo->bindValue(10, $resultado['id_tipo']); 
    $pdo->bindValue(11, $email);

    $pdo->execute();

    $rowsAffected = $pdo->rowCount();

    if ($rowsAffected > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se afectaron filas']);
    }

} catch(PDOException $error) {
    echo json_encode(['success' => false, 'error' => $error->getMessage()]);
    die();
}
?>
