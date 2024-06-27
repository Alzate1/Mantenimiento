document.addEventListener('DOMContentLoaded', function () {
    const openModal = document.querySelectorAll('.abrir')
    const modal = document.querySelector('.modalDad');
    const close = document.querySelectorAll('.cerrar');
    openModal.forEach(openModals => {
        openModals.addEventListener('click', () => {
            modal.style.animationName = 'modalDad';
            modal.style.animationDuration = '1s';
            modal.classList.add('modal--show');
        });
    });
    const openModal2 = document.querySelectorAll('.editMotorista')
    const modal2 = document.querySelector('.modalDad2');

    openModal2.forEach(openModals => {
        openModals.addEventListener('click', () => {
            modal2.style.animationName = 'modalDad2';
            modal2.style.animationDuration = '1s';
            modal2.classList.add('modal--show2');
        });
    });
    close.forEach(closeModal => {
        closeModal.addEventListener('click', () => {
            modal.style.animationName = 'modalClose';
            modal.style.animationDuration = '1s'
            modal.classList.remove('modal--show');
            modal2.style.animationName = 'modalClose';
            modal2.style.animationDuration = '1s'
            modal2.classList.remove('modal--show2');
        });
    });

    window.addEventListener('click', function (e) {
        if (e.target == modal2) {
            modal2.style.animationName = 'modalClose2';
            modal2.style.animationDuration = '1s'
            modal2.classList.remove('modal--show2');
        }

    })



    var tableAlist = $('#tableMotorista');
    var inputBusqueda = $('#searchData');
    var filaNoResultados = $('#filaNoResultados');

    var filas = tableAlist.find('tbody tr');

    inputBusqueda.on('input', function () {
        var valorBusqueda = inputBusqueda.val().toLowerCase();

        var filasFiltradas = filas.filter(function () {
            return $(this).text().toLowerCase().indexOf(valorBusqueda) > -1;
        });

        filaNoResultados.toggle(filasFiltradas.length === 0);

        filas.hide();
        filasFiltradas.show();
    });
    $('form').submit(function (e) {
        e.preventDefault();
    });
    $('#pos,#nombre,#apellido,#documento').on('click', function (e) {
        var column = $(this);
        var order = column.data('order') || 'asc';
        var indexColmn = column.index();
        var orderedRow = filas.toArray().sort(function (filaA, filaB) {
            var valueA = $(filaA).find('td').eq(indexColmn).text();
            var valueB = $(filaB).find('td').eq(indexColmn).text();
            return (order === 'asc' ? 1 : -1) * valueA.localeCompare(valueB);
        });


        filas.detach().sort(function (filaA, filaB) {
            var valueA = $(filaA).find('td').eq(indexColmn).text();
            var valueB = $(filaB).find('td').eq(indexColmn).text();
            return (order === 'asc' ? 1 : -1) * valueA.localeCompare(valueB);
        }).appendTo(tableAlist.find('tbody'));


        $('#pos,#nombre,#apellido,#documento').not(column).data('order', null);
        $('#pos,#nombre,#apellido,#documento').find('i').removeClass().addClass('fas fa-sort');


        column.find('i').removeClass().addClass(order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down');

        column.data('order', order === 'asc' ? 'desc' : 'asc');

    });

    $('#formCreate').submit(function (event) {
        event.preventDefault();
        $.ajax({
            method: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (data) {
                if (data.documentError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Documento existente.',
                        text: 'Este Documento ya está registrado. ',
                    });
                }
                else if (data.exitoso) {
                    Swal.fire({
                        title: 'Cargando...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 3500

                    }).then(function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            text: 'Motorista registrado correctamente',
                        }).then(function () {

                            location.reload();
                            // Espera 2 segundos (ajusta según sea necesario)
                        });
                    })

                }
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al registrar el usuario',
                });
            }

        });
    });

    var edit = $('.editMotorista')
    edit.on('click', function () {
        var id = $(this).data('id');
       $.ajax({
        type: "GET",
        url: "/Motorista/Modificar/" + id,
        success: function (response) {
            $('input[name="nombreUpdate"]').val(response.nombre);
            $('input[name="apellidoUpdate"]').val(response.apellido);
            $('input[name="documentoUpdate"]').val(response.documento);

        }
       });
    })

   $('#formUpdate').on('submit', function(e){
         e.preventDefault();
         var id = $('.editMotorista').data('id');
         var formData = $(this).serialize();
         $.ajax({
            type: "PUT",
            url: "/Motorista/Modificar/" + id,
            data: formData,
            success: function (data) {
               if (data.success) {
                    Swal.fire({
                        title: 'Cargando...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 3500

                    }).then(function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            text: 'Motorista actualizado correctamente',
                        }).then(function () {

                            location.reload();
                            // Espera 2 segundos (ajusta según sea necesario)
                        });
                    })

                }
            }, error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al actualizar',
                })

                // Manejar errores si es necesario
            }
         });
    })
   


})
