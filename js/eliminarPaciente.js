function eliminarPaciente() {
    if (confirm("¿Estás seguro de que deseas eliminar?")) {
      fetch('../../php/eliminarPaciente.php', {
        method: 'POST'
      })
      .then(response => response.text())
      .then(data => {
        console.log(data);
        console.log("Paciente eliminado");
        alert("Paciente eliminado")
        window.location.href = '../inicio.html';
      })
      .catch(error => {
        console.log(error);
      });
    }
  }