document.getElementById('consultaP').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let formulario = new FormData(document.getElementById('consultaP'));
    let documento = formulario.get('documento');
  
    fetch('../../php/consultarPaciente.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({ documento: documento })
    })
      .then(response => response.json())
      .then(data => {
        if (data.existe) {
          console.log("si existe");
          window.location.href = 'consulta_datos_p.html';
        } else {
          console.log("no existe");
          alert("Paciente no registrado");
          window.location.href = 'consulta_p.html';
        }
      })
      .catch(error => {
        console.error('Error al realizar la consulta:', error);
      });
  });
  