
var banner = document.querySelector('.banner');
var contenido = document.getElementById('contenido');

if (contenido) { // Verifica si el elemento contenido existe
  // Guarda el ancho original del elemento contenido
  var anchoOriginal = getComputedStyle(contenido).getPropertyValue('width');

  banner.addEventListener('mouseenter', () => {
    // Cambia el ancho del elemento contenido cuando el mouse entra
    contenido.style.width = '100%';
  });

  banner.addEventListener('mouseleave', () => {
    // Restaura el ancho original cuando el mouse sale
    contenido.style.width = anchoOriginal;
  });
}

Open_menu.addEventListener('change', function () {
  if (this.checked) {
    banner.style.display = 'block';
    setTimeout(() => {
      banner.style.opacity = '1';


    }, 10);
  } else {
    banner.style.opacity = '0';
    setTimeout(() => {
      banner.style.display = 'none';

    }, 300);
  }

});

var exitButtons = document.querySelectorAll('.exit');

// Agrega un evento a todos los botones con la clase "exit-button"
exitButtons.forEach(function (button) {
  button.addEventListener('click', function () {
    Swal.fire({
      title: "¿cerrar sesión ?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, cerrar",
      cancelButtonText: "Cancelar"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
            url: "/logOut",
            type: 'get',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
              window.location=ruta;
            }
        });
      }
    });
  });
});
