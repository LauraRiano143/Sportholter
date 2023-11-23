// Obtén la referencia al enlace "Salir"
var salirLink = document.getElementById('salirLink');

// Agrega un event listener para el clic en el enlace
salirLink.addEventListener('click', function(e) {
    // Previene el comportamiento predeterminado del enlace
    e.preventDefault();

    // Muestra un mensaje de confirmación
    var confirmacion = confirm('¿Estás seguro de que deseas salir de la aplicación?');

    // Si el usuario confirma, redirige a la página de inicio
    if (confirmacion) {
        window.location.href = salirLink.getAttribute('href');
    }
    // Si el usuario cancela, no hace nada
});
