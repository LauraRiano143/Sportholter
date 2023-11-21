document.getElementById('modificar').addEventListener('click', function(e) {
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
    };

    console.log('Datos ingresados:');
    console.log(formulario);

    fetch('../php/modificarEspecialista.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formulario)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success === false) {
            console.log('Error al modificar:', data.error);
            alert('Error al modificar el usuario: ' + data.error);
        } else if (data.success === true) {
            // Restablecer los valores del formulario
            document.getElementById('primer-nombre').value = '';
            document.getElementById('segundo-nombre').value = '';
            document.getElementById('primer-apellido').value = '';
            document.getElementById('segundo-apellido').value = '';
            document.getElementById('tipo-documento').value = '';
            document.getElementById('numero-documento').value = '';
            document.getElementById('ciudad-expedicion').value = '';
            document.getElementById('fecha-nacimiento').value = '';
            document.getElementById('genero').value = '';
            document.getElementById('telefono').value = '';
            alert('El usuario se modificó correctamente');
            window.location.href = 'configuracion.html';
        } else {
            console.log('Respuesta inesperada:', data);
            alert('Respuesta inesperada del servidor.');
        }
    })
    .catch(error => {
        console.error('Error de red:', error);
        alert('Hubo un error de red al intentar modificar el usuario.');
    });
});
