document.getElementById('form_register').addEventListener('submit', function(e) {
  e.preventDefault();
  let nombre = document.getElementById('txt_usuario').value;
  let email = document.getElementById('txt_email').value;
  let contraseña = document.getElementById('txt_pass').value;
  let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (nombre === '' || email === '' || contraseña === '') {
      alert('Por favor, completa todos los campos');
  } else if (!emailRegex.test(email)) {
      alert('Por favor, ingresa un correo electrónico válido');
  } else {
      let formulario = new FormData(document.getElementById('form_register'));

      fetch('../php/registro.php', {
          method: 'POST',
          body: formulario
      })
      .then(res => res.json())
      .then(data => {
          if (data === 'Correo existente') {
              alert('El usuario ya existe');
              window.location.href = 'register.html';
          } else if (data === 'true') {
              document.getElementById('txt_usuario').value = '';
              document.getElementById('txt_email').value = '';
              document.getElementById('txt_pass').value = '';
              alert('El usuario se insertó correctamente');
              window.location.href = 'login.html';
          } else {
            alert(data);
              console.log(data);
          }
      });
  }
});