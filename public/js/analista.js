document.addEventListener('DOMContentLoaded', function () {
    const openModal = document.querySelectorAll('.abrir')
    const modal = document.querySelector('.modalDad');
    const close = document.querySelectorAll('.cerrar');
    const openModal3 = document.querySelectorAll('.viewDesc')
    const modal3 = document.querySelector('.modalDad3');
    const openModal2 = document.querySelectorAll('.editMotorista')
    const modal2 = document.querySelector('.modalDad2');
    const cerrar = document.getElementById('cerrar');
    const cerrarMod2 = document.querySelectorAll('.cerrarMod2')
    openModal.forEach(openModals => {
        openModals.addEventListener('click', () => {
            modal.style.animationName = 'modalDad';
            modal.style.animationDuration = '1s';
            modal.classList.add('modal--show');
        });
    });


    openModal2.forEach(openModals => {
        openModals.addEventListener('click', () => {
            modal2.style.animationName = 'modalDad2';
            modal2.style.animationDuration = '1s';
            modal2.classList.add('modal--show2');
        });
    });
    cerrarMod2.forEach(closeModal => {
        closeModal.addEventListener('click', () => {
            modal2.style.animationName = 'modalClose2';
            modal2.style.animationDuration = '1s'
            modal2.classList.remove('modal--show2');

        });
    });

    function viewDes(id) {
        $.ajax({
            type: "GET",
            url:"/route/date/desc/" + id,
            success: function (response) {
                if (response.success) {
                    if (response.desc) {

                        $('#description').text(response.desc);
                    }if (response.items) {
                        var itemsListHtml = '';
                        response.items.forEach(function(item) {
                            itemsListHtml += '<li class="list-group-item">' + item + '</li>';
                        });
                        $('#itemsList').html(itemsListHtml);
                        if(response.estado==0){
                            $('input[name="state"][value="0"]').prop('checked', true);
                        }else{
                            $('input[name="state"][value="1"]').prop('checked', true);
                        }
                        $('input[name="state"]').off('change').on('change', function() {

                            updateEstado(id);
                        });
                    }
                }else{
                    console.error('Error al obtener la descripción');
                }
            }
        });
    }
    function updateEstado(id) {
        var newEstado = $('input[name="state"]:checked').val(); // Obtener el nuevo estado desde el radio seleccionado

        $.ajax({
            type: "POST", // Cambiar a POST si no se está usando PUT en el backend
            url: "/route/date/update-state/" + id,
            data: {
                estado: newEstado,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Informe actualizado",
                        text: 'Informe actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        // Desmarcar todos los radios
                        $('input[name="state"]').prop('checked', false);
                        // Marcar el radio correspondiente al nuevo estado
                        $('input[name="state"][value="' + response.estado + '"]').prop('checked', true);
                        location.reload();
                    });
                } else {
                    console.error('Error al actualizar el estado');
                }
            },
            error: function(error) {
                console.error('Error en la solicitud:', error);
            }
        });
    }

    openModal3.forEach(openModals => {
        openModals.addEventListener('click',function (){
            var id = $(this).data('id');
            $('#description').empty();
            $('#itemsList').empty();
            Swal.fire({
                title: 'Cargando...',
                text: 'Por favor Esperar',
                timer: 3500,
                showConfirmButton: false,
            }).then(function(){
                viewDes(id)
            })
            modal3.style.animationName = 'modalDad3';
            modal3.style.animationDuration = '1s';
            modal3.classList.add('modal--show3');
        })
    });
    cerrar.addEventListener('click', () => {
        modal3.style.animationName = 'modalClose3';
        modal3.style.animationDuration = '1s'
        modal3.classList.remove('modal--show3');
        $('#description').empty();
        $('#itemsList').empty();
    });
    close.forEach(closeModal => {
        closeModal.addEventListener('click', () => {
            modal.style.animationName = 'modalClose';
            modal.style.animationDuration = '1s'
            modal.classList.remove('modal--show');
        });
    });


    var tableAlist = $('#tableAnalista');
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
    $('#pos,#interno,#responsable,#fecha,#ruta,#descrip').on('click', function (e) {

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


        $('#pos,#interno,#responsable,#fecha,#ruta,#descrip').not(column).data('order', null);
        $('#pos,#interno,#responsable,#fecha,#ruta,#descrip').find('i').removeClass().addClass('fas fa-sort');


        column.find('i').removeClass().addClass(order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down');

        column.data('order', order === 'asc' ? 'desc' : 'asc');

    });


    // var edit = $('.editMotorista')
    // edit.on('click', function () {
    //     var id = $(this).data('id');
    //    $.ajax({
    //     type: "GET",
    //     url: "/Motorista/Modificar/" + id,
    //     success: function (response) {
    //         $('input[name="nombreUpdate"]').val(response.nombre);
    //         $('input[name="apellidoUpdate"]').val(response.apellido);
    //         $('input[name="documentoUpdate"]').val(response.documento);

    //     }
    //    });
    // })

    //    $('#formUpdate').on('submit', function(e){
    //          e.preventDefault();
    //          var id = $('.editMotorista').data('id');
    //          var formData = $(this).serialize();
    //          $.ajax({
    //             type: "PUT",
    //             url: "/Motorista/Modificar/" + id,
    //             data: formData,
    //             success: function (data) {
    //                if (data.success) {
    //                     Swal.fire({
    //                         title: 'Cargando...',
    //                         allowOutsideClick: false,
    //                         showConfirmButton: false,
    //                         timer: 3500

    //                     }).then(function () {
    //                         Swal.fire({
    //                             icon: 'success',
    //                             title: 'Correcto',
    //                             text: 'Motorista actualizado correctamente',
    //                         }).then(function () {

    //                             location.reload();
    //                             // Espera 2 segundos (ajusta según sea necesario)
    //                         });
    //                     })

    //                 }
    //             }, error: function (error) {
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Error',
    //                     text: 'Error al actualizar',
    //                 })

    //                 // Manejar errores si es necesario
    //             }
    //          });
    //     })

    const checkCorinto = document.getElementById("checkCorinto");
    const filterForm = document.getElementById("filter_form");
    const checkPalmira = document.getElementById("checkPalmira");
    const filterFormp = document.getElementById("filter_formp");
    const checkMensual = document.getElementById("checkMensual");
    const filterFormMen = document.getElementById("filter_formMen");
    const checkTrimestral = document.getElementById("checkTrimestral");
    const filter_formTri = document.getElementById("filter_formTri");
    const checkAnual = document.getElementById("checkAnual");
    const filter_formAn = document.getElementById("filter_formAn");


    checkCorinto.addEventListener("change", (e) => {
        e.preventDefault(); // Previene el envío inmediato del formulario
        Swal.fire({
            title: 'Cargando...',
            text: 'Por favor Esperar',
            timer: 3500,
            showConfirmButton: false,
        }).then(function(){
            filterForm.submit();
        })
    })
    checkPalmira.addEventListener("change", (e) => {
        e.preventDefault(); // Previene el envío inmediato del formulario
        Swal.fire({
            title: 'Cargando...',
            text: 'Por favor Esperar',
            timer: 3500,
            showConfirmButton: false,
        }).then(function(){
            filterFormp.submit();
        })
    })
    checkMensual.addEventListener("change", (e)=>{
        e.preventDefault(); // Previene el envío inmediato del formulario
        Swal.fire({
            title: 'Cargando...',
            text: 'Por favor Esperar',
            timer: 3500,
            showConfirmButton: false,
        }).then(function(){
            filterFormMen.submit();
        })
    })
    checkTrimestral.addEventListener("change", (e)=>{
        e.preventDefault(); // Previene el envío inmediato del formulario
        Swal.fire({
            title: 'Cargando...',
            text: 'Por favor Esperar',
            timer: 3500,
            showConfirmButton: false,
        }).then(function(){
            filter_formTri.submit();
        })
    })
    checkAnual.addEventListener("change", (e)=>{
        e.preventDefault(); // Previene el envío inmediato del formulario
        Swal.fire({
            title: 'Cargando...',
            text: 'Por favor Esperar',
            timer: 3500,
            showConfirmButton: false,
        }).then(function(){
            filter_formAn.submit();
        })
    })

    $(document).on('click','#deleteInfo', function(){
        var id = $(this).data('id');
        var interno = $(this).data('interno');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: "btn btn-success",
              cancelButton: "btn btn-danger"
            },
          });
          swalWithBootstrapButtons.fire({
            title: "¿Estas Seguro?",
            html: `<p> Este cambio es irreversible. <br>Eliminaras la bitácora del vehículo <strong>` +interno+`<strong> </p>` ,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Si, Borrar",
            cancelButtonText: "No, cancelar",
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    type: "delete",
                    url: "/route/delete/"+ id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un error',
                            })
                        }else{
                            Swal.fire({
                                title: 'Cargando...',
                                text: 'Por favor Esperar',
                                timer: 2500,
                            }).then(function(){
                                swalWithBootstrapButtons.fire({
                                    title: "Borrado",
                                    text: "La bitacora se ha Eliminado",
                                    icon: "success",
                                    showConfirmButton: false,
                                  }).then(function(){

                                      location.reload();
                                  })
                            })

                        }
                    }
                });

            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
              swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "No borraste el infome",
                icon: "error"
              });
            }
          });
    })

});
