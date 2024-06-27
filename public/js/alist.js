document.addEventListener('DOMContentLoaded', () => {


    const volverAlist = document.getElementById('volverAlist');
    volverAlist.addEventListener('click', () => {
        window.location.href = alistamiento;
    });

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
    // CIERRA FUNCIÓN
    //BOTON BUSCAR
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
        $('#documento_propietario').val('');
        $('#ruta').val('');
        $('#soat').val('');
        $('#revision_tmc').val('');
        $('#extra_contra').val('');
        $('#tarjeta_operacion').val('');
        $('#km_actual').val('');
    }
    //CIERRA FUNCION
    //  document.getElementById('registrar').disabled=true;
    $(search).on('click', function () {
        var nroInterno = $('#nro_interno').val();
        var documento = $('#documento').val();
        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 3500

        }).then(function(){
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
                        $('#soat').val(data.soat);
                        $('#revision_tmc').val(data.revision_tmc);
                        $('#extra_contra').val(data.extra_contra);
                        $('#tj_operacion').val(data.tj_operacion);
                        let documentosAVencer = data.vence;

                        let nombresDocumentos = {
                            'soat': 'SOAT',
                            'revision_tmc': 'Revisión TMC',
                            'extra_contra': 'Extra Contra',
                            'tj_operacion': 'Tarjeta de Operación'
                        };
                        let documentosVencidos = Object.keys(documentosAVencer).filter(documento => documentosAVencer[documento] === "Vencido");
                        if (documentosVencidos.length > 0) {
                            let mensaje = 'Los siguientes documentos están vencidos:\n\n';
                            documentosVencidos.forEach((documento,index) => {
                                let nameDocument = nombresDocumentos[documento];
                                if (index === 0) {
                                    mensaje += `${nameDocument}`;
                                } else if (index === documentosVencidos.length - 1) {
                                    mensaje += ` y ${nameDocument}\n`;
                                } else {
                                    mensaje += `, ${nameDocument}`;
                                }
                            })
                            Swal.fire({
                                position: "center",
                                icon: "warning",
                                title: "Documento vencido",
                                text: mensaje,
                                showConfirmButton: true,
                                showCancelButton: true,
                                cancelButtonText: 'Cancelar',
                                confirmButtonText: 'Modificar',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    rutaEdit();
                                } else {
                                    // Si se cancela, redirigir a la vista de alistamiento
                                    window.location.href = alistamiento;
                                }
                            });
                        } else {
                            // No hay documentos vencidos, verificar si hay documentos próximos a vencer
                            let documentosProximosAVencer = Object.keys(documentosAVencer).filter(documento => documentosAVencer[documento] <= 5);

                            if (documentosProximosAVencer.length > 0) {
                                // Hay documentos próximos a vencer, mostrar la alerta
                                let mensaje = 'Los siguientes documentos están próximos a vencer:\n\n';
                                documentosProximosAVencer.forEach((documento,index) => {
                                    let nameDocument = nombresDocumentos[documento];
                                    let diasRestantes = documentosAVencer[documento];
                                    let diasMensaje = diasRestantes === 1 ? 'día' : 'días';
                                    let faltanMensaje = diasRestantes === 1 ? 'Falta' : 'Faltan'; // Verifica si es un día o más de uno
                                    if (index === 0) {
                                        mensaje += `${nameDocument.toUpperCase()}: ${faltanMensaje} ${diasRestantes} ${diasMensaje}`;
                                    } else if (index === documentosProximosAVencer.length - 1) {
                                        mensaje += ` y ${nameDocument.toUpperCase()}: ${faltanMensaje} ${diasRestantes} ${diasMensaje}\n`;
                                    } else {
                                        mensaje += `, ${nameDocument.toUpperCase()}: ${faltanMensaje} ${diasRestantes} ${diasMensaje}`;
                                    }
                                });
                                Swal.fire({
                                    position: "center",
                                    icon: "warning",
                                    title: "Documentos próximos a vencer",
                                    text: mensaje,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Modificar',
                                    showCancelButton: true,
                                    cancelButtonText: 'Cancelar',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        rutaEdit();
                                    }
                                });
                            }
                            else if (!data.soat && !data.revision_tcm && !data.extra_contra && !data.tj_operacion) {
                                // No hay documentos próximos a vencer ni documentos registrados, mostrar alerta y redirigir a la vista de alistamiento
                                Swal.fire({
                                    position: "center",
                                    icon: "error",
                                    title: "Sin documentos relacionados",
                                    text: "El vehículo no tiene documentos",
                                    showConfirmButton: true,
                                    confirmButtonText: 'Modificar o agregar',
                                    showCancelButton: true,
                                    cancelButtonText: 'Cancelar',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        rutaEdit();
                                    } else {
                                        window.location.href = alistamiento;
                                    }
                                });
                            }
                        }
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

        }).then(function(){
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

    $('#formCraete').submit(function (event) {
        event.preventDefault();
        var nroInterno = $('#nro_interno').val();
        var fecha_chequeo = $('#fecha_chequeo').val();
        var placa = $('#placa').val();
        var responsable = $('#id_usuario').val();
        var documento = $('#documento').val();
        var dato = $('#dato').val();


        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            showConfirmButton: false,
        });
        // if (!imagenEnviada) {
        //     Swal.fire('Firma requerida', 'Por favor, firma antes de enviar', 'info');
        //     return false;
        // }
        if (nroInterno == '') {
            Swal.fire('No hay Numero de interno', 'Por favor ingrese numero de interno', 'info');
            return false;
        }
        if (documento == '') {
            Swal.fire('No hay Numero de Documento', 'Por favor ingrese el documento del conductor', 'info');
            return false;
        }
        if (dato == '') {
            Swal.fire('No hay Numero de Conductor', 'Por favor Busque el documento por numero de interno', 'info');
            return false;
        }
        if (fecha_chequeo == '') {
            Swal.fire('No hay Fecha de Chequeo', 'Por favor ingrese  la fecha de Chequeo', 'info');
            return false;

        }
        if (placa == '') {
            Swal.fire('No hay Placa', 'Por favor Busque la placa por numero de interno', 'info');
            return false;

        }
        if (responsable === '' || responsable === null) {
            Swal.fire('No hay Responsable', 'Por favor Seleccione el responsable del alistamiento', 'info');
            return false;

        } else {
            $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr('action'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            text: 'Alistamiento Realizado correctamente',
                        }).then(function () {

                            window.location.href = alistamiento;
                            // Espera 2 segundos (ajusta según sea necesario)
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Alistamiento Existente',
                            text: data.message,
                        })
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al Realizar el Alistamiento',
                    });

                }
            });
        }

    });
});
