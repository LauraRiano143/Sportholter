<?php

$token = $_GET["token"];

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
    die("El token expiró");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../media/logo-p.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>SPORTHOLTER</title>
</head>

<body class="log">

    <div class="login">
        <div class="imagen">
            <a title="Logo_login" href="../index.html"><img src="../media/Login.png" alt="Logo_login" style="height: 38em;"/></a>
        </div>

        <div class="contenedor" style="height: 21em;">
            
            <div class="titulo">
                <h4>SPORTHOLTER</h4>
            </div>

            <div class="inicio">
                <h4>Contraseña</h4>
            </div>

            <form method="post" action="process-reset-password.php">

                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">              

                <div class="cont">
                    <h5>Contraseña nueva</h5>
                    <div class="agrupar">
                        <img src="../media/lock.svg" alt="contraseña" style="height: 2rem; background-color: gray">
                        <input autocomplete="off" name="password" class="in" type="password" id="password" placeholder="-"/>
                    </div>
                </div>
                
                <div class="cont">
                    <h5>Confirmar</h5>
                    <div class="agrupar">
                        <img src="../media/lock.svg" alt="contraseña" style="height: 2rem; background-color: gray">
                        <input autocomplete="off" name="password_confirmation" class="in" type="password" id="password_confirmation" placeholder="-"/>
                    </div>
                </div>
                
                <div class="enviar">
                    <button id="btnChangePassword">CAMBIAR</button> 
                </div>

            </form>
        </div>
        
    </div>
</body>
</html>

