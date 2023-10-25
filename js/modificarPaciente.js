console.log("si");
document.getElementById('editar').addEventListener('click', function(e) {
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
        'frecuencia': document.getElementById('frecuencia').value
    };

    console.log('Datos ingresados:');
    console.log(formulario);

    fetch('../../php/modificarPaciente.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formulario)
    })
    .then(res => res.json())
    .then(data => {
        if (data === 'true') {
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
            document.getElementById('email').value = '';
            document.getElementById('actividad').value = '';
            document.getElementById('frecuencia').value = '';
            alert('El usuario se modificó');
            window.location.href = 'consulta_datos_p.html';
        } else {
            console.log(data);
        }
    });
});
