document.getElementById('guardar').addEventListener('click', function(e) {
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


    if (!formulario['primer-nombre'] || !formulario['primer-apellido'] || !formulario['segundo-apellido']|| !formulario['tipo-documento'] || !formulario['numero-documento'] 
    || !formulario['ciudad-expedicion'] || !formulario['fecha-nacimiento'] || !formulario['genero'] || !formulario['telefono']) {
        alert('Por favor, complete los campos obligatorios.');
        return;
    }

    fetch('../php/confUsuario.php', {
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
            alert('El usuario se insertó correctamente');
        } else {
            console.log(data);
        }
    });
});
