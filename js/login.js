document.getElementById('form_login').addEventListener('submit', function(e) {    
    e.preventDefault();
    let email = document.getElementById('txt_email').value;
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let isPatient = document.getElementById('mycheck').checked;

    if (emailRegex.test(email)) {
        let formulario = new FormData(document.getElementById('form_login'));

        fetch('../php/login.php', {
            method: 'POST',
            body: formulario
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (isPatient) {
                    
                    window.location.href = '../Paciente/inicioPaciente.html';
                } else {
                    window.location.href = '../Interfaz/configuracion.html';
                }
            } else {
                console.log('Acceso incorrecto:', data.message);
                alert('Acceso incorrecto!, Verifica las credenciales');
                window.location.href = 'login.html';
            }
        });
    } else {
        console.log('Email no válido:', email);
        alert('Por favor, ingresa un correo electrónico válido');
    }
});
