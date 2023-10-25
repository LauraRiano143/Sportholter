function eliminarEspecialista() {
    if (confirm("¿Estás seguro de que deseas eliminar?")) {
      fetch('../php/eliminarEspecialista.php', {
        method: 'POST'
      })
      .then(response => response.text())
      .then(data => {
        console.log(data);
        window.location.href = '../index.html';
      })
      .catch(error => {
        console.log(error);
      });
    }
  }
  