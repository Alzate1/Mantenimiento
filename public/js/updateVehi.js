document.addEventListener("DOMContentLoaded", ()=> {
      const summaryElements = document.querySelectorAll('summary');
    const downArrows = document.querySelectorAll('.iconoBajar');
    const upArrows = document.querySelectorAll('.iconoSubir');

    summaryElements.forEach((summaryElement, index) => {
      const downArrow = downArrows[index];
      const upArrow = upArrows[index];
      let isOpen = false; // Inicialmente cerrado

      summaryElement.addEventListener('click', () => {
        isOpen = !isOpen;

        if (isOpen) {
          downArrow.style.display = 'none';
          upArrow.style.display = 'inline';
        } else {
          downArrow.style.display = 'inline';
          upArrow.style.display = 'none';
        }
      });
    });

        const volverVehi = document.getElementById('volverVehi');
        if (volverVehi) {
            volverVehi.addEventListener('click', () => {
                window.location.href = vehiculo;
            });
        }
        const search = document.getElementById('search')
        const documento = document.getElementById('documento')
        documento.addEventListener('input', function () {
            if (documento.value.trim() !== '') {
                search.style.display = 'inline-block'
                $('#dato').val('');
              
            } else {
                search.style.display = 'none'
                $('#dato').val('');
                $('#documento').val('');

            }
        });
        $(search).on('click', function () {
            var documento = $('#documento').val();
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 3500

            }).then(function(){
                $.ajax({
                    type: "GET",
                    url: "/busqueda/motorista/" + documento,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.error) {
                            Swal.fire('Error', data.error, 'error');
                            $('#dato').val('');
                            $('#documento').val('')
                        } else {
                            $('#dato').val(data.nombre + ' ' + data.apellido);
                            $('#documento').val(data.documento)

                            // Habilitar cualquier lógica adicional aquí
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Error al buscar el Documento del motorista');
                    }
                });
            })

        })
        $('#formUpdate').submit(function(event) {
            event.preventDefault();
            var documento_propietario = $('#documento_propietario').val();
            var nombre_motorista=$('#dato').val();
            var numconductor = $('#documento').val();
            var nro_interno = $('#nro_interno').val();
            var id_ruta = $('#id_ruta').val();
            var soat = $('#soat').val();
            var revision_tmc = $('#revision_tmc').val();
            var extra_contra = $('#extra_contra').val();
            var tarjeta_operacion = $('#tarjeta_operacion').val();
            var km_actual = $('#km_actual').val();
            if (documento_propietario == '') {
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Debes ingresar el documento de propietario",
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            if (id_ruta === null || id_ruta === '') {
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Debes seleccionar una ruta",
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            if (nro_interno == '') {
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Debes ingresar el numero de interno",
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            if (numconductor == '') {
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Debes ingresar el documento del conductor",
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            if (soat == '') {
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Digite cuando vence el SOAT Por favor",
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            if (revision_tmc == '') {
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Digite el vencimiento de  REVISIÓN TCM Por favor",
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            if (extra_contra == '') {
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Digite el vencimiento EXTRA/CONTRA Por favor",
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }
            if (tarjeta_operacion == '') {
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Digite el Vencimiento TARJETA OPERACIÓN Por favor",
                    showConfirmButton: false,
                    timer: 1500
                });
                return false;
            }else {
                Swal.fire({
                    title: 'Cargando...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                });
                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Correcto',
                                text: 'Vehiculo actualizado correctamente',
                            }).then(function() {
                                window.location.href = vehiculo;
                            });
                        } else {
                            // Error: Mostrar SweetAlert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un error al actualizar el Vehiculo',
                            });
                        }
                    },
                    error: function(error) {
                        if (error.responseJSON && error.responseJSON.errors) {
                            if (error.responseJSON.errors.nro_interno) {
                                Swal.fire({
                                    position: "center",
                                    icon: "error",
                                    title: 'Numero de interno Existente',
                                    text: 'Por favor digite uno nuevo',
                                    showConfirmButton: false,
                                    timer: 2500
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un error al actualizar el vehiculo',
                            });
                        }
                        // Puedes manejar errores aquí si es necesario
                    }
                });
            }
        });
});
