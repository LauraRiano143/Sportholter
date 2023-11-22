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
    
    if (empty($email)) {
        echo json_encode('El campo del correo no puede estar vacío.');
        die();
    }

    $pdoVerificar = $conexion->prepare('SELECT id_documento, num_documento FROM usuarios WHERE num_documento=?');
    $pdoVerificar->bindValue(1, $n_documento);
    $pdoVerificar->execute();
    $resultado = $pdoVerificar->fetch(PDO::FETCH_ASSOC);

    if ($resultado['id_documento'] != $t_documento || $resultado['num_documento'] != $n_documento) {
        echo json_encode('No se puede modificar el tipo de documento ni el número de documento.');
        die();
    }

    $pdoUsuarios = $conexion->prepare('UPDATE usuarios SET correo=?, primer_nombre=?, segundo_nombre=?, primer_apellido=?, 
        segundo_apellido=?, ciudad_expedicion=?, fecha_nacimiento=?, telefono=?, id_genero=? WHERE num_documento=?');
    $pdoUsuarios->bindValue(1, $email);
    $pdoUsuarios->bindValue(2, $p_nombre);
    $pdoUsuarios->bindValue(3, $s_nombre);
    $pdoUsuarios->bindValue(4, $p_apellido);
    $pdoUsuarios->bindValue(5, $s_apellido);
    $pdoUsuarios->bindValue(6, $c_expedicion);
    $pdoUsuarios->bindValue(7, $f_nacimiento);
    $pdoUsuarios->bindValue(8, $telefono);
    $pdoUsuarios->bindValue(9, $genero);
    $pdoUsuarios->bindValue(10, $n_documento);

    $pdoUsuarios->execute();

    $modificacionesUsuarios = $pdoUsuarios->rowCount();

    $pdoConsultaInsert = null; // Inicializar $pdoConsultaInsert

    if (!empty($actividad) || !empty($frecuencia)) {
        $pdoConsultaCheck = $conexion->prepare('SELECT * FROM consulta WHERE documento_especialista=?');
        $pdoConsultaCheck->bindValue(1, $nd_especialista);
        $pdoConsultaCheck->execute();

        if ($pdoConsultaCheck->rowCount() > 0) {
            $pdoConsultaUpdate = $conexion->prepare('UPDATE consulta SET actividad_fisica=?, frecuencia_actividad=? WHERE documento_especialista=?');
            $pdoConsultaUpdate->bindValue(1, $actividad);
            $pdoConsultaUpdate->bindValue(2, $frecuencia);
            $pdoConsultaUpdate->bindValue(3, $nd_especialista);
            $pdoConsultaUpdate->execute();
        } else {
            $pdoConsultaInsert = $conexion->prepare('INSERT INTO consulta (documento_paciente, actividad_fisica, frecuencia_actividad, documento_especialista) VALUES (?, ?, ?, ?)');
            $pdoConsultaInsert->bindValue(1, $n_documento);
            $pdoConsultaInsert->bindValue(2, $actividad);
            $pdoConsultaInsert->bindValue(3, $frecuencia);
            $pdoConsultaInsert->bindValue(4, $nd_especialista);
            $pdoConsultaInsert->execute();
        }
    }

    if ($modificacionesUsuarios === 0 && (!$pdoConsultaInsert || $pdoConsultaInsert->rowCount() === 0) && $pdoConsultaUpdate->rowCount() === 0) {
        echo json_encode('No se realizó ninguna modificación.');
        die();
    }

    echo json_encode('true');

} catch(PDOException $error) {
    echo json_encode('Error en la ejecución: ' . $error->getMessage());
    die();
}
?>
