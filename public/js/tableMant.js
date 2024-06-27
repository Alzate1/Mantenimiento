document.addEventListener("DOMContentLoaded", function () {
    const openModal = document.querySelectorAll('.abrir')
    const modal = document.querySelector('.modalDad');

    openModal.forEach(openModals => {
        openModals.addEventListener('click', () => {
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 2500

            }).then(function () {
                modal.style.animationName = 'modalDad';
                modal.style.animationDuration = '1s';
                modal.classList.add('modal--show');
            })

        });
    });
    var close = document.querySelector('.close')
    if (close) {
        close.addEventListener('click', () => {
            modal.style.animationName = 'modalClose';
            modal.style.animationDuration = '1s'
            modal.classList.remove('modal--show');
        })
        document.querySelector('.close2').addEventListener('click', () => {
            modal2.style.animationName = 'modalClose2';
            modal2.style.animationDuration = '1s'
            modal2.classList.remove('modal--show2');
        })

        document.querySelector('.close3').addEventListener('click', () => {
            modal3.style.animationName = 'modalClose3';
            modal3.style.animationDuration = '1s'
            modal3.classList.remove('modal--show3');
        })

        document.querySelector('.close4').addEventListener('click', () => {

            modal4.style.animationName = 'modalClose4';
            modal4.style.animationDuration = '1s'
            modal4.addEventListener('animationend', function handleAnimationEnd() {
                // Este código se ejecutará cuando la animación haya terminado
                modal4.removeEventListener('animationend', handleAnimationEnd); // Evitar fugas de memoria

                viewFiles.innerHTML = '';
                labelTitle.innerHTML = '';
            });
            modal4.classList.remove('modal--show4');
        })
    }

    window.addEventListener('click', function (e) {
        if (e.target == modal) {
            modal.style.animationName = 'modalClose';
            modal.style.animationDuration = '1s'
            modal.classList.remove('modal--show');
        }

    })

    const openModal2 = document.querySelectorAll('.addRev')
    const modal2 = document.querySelector('.modalDad2');

    openModal2.forEach(openModals => {
        openModals.addEventListener('click', () => {
            modal.style.animationName = 'modalClose';
            modal.style.animationDuration = '1s'
            modal.classList.remove('modal--show');
            modal2.style.animationName = 'modalDad2';
            modal2.style.animationDuration = '1s';
            modal2.classList.add('modal--show2');
        });
    });
    var close = document.querySelector('.close')


    window.addEventListener('click', function (e) {
        if (e.target == modal2) {
            modal2.style.animationName = 'modalClose2';
            modal2.style.animationDuration = '1s'
            modal2.classList.remove('modal--show2');
        }

    })


    $('#formServ').submit(function (event) {
        event.preventDefault();
        var centroRev = $('#centroRev').val();
        var fecha = $('#fechaRev').val();
        var detailsRev = $('#detailsRev').val();

        if (fecha == '') {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "No hay fecha!",
                text: "Debes ingresar la fecha en la cual se hace el mantenimiento",
                showConfirmButton: true,

            });
            return false;
        }
        if (centroRev == '') {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "No hay Centro",
                text: "Ingrese el sitio donde se hizo la revisión",
                showConfirmButton: true,

            });
            return false;
        }
        if (detailsRev == '') {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "No hay Detalle",
                text: "Ingrese el detalle o tipo de revisión que se hizo",
                showConfirmButton: true,

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
                                text: 'Servicio registrado correctamente',
                            }).then(function () {
                                Swal.fire({
                                    title: 'Cargando...',
                                    allowOutsideClick: false,
                                    showConfirmButton: false,
                                    timer: 3500

                                }).then(function () {
                                    const modal = document.querySelector('.modalDad');
                                    const modal2 = document.querySelector('.modalDad2');
                                    if (data.revCorrectiva && data.revCorrectiva.length > 0) {
                                        $('#tableRev tbody').empty();
                                        // Iterar sobre los nuevos datos de revisión correctiva y agregar filas a la tabla
                                        $.each(data.revCorrectiva.reverse(), function (index, rev) {
                                            var row = '<tr>' +
                                                '<td style="' + (rev.estado == 2 ? 'color:red;' : '') + '" ' + (rev.estado == 2 ? 'title="Archivos: pendiente"' : '') + '>' + (index + 1) + '</td>' +
                                                '<td>' + rev.fecha + '</td>' +
                                                '<td>' + rev.centro_especializado + '</td>' +
                                                '<td>' + rev.detalle_mantenimiento + '</td>' +
                                                '<td>' +
                                                '<a type="button" id="subir" class="subir" data-id-correctiva="' + rev.idcorreccion + '">' +
                                                '<i class="bi bi-arrow-up-square Objets"></i>' +
                                                '</a>' +
                                                '</td>' +
                                                '<td>' +
                                                '<a type="button" id="verArch" class="verArch" data-id-correctiva="' + rev.idcorreccion + '">' +
                                                '<i class="bi bi-file-earmark Objets bi bi-arrow-up-square Objets"></i>' +
                                                '</a>' +
                                                '</td>' +
                                                '</tr>';
                                            if (rev.estado == 2) {
                                                row = '<tr class="estado-dos">' + row + '</tr>';
                                            }
                                            $('#tableRev tbody').prepend(row);
                                        });

                                        // Mostrar la tabla de revisión correctiva y ocultar otros elementos según sea necesario
                                        modal2.style.animationName = 'modalClose2';
                                        modal2.style.animationDuration = '1s'
                                        modal2.classList.remove('modal--show2');

                                        modal.style.animationName = 'modalDad';
                                        modal.style.animationDuration = '1s';
                                        modal.classList.add('modal--show');
                                        $('#centroRev').val('');
                                        $('#fechaRev').val('');
                                        $('#detailsRev').val('');
                                        var close = document.querySelector('.close')
                                        if (close) {
                                            close.addEventListener('click', () => {
                                                modal.style.animationName = 'modalClose';
                                                modal.style.animationDuration = '1s'
                                                modal.classList.remove('modal--show');
                                                location.reload();
                                            })
                                            document.querySelector('.close2').addEventListener('click', () => {
                                                modal2.style.animationName = 'modalClose2';
                                                modal2.style.animationDuration = '1s'
                                                modal2.classList.remove('modal--show2');
                                                location.reload();

                                            })
                                        }
                                    }
                                });


                            });
                        })

                    } else {
                        // Error: Mostrar SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error al crear el servicio',
                        });
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al crear el servicio',
                    });

                }
            });
        }


    });
    $(document).on('click', '#ver', function () {
        var idrevision = $(this).data('revision-id');
        $('#id_revision').val(idrevision);

        var nroInterno = $(this).data('nro-interno');
        $('#strongInterno').text(nroInterno);
        $.ajax({
            url: '/tabla/revision/' + idrevision,
            method: 'GET',
            success: function (data) {

                $('#revTable tbody').empty();
                if (data.revCorrectiva.length > 0) {
                    $.each(data.revCorrectiva, function (index, rev) {
                        var row = '<tr>' +
                            '<td style="' + (rev.estado == 2 ? 'color:red;' : '') + '" ' + (rev.estado == 2 ? 'title="Archivos: pendiente"' : '') + '>' + (index + 1) + '</td>' +
                            '<td>' + rev.fecha + '</td>' +
                            '<td>' + rev.centro_especializado + '</td>' +
                            '<td>' + rev.detalle_mantenimiento + '</td>' +
                            '<td>' +
                            '<a type="button" id="subir" class="subir" data-id-correctiva="' + rev.idcorreccion + '">' +
                            '<i class="bi bi-arrow-up-square Objets"></i>' +
                            '</a>' +
                            '</td>' +
                            '<td>' +
                            '<a type="button" id="verArch" class="verArch" data-id-correctiva="' + rev.idcorreccion + '">' +
                            '<i class="bi bi-file-earmark Objets bi bi-arrow-up-square Objets"></i>' +
                            '</a>' +
                            '</td>' +
                            '</tr>';
                        $('#revTable tbody').append(row)
                        // 'data-nro-interno="' + $revisionInfo->nro_interno + '">' +
                    });
                    $('#tableRev').show();

                } else {
                    $('#tableRev tbody').html('<tr><td colspan="6" class="text-center">No hay revisiones correctivas disponibles</td></tr>');
                    $('#tableRev').show();
                }

            },
            error: function (error) {
                console.error('Error al cargar las revisiones correctivas:', error);
            }
        });
        $('#addRev').on('click', function () {
            $('#strongInterno2').text(nroInterno);
        })
    });
    $('#revTable tbody').on('click', '.subir', function () {
        const modal4 = document.querySelector('.modalDad4');
        modal4.style.animationName = 'modalDad4';
        modal4.style.animationDuration = '1s';
        modal4.classList.add('modal--show4');




        window.addEventListener('click', function (e) {
            if (e.target == modal4) {
                modal4.style.animationName = 'modalClose4';
                modal4.style.animationDuration = '1s'
                modal4.classList.remove('modal--show4');
                viewFiles.innerHTML = '';
                labelTitle.innerHTML = '';
            }

        })
        modal.style.animationName = 'modalClose';
        modal.style.animationDuration = '1s'
        modal.classList.remove('modal--show');

    });
    $('#revTable tbody').on('click', '.verArch', function () {
        const modal3 = document.querySelector('.modalDad3');
        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 2000

        }).then(function () {
            modal3.style.animationName = 'modalDad3';
            modal3.style.animationDuration = '1s';
            modal3.classList.add('modal--show3');
        })
        window.addEventListener('click', function (e) {
            if (e.target == modal3) {
                modal3.style.animationName = 'modalClose3';
                modal3.style.animationDuration = '1s'
                modal3.classList.remove('modal--show3');
            }

        })

    });
    $(document).on('click', '#subir', function () {

        var nroInterno = $(this).data('nro-interno');
        $('#strongInterno4').text(nroInterno);
        // var idrevision = $(this).data('revision-id');
        // $('#idrev').val(idrevision);
        var idCorreccion = $(this).data('id-correctiva');
        $('#idrev').val(idCorreccion);
        $('#registrar').val(idCorreccion);

    });
    //FUNCIÓN DE BUSQUEDA
    var tableMant = $('#tableMant');
    var inputBusqueda = $('#searchData');
    var filaNoResultados = $('#filaNoResultados');

    var filas = tableMant.find('tbody tr');

    inputBusqueda.on('input', function () {
        var valorBusqueda = inputBusqueda.val().toLowerCase();

        var filasFiltradas = filas.filter(function () {
            return $(this).text().toLowerCase().indexOf(valorBusqueda) > -1;
        });

        filaNoResultados.toggle(filasFiltradas.length === 0);

        filas.hide();
        filasFiltradas.show();
    });

    //FUNCION EL ORDEN DE LOS DATOS AL DARL CLICK A LOS TH
    $('#pos,#placa,#NumeroInterno,#motorista,#fecha,#hora,#ruta').on('click', function (e) {
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
        }).appendTo(tableMant.find('tbody'));


        $('#pos,#placa,#NumeroInterno,#motorista,#fecha,#hora,#ruta').not(column).data('order', null);
        $('#pos,#placa,#NumeroInterno,#motorista,#fecha,#hora,#ruta').find('i').removeClass().addClass('fas fa-sort');


        column.find('i').removeClass().addClass(order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down');

        column.data('order', order === 'asc' ? 'desc' : 'asc');

    });

    const inputFile = document.getElementById('enviarFile');
    const viewFiles = document.getElementById('viewFiles');
    const labelTitle = document.getElementById('labelTitle');
    const maxFile = 5
    const maxFileNameLength = 18;
    const selectedFile = [];
    if (inputFile) {
        inputFile.addEventListener('change', function () {
            const fileTrue = viewFiles.querySelectorAll('.Content')
            var files = this.files;
            if (files.length > 5) {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Maximo 5 archivos",
                    text: "Por favor, selecciona un máximo de 5 archivos.",
                    showConfirmButton: true,
                });
                this.value = '';
                viewFiles.innerHTML = '';
                labelTitle.innerHTML = ''
            } else if (fileTrue.length >= maxFile) {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "Límite alcanzado",
                    text: "Ya has alcanzado el límite de archivos.",
                    showConfirmButton: true,
                });
                this.value = ''
                document.getElementById("adjunt").style.display = "none";
                document.getElementById("fileid").style.display = "none";

                return this;

            } else {
                for (let i = 0; i < files.length; i++) {
                    if (files[i].type.startsWith('image/') || files[i].type === 'application/pdf') {
                        const archivo = files[i];
                        selectedFile.push(archivo)
                        const divPreview = document.createElement('div');
                        const divPreviewLabel = document.createElement('label');
                        divPreview.classList.add('Content', 'col-md-2', 'position-relative', 'espaciado');
                        divPreview.style.textAlign = 'center';
                        divPreviewLabel.classList.add('espaciado');

                        const small = document.createElement('small');
                        small.classList.add('eliminar-archivo');
                        small.innerHTML = '<i class="bi bi-x-circle"></i>';
                        small.addEventListener('click', function () {
                            const index = selectedFile.indexOf(archivo)
                            if (index !== -1) {
                                selectedFile.splice(index, 1);
                            }

                            divPreview.remove();
                            divPreviewLabel.remove();

                        });

                        if (archivo.type.startsWith('image/')) {
                            const img = document.createElement('img');
                            img.src = URL.createObjectURL(archivo);
                            img.classList.add('previewImg');
                            divPreview.appendChild(img);
                            const labelName = document.createElement('p');
                            labelName.textContent = archivo.name.length > maxFileNameLength ? archivo.name.substring(0, maxFileNameLength) : '...' + archivo.name;
                            divPreviewLabel.appendChild(labelName);

                        } else if (archivo.type === 'application/pdf') {
                            const imgGenric = document.createElement('img')
                            imgGenric.classList.add('previewImg');
                            imgGenric.src = imgPdfg;
                            divPreview.appendChild(imgGenric);
                            const labelName = document.createElement('p');
                            labelName.textContent = archivo.name.length > maxFileNameLength ? archivo.name.substring(0, maxFileNameLength) + '...' : archivo.name;
                            divPreviewLabel.appendChild(labelName);
                        }
                        divPreview.appendChild(small);
                        labelTitle.appendChild(divPreviewLabel);
                        viewFiles.appendChild(divPreview);
                    } else {
                        Swal.fire({
                            position: "center",
                            icon: "info",
                            title: "Solo imagenes o PDF",
                            text: "Por favor, selecciona una imagen o un pdf.",
                            showConfirmButton: true,
                        });

                    }
                }

            }

        })
    }

    $(document).on('click', '#verArch', function () {
        var idCorreccion = $(this).data('id-correctiva');
        $.ajax({
            url: '/tabla/anexo/' + idCorreccion,
            method: 'GET',
            success: function (data) {
                $('#tableAnexo tbody').empty();
                if (data.anexo.length > 0) {
                    $.each(data.anexo, function (index, anex) {
                        var row = '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + anex.fecha + '</td>' +
                            '<td>' + anex.nombre + '</td>' +
                            '<td>' +
                            '<a type="button" href="' + base + '/' + anex.ruta + '" target="_blank"  ><i class="bi bi-eye Objets"></i></a>' +
                            '</td>' +
                            '<td>' +
                            '<a type="button" class="deleteAnexo" data-anexo-id="' + anex.idanexo + '"><i class="bi bi-trash3-fill Objets"></i></a>' +
                            '</td>' +
                            '</tr>';
                        $('#tableAnexo tbody').append(row)
                    });
                    $('#tableAnexo').show();
                } else {
                    $('#tableAnexo tbody').html('<tr><td colspan="5" class="text-center">No hay archivos disponibles</td></tr>');
                    $('#tableAnexo').show();
                }
            },
            error: function (error) {
                console.error('Error al cargar las revisiones correctivas:', error);
            }
        });


    });
    $('#formAnexo').submit(function (event) {
        event.preventDefault();

        // Verificar si se han seleccionado archivos
        if (selectedFile.length === 0) {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "No hay Archivo",
                text: "Por favor, adjunte un archivo",
                showConfirmButton: true,
            });
            return false;
        } else {
            // Realizar la solicitud AJAX para enviar los archivos
            $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr('action'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (data) {
                    if (data.success) {

                        // Mostrar mensaje de éxito
                        Swal.fire({
                            title: 'Cargando...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 3500
                        }).then(function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Correcto',
                                text: 'Anexo registrado correctamente',
                            }).then(function () {

                                // Mostrar la tabla de anexos con los datos actualizados
                                $('#tableAnexo tbody').empty();
                                $.each(data.anexo, function (index, anex) {
                                    var row = '<tr>' +
                                        '<td>' + (index + 1) + '</td>' +
                                        '<td>' + anex.fecha + '</td>' +
                                        '<td>' + anex.nombre + '</td>' +
                                        '<td>' +
                                        '<a type="button" href="' + base + '/' + anex.ruta + '" target="_blank"><i class="bi bi-eye Objets"></i></a>' +
                                        '</td>' +
                                        '<td>' +
                                        '<a type="button" class="deleteAnexo" data-anexo-id="' + anex.idanexo + '"><i class="bi bi-trash3-fill Objets"></i></a>' +
                                        '</td>' +
                                        '</tr>';

                                    $('#tableAnexo  tbody').append(row)

                                });
                                $('#tableAnexo').show();
                                // Limpiar modal y mostrar la tabla de anexos
                                const modal4 = document.querySelector('.modalDad4');
                                modal4.style.animationDuration = '1s';
                                modal4.classList.remove('modal--show4');
                                viewFiles.innerHTML = '';
                                labelTitle.innerHTML = '';
                                const modal3 = document.querySelector('.modalDad3');
                                modal3.style.animationName = 'modalDad3';
                                modal3.style.animationDuration = '1s';
                                modal3.classList.add('modal--show3');
                                var close = document.querySelector('.close')
                                if (close) {
                                    close.addEventListener('click', () => {
                                        location.reload();
                                    })
                                    document.querySelector('.close3').addEventListener('click', () => {
                                        modal2.style.animationName = 'modalClose2';
                                        modal2.style.animationDuration = '1s'
                                        modal2.classList.remove('modal--show2');
                                        modal.style.animationName = 'modalDad';
                                        modal.style.animationDuration = '1s';
                                        modal.classList.add('modal--show');

                                    })
                                }
                            });
                        });
                    } else {
                        // Mostrar mensaje de error si la carga falló
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error al subir el archivo',
                        });
                    }
                },
                error: function (error) {
                    // Mostrar mensaje de error si la solicitud AJAX falla
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al subir el archivo',
                    });
                }
            });
        }
    });

    $(document).on('click', '#verArch', function () {
        var idCorreccion = $(this).data('id-correctiva');
        // Almacena idCorreccion en un campo de entrada oculto en el formulario
        $('#idCorreccionInput').val(idCorreccion);
        // Abre el modal
        // Tu código AJAX existente
    });
    $(document).on('click', '.deleteAnexo', function () {
        var idAnexo = $(this).data('anexo-id');
        var currentRow = $(this).closest('tr');
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminarlo!'
        }).then(function () {
            $.ajax({
                method: "DELETE",
                url: "/delete/anexo/" + idAnexo,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            text: 'Anexo eliminado correctamente',
                        }).then(function () {
                            Swal.fire({
                                title: 'Cargando...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                timer: 2000

                            }).then(function () {
                                currentRow.remove();
                                $('#tableAnexo tbody tr:visible').each(function (index, element) {
                                    $(element).find('td:first').text(index + 1);
                                });

                                var visibleRows = $('#tableAnexo tbody tr:visible').length;
                                if (visibleRows === 0) {
                                    $('#tableAnexo tbody').html('<tr><td colspan="5" class="text-center">No hay archivos disponibles</td></tr>');
                                }
                            })

                        })
                    } else {
                        Swal.fire('Error', 'Hubo un error al eliminar el anexo', 'error');

                    }

                }, error: function(error) {
                    // Mostrar mensaje de error si la solicitud AJAX falla
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al subir el archivo',
                    });
                }
            });
        })

    })
    $(document).on('click', '#deleteTrash', function (e) {
        var interno = $(this).data('nro-interno')
        var id = $(this).data('revision-id')
        var placa = $(this).data('placa')
        Swal.fire({
            title: '¿Estás seguro?',
            html: 'Se eliminará mantenimiento del Interno  <strong>' + interno + '</strong>, Esta acción no se puede revertir',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminarlo',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "/transultana/delete/mant/" + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Correcto',
                                text: 'Mantenimiento Eliminado correctamente',
                                showConfirmButton: false,
                                timer: 1500,
                            }).then(function () {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', 'Hubo un error al eliminar el mantenimiento', 'error');
                        }
                    }
                });
            }
        })

    })
})
//
