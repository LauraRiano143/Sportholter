document.getElementById('editar').addEventListener('click', function (e) {
    e.preventDefault();

    let formulario = {
        'primer-nombre': document.getElementById('primer-nombre').value,
        'segundo-nombre': document.getElementById('segundo-nombre').value,
        'primer-apellido': document.getElementById('primer-apellido').value,
        'segundo-apellido': document.getElementById('segundo-apellido').value,
        'tipo-documento': document.getElementById('tipo-documento').value,
        'numero-documento': document.getElementById('numero-documento').value,
        'ciudad-expedicion': document.getElementById('ciudad-expedicion').value,
        'fecha-nacimiento': document.getElementById('fecha-nacimiento').value,
        'genero': document.getElementById('genero').value,
        'telefono': document.getElementById('telefono').value,
        'email': document.getElementById('email').value,
        'actividad': document.getElementById('actividad').value,
        'frecuencia': document.getElementById('frecuencia').value,
    };

    if (!formulario['email'] || !isValidEmail(formulario['email'])) {
        alert('Por favor, ingrese un correo electrónico válido.');
        return;
    }

    if (!formulario['primer-nombre'] || !formulario['primer-apellido'] || !formulario['segundo-apellido'] || !formulario['tipo-documento'] || !formulario['numero-documento'] ||
        !formulario['ciudad-expedicion'] || !formulario['fecha-nacimiento'] || !formulario['genero'] || !formulario['telefono']) {
        alert('Por favor, complete los campos obligatorios.');
        return;
    }

    fetch('../../php/modificarPaciente.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formulario),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data === 'true') {
                alert('El usuario se modificó correctamente');
                window.location.href = 'consulta_datos_p.html';
            } else {
                console.error(data);
                alert(data);
            }
        })
        .catch((error) => {
            console.error('Error en la solicitud:', error);
        });
});

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}
