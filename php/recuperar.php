<?php
require_once 'conexion.php';
$email = $_POST["email"];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry= date("Y-m-d H:i:s", time() + 60 * 5);

$mysqli = require __DIR__ . "/database.php";

$sql = "UPDATE registro
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE correo=?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$stmt->execute();


if ($mysqli->affected_rows){
     
    $mail = require __DIR__."/mailer.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

    Click  <a href="http://localhost/Sportholter/php/reset_password.php?token=$token">here</a>
    to reset your password.

    END;

    try {
        $mail->send(); 
    } catch (Exception $e) {
        echo "Not send. Mailer error: {$mail->ErrorInfo}";
    }
}

echo "Message sent, please check your inbox.";

?>