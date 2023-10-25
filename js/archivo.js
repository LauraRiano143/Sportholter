document.addEventListener('DOMContentLoaded', function() {
  const url = '../../php/archivo.php';
  let data = []; // Variable para almacenar los datos

  // Obtener los datos del paciente actual al cargar la página
  getData(url)
    .then(resultData => {
      data = resultData; // Almacenar los datos en la variable
      displayData(data); // Mostrar los datos en la tabla
    });

  const abrirBoton = document.getElementById('abrirBtn');
  abrirBoton.addEventListener('click', function(event) {
    const checkboxSeleccionado = document.querySelector('.table__body input[type="checkbox"]:checked');
    if (checkboxSeleccionado) {
      event.preventDefault(); // Evitar el comportamiento predeterminado del enlace
      window.open('../../php/reportes/DescargarReporte_x_fecha_PDF.php', '_blank'); // Abrir enlace en una nueva pestaña
    } else {
      alert('Debes seleccionar un paciente');
    }
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
  const tableBody = document.querySelector('.table__body tbody');
  tableBody.innerHTML = '';

  if (data.length === 0) {
    console.log('No hay datos disponibles');
    alert('No hay datos disponibles');
    return;
  }

  data.forEach(paciente => {
    if (paciente.tiene_ECG === 'Sí') {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td><input type="checkbox"></td>
        <td>${paciente.primer_nombre}</td>
        <td>${paciente.primer_apellido}</td>
        <td>${paciente.num_documento}</td>
        <td>${paciente.fecha_consulta}</td>
      `;
      tableBody.appendChild(row);
    }
  });
}
