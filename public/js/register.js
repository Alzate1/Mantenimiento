document.addEventListener('DOMContentLoaded', () => {

    const btnCancel = document.querySelectorAll('.btnCancel');
    btnCancel.forEach(cancelar => {
        cancelar.addEventListener('click', () => {
            window.location.href = user;
        })

    });
    const crearFirma = $('#crearFirma')
    const clickFirma =$('#clickFirma')
    var AdjuntarFirma =$('#AdjuntarFirma')
    var createFirma =$('#createFirma')

    crearFirma.on('click', () => {
        AdjuntarFirma.css('display', 'none')
        clickFirma.css('display', 'none')
        createFirma.css('display', 'block')
        crearFirma.css('display', 'none')
    })
    clickFirma.on('click', () => {
        AdjuntarFirma.css('display', 'none')
        clickFirma.css('display', 'none')
        AdjuntarFirma.css('display', 'block')
        crearFirma.css('display', 'none')
    })

    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas); //canvas

    $('#borrar').on('click', function () {
        signaturePad.clear();
    });
    let imagenEnviada = false;
    $('#conf').on('click', function () {
        const tuFv = document.getElementById('tuF');
        const conf = document.getElementById('conf');
        const imagenesFirmadasContainer = document.getElementById('imagenesFirmadasContainer');
        const formFirma = document.getElementById('formFirma');
        const containerImg = document.getElementById('containerImg');
        if (signaturePad.isEmpty()) {
            Swal.fire({
                title: "La firma está vacía",
                icon: "info",
                text: "Por favor, firma antes de enviar",
            })
        } else {
            Swal.fire({
                title: "¿seguro que quieres guardar esta firma?",
                icon: "info",
                text: "Este cambio será irreversible",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, guardar",
                cancelButtonText: "Cancelar"
            }).then(result => {
                if (result.isConfirmed) {
                    var dataURL = signaturePad.toDataURL();
                    const image = document.createElement('img');
                    image.src = dataURL;
                    $('#insertfirma').val(image.src);
                    image.height = canvas.height;
                    image.width = canvas.width;
                    image.style.display = 'block';

                    imagenesFirmadasContainer.appendChild(image);
                    imagenEnviada = true;
                    signaturePad.clear();
                    formFirma.style.display = 'none';
                    containerImg.style.display = 'block'
                    tuFv.style.display = 'block'
                    document.getElementById('imagenesFirmadasContainer').style.display = 'block'

                } else {
                    signaturePad.clear();
                }

            })
        }
    });
    const inputFile = document.getElementById('enviarFile');
    const viewFiles = document.getElementById('viewFiles');
    const labelTitle = document.getElementById('labelTitle');
    const maxFile = 5
    const maxFileNameLength = 18;
    const selectedFile = [];
    let firmaAd = false
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
                    if (files[i].type.startsWith('image/')) {
                        const archivo = files[i];
                        selectedFile.push(archivo)
                        const divPreview = document.createElement('div');
                        const divPreviewLabel = document.createElement('label');
                        divPreview.classList.add('Content', 'col-md-2', 'position-relative', 'espaciado');
                        divPreview.style.textAlign = 'center';
                        divPreviewLabel.classList.add('espaciado');

                         const small = document.createElement('small');
                         small.classList.add('eliminar-archivo');
                         small.innerHTML= '<i class="bi bi-x-circle"></i>';
                         small.addEventListener('click', function() {
                             const index = selectedFile.indexOf(archivo)
                             if (index !== -1) {
                                selectedFile.splice(index,1);
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

                        }
                        divPreview.appendChild(small);
                        labelTitle.appendChild(divPreviewLabel);
                        viewFiles.appendChild(divPreview);
                        firmaAd = true
                    } else {
                        Swal.fire({
                            position: "center",
                            icon: "info",
                            title: "Solo imagenes 'jpg', 'png' y 'jpeg'",
                            text: "Por favor, selecciona una imagen.",
                            showConfirmButton: true,
                        });

                    }
                }

            }

        })
    }

    $('#formRegistrar').submit(function (event) {
        event.preventDefault();
        if (!imagenEnviada && !firmaAd) {
            Swal.fire('Firma requerida', 'Por favor,anexa la firma o crea una antes de enviar', 'info');
            return false;
        }
        $.ajax({
            method: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (data) {
                Swal.fire({
                    title: 'Cargando...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 2500
                }).then(function(){
                    if(data.userError) {
                        // Error: Mostrar SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Usuario existente.',
                            text: 'Por favor, elija otro usuario. ',
                        });
                    } else if(data.emailError){
                        Swal.fire({
                            icon: 'error',
                            title: 'Correo Electronico existente.',
                            text: 'Este correo electrónico ya está registrado. ',
                        });
                    }
                    else if(data.documentError){
                        Swal.fire({
                            icon: 'error',
                            title: 'Documento existente.',
                            text: 'Este Documento ya está registrado. ',
                        });
                    }
                    else if (data.exitoso) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Correcto',
                                text: 'Usuario registrado correctamente',
                            }).then(function () {
                                window.location.href = user;
                                // Espera 2 segundos (ajusta según sea necesario)
                            });
                    }
                })

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

})
