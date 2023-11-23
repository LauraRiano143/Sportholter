document.getElementById('btnChangePassword').addEventListener('click', function(e) {
    e.preventDefault();

    var token = document.getElementById("token").value;

    fetch("../php/reset_password.php?token=" + token)
        .then(response => {
            if (response.ok) {
                return response.text(); // Convertir la respuesta a texto
            } else {
                throw new Error("Token inválido o expirado");
            }
        })
        .then(data => {
            // Aquí, data contendrá el texto de la respuesta del servidor
            alert(data); // Muestra la respuesta en la consola
            changePassword(); // Continuar con el cambio de contraseña si es necesario
        })
        .catch(error => {
            console.error("Error al validar el token:", error);
            alert("Error al validar el token. Por favor, inténtalo de nuevo.");
        });
});


function changePassword() {
    var newPassword = document.getElementById("password").value;
    var confirmPassword = document.getElementById("password_confirmation").value;
    var token = document.getElementById("token").value;

    if (newPassword !== confirmPassword) {
        alert("Las contraseñas no coinciden. Por favor, inténtalo de nuevo.");
        return;
    }

    // Crear un FormData con el token y las contraseñas
    var formData = new FormData();
    formData.append("token", token);
    formData.append("password", newPassword);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/process-reset-password.php", true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                alert(xhr.responseText);
            } else {
                alert("Error al procesar la solicitud. Por favor, inténtalo de nuevo.");
            }
        }
    };

    xhr.send(formData);
}
