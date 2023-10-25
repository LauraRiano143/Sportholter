<?php

require_once 'conexion.php';

$nombre = isset($_POST['txt_usuario']) ? $_POST['txt_usuario'] : '';
$email = isset($_POST['txt_email']) ? $_POST['txt_email'] : '';
$contraseña = isset($_POST['txt_pass']) ? $_POST['txt_pass'] : '';
$hashContraseña = password_hash($contraseña, PASSWORD_DEFAULT);
$esPaciente = isset($_POST['mycheck']) ? $_POST['mycheck'] : '';

try {
    if (!$esPaciente) { 

        $consultaCorreoRegistro = $conexion->prepare("SELECT correo FROM registro WHERE correo = :email");
        $consultaCorreoRegistro->bindParam(':email', $email);
        $consultaCorreoRegistro->execute();

        $consultaCorreoUsuarios = $conexion->prepare("SELECT correo FROM usuarios WHERE correo = :email");
        $consultaCorreoUsuarios->bindParam(':email', $email);
        $consultaCorreoUsuarios->execute();

        if ($consultaCorreoRegistro->rowCount() > 0) {
            echo json_encode('El correo ya está registrado en la tabla "registro".');
        } elseif ($consultaCorreoUsuarios->rowCount() > 0) {
            echo json_encode('El correo ya está registrado en la tabla "usuarios".');
        } else {

            $pdo = $conexion->prepare('INSERT INTO registro(correo, nom_usuario, contraseña) VALUES(?,?,?)');
            $pdo->bindValue(1, $email);
            $pdo->bindValue(2, $nombre);
            $pdo->bindValue(3, $hashContraseña);
            $pdo->execute() or die(print_r($pdo->errorInfo()));
            echo json_encode('true');
        }
    } else {

        $consultaCorreo = $conexion->prepare("SELECT correo FROM registro WHERE correo = :email");
        $consultaCorreo->bindParam(':email', $email);
        $consultaCorreo->execute();

        if ($consultaCorreo) { 
            if ($consultaCorreo->rowCount() > 0) {

                echo json_encode('El correo ya está registrado en la tabla "registro".');
            } else {

                $consultaUsuario = $conexion->prepare("SELECT num_documento FROM usuarios WHERE correo = :email");
                $consultaUsuario->bindParam(':email', $email);
                $consultaUsuario->execute();

                if ($consultaUsuario) { 
                    if ($consultaUsuario->rowCount() > 0) {

                        $row = $consultaUsuario->fetch(PDO::FETCH_ASSOC);
                        $numDocumentoPaciente = $row['num_documento'];
                        $consultaPaciente = $conexion->prepare("SELECT * FROM consulta WHERE documento_paciente = :documentoPaciente AND documento_especialista IS NOT NULL");
                        $consultaPaciente->bindParam(':documentoPaciente', $numDocumentoPaciente);
                        $consultaPaciente->execute();

                        if ($consultaPaciente->rowCount() == 0) {
                            echo json_encode('El paciente no está relacionado con un médico.');
                        } else {
                            $pdo = $conexion->prepare('INSERT INTO registro(correo, nom_usuario, contraseña) VALUES(?,?,?)');
                            $pdo->bindValue(1, $email);
                            $pdo->bindValue(2, $nombre);
                            $pdo->bindValue(3, $hashContraseña);
                            $pdo->execute() or die(print_r($pdo->errorInfo()));
                            echo json_encode('true');
                        }
                    } else {
                        echo json_encode('El correo no está registrado como usuario.');
                    }
                } else {
                    echo json_encode('Error en la consulta.');
                }
            }
        } else {
            echo json_encode('Error en la consulta.');
        }
    }
} catch (PDOException $error) {
    echo $error->getMessage();
    die();
}
?>
