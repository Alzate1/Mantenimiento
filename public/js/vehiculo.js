function validarNum(inputValue) {
    var regex = /^[0-9]+$/;
    return !regex.test(inputValue);
}

document.addEventListener("DOMContentLoaded", () => {
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
    const openModal = document.getElementById('abrir')
    const modal = document.querySelector('.modalDad');
    if (openModal) {
        openModal.addEventListener('click', () => {
        modal.style.animationName = 'modalDad';
        modal.style.animationDuration = '1s';
        modal.classList.add('modal--show');
    });

    document.querySelector('.close').addEventListener('click', () => {
        modal.style.animationName = 'modalClose';
        modal.style.animationDuration = '1s'
        modal.classList.remove('modal--show');
    })
    window.addEventListener('click', function (e) {
        if (e.target == modal) {
            modal.style.animationName = 'modalClose';
            modal.style.animationDuration = '1s'
            modal.classList.remove('modal--show');
        }

    })
    }

    $('#saveGroup').on('click', function (e) {
        e.preventDefault();
        var desc_grupo = $('#desc_grupo').val();
        if (desc_grupo == '') {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Debes ingresar una descripción",
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        } else {
            $.ajax({
                url: '/guardar_grupo',
                type: 'post',
                data: {

                    'desc_grupo': desc_grupo
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.exitoso) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Grupo creado",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    window.location.href = createVehiculo;
                },
                error: function (error) {

                    alert('Error al crear el grupo');
                }
            })
        }

    });

        $('#documento_propietario, #nro_interno, #numconductor').on('blur', function () {
            var inputValue = this.value;
            if (validarNum(inputValue)) {
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Debes ingresar solo números",
                    html: "revisa los campos:<br> <strong>Doc.Propietario,Nro.Interno o Nro.Conducto</strong>",
                    showConfirmButton: true,

                });
               $(this).val('')
            }
        });
        const search = document.getElementById('search')
        const documento = document.getElementById('documento')
        documento.addEventListener('input', function () {
            if (documento.value.trim() !== '') {
                search.style.display = 'inline-block'
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

    $('#Registrar').on('click', function (e) {
        e.preventDefault();
        var documento_propietario = $('#documento_propietario').val();
        var placa = $('#placa').val();

        var id_grupo = $('#id_grupo').val();
        var nro_interno = $('#nro_interno').val();
        var documento = $('#documento').val();
        var id_ruta = $('#id_ruta').val();
        var soat = $('#soat').val();
        var revision_tmc = $('#revision_tmc').val();
        var extra_contra = $('#extra_contra').val();
        var tarjeta_operacion = $('#tarjeta_operacion').val();
        var km_actual = $('#km_actual').val();
        var tarjeta_propiedad = $('#tarjeta_propiedad').val();
        var documento = $('#documento').val();

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


        if (nro_interno == '') {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Debes ingresar el numero del interno",
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
        if (documento == '') {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Debes ingresar el documento del conductor",
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        }
        if (placa == '') {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Digite la placa Por favor",
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
        } if (revision_tmc == '') {
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
        } if (tarjeta_propiedad === null || tarjeta_propiedad === '') {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Seleccione sí tiene o no tarjeta de Propiedad",
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        }
          else {
            $.ajax({
                url: '/crear_vehiculo',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'placa': placa,
                    'documento_propietario': documento_propietario,
                    'id_grupo': id_grupo,
                    'nro_interno': nro_interno,
                    'documento': documento,
                    'id_ruta': id_ruta,
                    'soat':soat,
                    'revision_tmc':revision_tmc,
                    'extra_contra':extra_contra,
                    'tarjeta_operacion':tarjeta_operacion,
                    'km_actual':km_actual,
                    'tarjeta_propiedad':tarjeta_propiedad,
                },


                success: function (data) {
                    if (data.exitoso) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "vehiculo creado",
                            showConfirmButton: true,
                            timer: 1500
                        }).then(function() {

                            window.location.href = vehiculo;
                        })

                    }else{
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Error al crear el vehículo",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                },error:function (error) {
                    if (error.responseJSON && error.responseJSON.errors) {
                        if (error.responseJSON.errors.placa) {
                            Swal.fire({
                                position: "center",
                                icon: "error",
                                title: 'Numero de placa Existente',
                                text:'Por favor digite una nueva',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        if (error.responseJSON.errors.nro_interno) {
                            Swal.fire({
                                position: "center",
                                icon: "error",
                                title: 'Numero de interno Existente',
                                text:'Por favor digite uno nuevo',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }


                    }else {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Error al crear el vehículo",
                            showConfirmButton: false,
                            timer: 1500
                        });

                    }
                    return false;
                }
            })
        }
        return false;
    });


});
