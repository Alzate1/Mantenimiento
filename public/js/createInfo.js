document.addEventListener("DOMContentLoaded", () => {
    //BLOQUE PARA LA ANIMACIÓN DE SUBIR Y BAJAR LOS SUAMMARY
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
    //CIERRA BLOQUE

    //BLOQUE PARA MOSTRA U OCULTA LOS ITEMS
    $('#saveItem').on('click', function (e) {
        var item = $('#newitem').val();
        var descripcion = $('#desc_item').val();
        e.preventDefault();
        if (item === '' || item === null) {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Debes ingresar el nombre del item",
                showConfirmButton: false,
                timer: 2500
            });
            return false
        } else {
            $.ajax({
                type: "POST",
                url: "/crear/item",
                data: {
                    newitem: item,
                    desc_item: descripcion
                }, headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, success: function (data) {
                    if (data.success) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Item creado",
                            showConfirmButton: false,
                            timer: 2500
                        }).then(function () {

                            location.reload();
                        })
                    }
                }, error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: error.error,
                        // showConfirmButton: false,
                        // timer: 2500
                    });
                }
            });
        }
    })
    document.getElementById('idTipoInfo').addEventListener('change', function() {
        var tipoInforme = this.value;

        // Mostrar u ocultar elementos según el tipo de informe seleccionado
        if (tipoInforme === '1' || tipoInforme === '2' || tipoInforme === '3' || tipoInforme === '4') { // Aquí '1' es un ejemplo de identificador de tipo de informe
            // $('.itemselect:checked').val('')
            document.getElementById('div-check').style.display = 'block';
            // $('.itemselect:checked').val()
        } else { // Para otros tipos de informes, ajusta según necesites
            document.getElementById('div-check').style.display = 'none';
        }
    });
    //CIERRA BLOQUE
    //BLOQUE PARA EL MODAL DE CREAR LOS ITEMS
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
    //CIERRA BLOQUE

    //BLOQUE  PARA BUSQUEDA POR INTERNO
    const search = document.getElementById('search')
    const nroInterno = document.getElementById('nro_interno');
    const documento = document.getElementById('documento')
    if (nroInterno || documento) {
        documento.readOnly = true
        documento.style.backgroundColor = '#eceeef'

        nroInterno.addEventListener('input', function () {
            if (nroInterno.value.trim() !== '') {
                search.style.display = 'inline-block'
                documento.readOnly = false
                documento.style.backgroundColor = ''
                restDriver();
            } else {
                search.style.display = 'none'
                $('#placa').val('');
                documento.readOnly = true;
                restDriver();
                documento.style.backgroundColor = '#eceeef'
            }
        });
        documento.addEventListener('input', function () {
            if (documento.value.trim() !== '') {
                search.style.display = 'inline-block'
            } else {
                search.style.display = 'none'
                $('#dato').val('');
                $('#documento').val('');

            }
        });
    }
    function restDriver() {
        $('#placa').val('');
        $('#dato').val('');
        $('#documento').val('');
    }

    $(search).on('click', function () {
        var nroInterno = $('#nro_interno').val();
        var documento = $('#documento').val();
        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 3500

        }).then(function () {
            $.ajax({
                type: "GET",
                url: "/busqueda/interno/" + nroInterno,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                        $('#placa').val('');
                        $('#nro_interno').val('')
                        document.getElementById('search').style.display = 'none';
                        document.getElementById('documento').readOnly = true;
                        document.getElementById('documento').style.backgroundColor = '#eceeef'
                    } else {
                        $('#placa').val(data.placa);
                        $('#dato').val(data.nombre + ' ' + data.apellido);
                        $('#documento').val(data.documento);
                        $('#idVehi').val(data.idVehi);
                    }
                }, error: function () {
                    Swal.fire('Error', 'Error al buscar el número de interno');
                }
            });
        })


        // Swal.fire('No hay Numero de interno', 'Por favor ingrese numero de interno','info');
        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 3500

        }).then(function () {
            if (documento !== '') {
                $.ajax({
                    type: "GET",
                    url: "/busqueda/documento/" + documento,
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
            }
        })



    })
    //CIERRA BLOQUE

    // FUNCIÓN PARA GUARDA DATOS
    $('#formCreate').submit(function (event) {
        event.preventDefault();
        var desc = $("#desc").val().trim();
        var fecha = $("#date").val();
        var nroInterno = $('#nro_interno').val();
        var idTipoInfo = $('#idTipoInfo').val();
        var item = $('.form-check-input:checked').length > 0;
        if (nroInterno === '' || nroInterno === null) {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Debes ingresar el numero de interno",
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        } if (fecha === '' || fecha === null) {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Debes ingresar la fecha",
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        } if (idTipoInfo == '' || idTipoInfo == null  || idTipoInfo == 'Tipo de Informe') {
            Swal.fire({
                icon: 'info',
                title: 'Seleccionar el Informe',
                text: 'Debe seleccionar al menos un tipo de Informe.',
            });
            return false;
        }if (!item) {
            Swal.fire({
                icon: 'info',
                title: 'Seleccionar Item',
                text: 'Debe seleccionar al menos un item.',
            });
            return false;
        }
         if (desc === '' || desc === null) {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Debes ingresar la descripción",
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        }
        else {
            $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr('action'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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
                                text: 'Informe registrado correctamente',
                                showCancelButton: true,
                                cancelButtonText:'Cancelar',
                                confirmButtonText: "Crear nuevo informe"
                            }).then((result)=> {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }else{
                                        window.location.href = analista
                                    }
                            });
                        })

                    } if (data.notVehicle) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No existe el numero de Interno',
                        });
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al registrar el Informe',
                    });
                }

            });
        }

    });
})
