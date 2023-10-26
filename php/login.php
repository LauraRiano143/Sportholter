<?php
include 'conexion.php';
session_start();

$email = $_POST['txt_email'];
$contraseña = $_POST['txt_pass'];
$esPaciente = isset($_POST['mycheck']) ? true : false;

$response = array(); // Crear un array para la respuesta

try {
    $consulta = $conexion->prepare("SELECT * FROM registro WHERE correo = :email");
    $consulta->bindParam(':email', $email);
    $consulta->execute();

    if ($consulta->rowCount() > 0) {
        $registro = $consulta->fetch(PDO::FETCH_ASSOC);
        $hashContraseña = $registro['contraseña'];

        if (password_verify($contraseña, $hashContraseña)) {
            $consultaUsuarios = $conexion->prepare("SELECT
                correo,
                CASE WHEN id_tipo = 3 THEN 1 ELSE 0 END AS es_deportista
                FROM usuarios
                WHERE correo = :email");

            $consultaUsuarios->bindParam(':email', $email);
            $consultaUsuarios->execute();

            $usuario = $consultaUsuarios->fetch(PDO::FETCH_ASSOC);

            if ($esPaciente) {
                if ($usuario['es_deportista'] == 1) {
                    $_SESSION['correo'] = $email;
                    $response['success'] = true;
                    $response['message'] = 'Ingresas como paciente';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'No eres un paciente, no tienes permitido ingresar como paciente';
                }
            } else {
                if ($usuario['es_deportista'] == 0) {
                    $_SESSION['correo'] = $email;
                    $response['success'] = true;
                    $response['message'] = 'Ingresas como especialista';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'No eres un especialista, no tienes permitido ingresar como especialista';
                }
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'La contraseña es incorrecta';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'El correo no se encuentra registrado';
    }
} catch (PDOException $error) {
    $response['success'] = false;
    $response['message'] = 'Error en la base de datos';
}

// Enviar la respuesta JSON
header('Content-Type: application/json');
echo json_encode($response);
?>




