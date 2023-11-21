document.getElementById('form_register').addEventListener('submit', function(e) {
    e.preventDefault();
    let nombre = document.getElementById('txt_usuario').value;
    let email = document.getElementById('txt_email').value;
    let contraseña = document.getElementById('txt_pass').value;
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  
    var thisRegex = new RegExp("(?=.*[0-9])");
    var thisRegex2 = new RegExp("(?=.[a-z])");
  
    if (contraseña.length < 8) {
        alert("La contraseña debe tener al menos 8 caracteres");
    }
    
    else if ( !thisRegex.test(contraseña)) {
        alert("La contraseña debe tener al menos un número");
    }
    
    else if ( !thisRegex2.test(contraseña)) {
        alert("La contraseña debe tener al menos una letra");
    }
    else if (nombre === '' || email === '' || contraseña === '') {
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
                  console.log(data);
              }
          });
    }
  });