document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.fecha');
    const url = '../php/pacientes.php';
  
    // Obtener todos los usuarios al cargar la página
    getData(url)
      .then(data => {
        displayData(data);
      });
  
    form.addEventListener('submit', function(e) {
      e.preventDefault(); // Evitar el envío del formulario
      
      const fecha = document.getElementById('fecha').value;
      const data = new FormData();
      data.append('fecha', fecha);
  
      fetch(url, {
        method: 'POST',
        body: data
      })
      .then(response => response.json())
      .then(data => {
        displayData(data);
      })
      .catch(error => {
        console.error('Error:', error);
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
      tableBody.innerHTML = ''; // Limpiar el contenido de la tabla
  
      if (data.length === 0) {
        console.log('No hay datos disponibles');
        alert('No hay datos disponibles');
        return;
      }
  
      data.forEach(paciente => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${paciente.primer_nombre}</td>
          <td>${paciente.primer_apellido}</td>
          <td>${paciente.num_documento}</td>
          <td>${paciente.tiene_ECG}</td>
          <td>${paciente.fecha_consulta}</td>
        `;
        tableBody.appendChild(row);
      });
    }
});
