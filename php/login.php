<?php
    include 'conexion.php';
    session_start();

    $email = $_POST['txt_email'];
    $contraseña = $_POST['txt_pass'];
    $esPaciente = isset($_POST['mycheck']) ? true : false;

    $response = array();

    try {
        $consultaRegistro = $conexion->prepare("SELECT * FROM registro WHERE correo = :email");
        $consultaRegistro->bindParam(':email', $email);
        $consultaRegistro->execute();

        if ($consultaRegistro->rowCount() > 0) {

            $registro = $consultaRegistro->fetch(PDO::FETCH_ASSOC);

            $consultaUsuarios = $conexion->prepare("SELECT id_tipo, num_documento FROM usuarios WHERE correo = :email");
            $consultaUsuarios->bindParam(':email', $email);
            $consultaUsuarios->execute();

            if ($esPaciente) {
                if ($consultaUsuarios->rowCount() > 0) {
                    $usuario = $consultaUsuarios->fetch(PDO::FETCH_ASSOC);
                    if (isset($usuario['id_tipo']) && $usuario['id_tipo'] == 3) {
                        $_SESSION['correo'] = $email;
                        $response['success'] = true;
                        $response['message'] = 'Ingresas como paciente';
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'No eres un paciente, no tienes permitido ingresar como paciente';
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = 'No estás registrado como paciente';
                }
            } else {
                if ($consultaUsuarios->rowCount() > 0) {
                    $usuario = $consultaUsuarios->fetch(PDO::FETCH_ASSOC);
                    // Asegúrate de que "num_documento" esté definido antes de intentar acceder a él
                    if (isset($usuario['num_documento']) && $usuario['id_tipo'] != 3) {
                        $_SESSION['numero-documento'] = $usuario['num_documento'];
                        $_SESSION['correo'] = $email;
                        $response['success'] = true;
                        $response['message'] = 'Ingresas como especialista';
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'No eres un especialista, no tienes permitido ingresar como especialista';
                    }
                } else {
                    $_SESSION['correo'] = $email;
                    $response['success'] = true;
                    $response['message'] = 'Ingresas como especialista';
                }
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'El correo no se encuentra registrado';
        }
    } catch (PDOException $error) {
        $response['success'] = false;
        $response['message'] = 'Error en la base de datos';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
?>
