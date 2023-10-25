document.addEventListener('DOMContentLoaded', function() {
    getData('../../php/readPacienteECG.php')
      .then(data => {
        displayData(data);
      });
  });
  
  function getData(url) {
    return fetch(url)
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la solicitud: ' + response.status);
        }
        return response.json();
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }
  
  function displayData(data) {
    console.log('Datos leídos:');
    console.log(data);
  
    if (Object.keys(data).length === 0) {
      console.log('No hay datos disponibles');
      alert('No hay datos disponibles');
      return;
    }
  
    const pnombreEspecialista = document.getElementById('primer-nombre');
    pnombreEspecialista.value = data.primer_nombre || '';
  
    const papellidoEspecialista = document.getElementById('primer-apellido');
    papellidoEspecialista.value = data.primer_apellido || '';
  
    const nDocumento = document.getElementById('numero-documento');
    nDocumento.value = data.num_documento || '';
  
    const telefono = document.getElementById('telefono');
    telefono.value = data.telefono || '';
  
    const email = document.getElementById('email');
    email.value = data.correo || '';
  
    const actividad = document.getElementById('actividad');
    actividad.value = data.actividad_fisica || '';

    const frecuencia = document.getElementById('frecuencia');
    frecuencia.value = data.frecuencia_actividad || '';
  }
  