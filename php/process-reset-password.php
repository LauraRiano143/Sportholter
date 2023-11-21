<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM registro
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

if (strlen($_POST["password"]) < 8) {
    die("La contraseña debe tener al menos 8 caracteres");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("La contraseña debe contener al menos una letra");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("La contraseña debe contener al menos un número");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Las contraseñas deben coincidir");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE registro
        SET contraseña = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE correo = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $password_hash, $user["correo"]);

$stmt->execute();

echo "Password updated. You can now login.";  