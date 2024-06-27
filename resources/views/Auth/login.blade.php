<!doctype html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="{{asset('img/icon.png')}}">
    <title>Login - Sultana</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="{{asset('css/login.css')}}">
</head>

<body class="">
    <div class="container-fluid w-100">

        <div class="mx-auto formu">

            <div class="container">
                <img src="{{asset('../img/sultana.jpg')}}" alt="" id="imagen" style="width: 55%;margin-left: 30%;">
            </div>
            <div class="mb-2">
                <h1 class="text-center">Inicio de sesión</h1>
            </div>
            <div class="container">
                <div class="row">
                    <form  id="formLogin" action="{{route('user.login')}}" method="POST">
                        @csrf
                        <div class="mb-3  mt-2 md-4">
                            <label class="form-label">usuario</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" id="inputGroup-sizing-lg"><i
                                        class="bi bi-person-fill"></i></span>
                                <input type="text" class="form-control form-control-lg text-center colorText"
                                    placeholder="usuario" name="usuario" id="user">
                            </div>
                        </div>
                        <div class="mb-3  mt-2 md-4">
                            <label class="form-label">Contraseña</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" id="inputGroup-sizing-lg"><i
                                        class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control form-control-lg text-center colorText"
                                    placeholder="**************" name="pass" id="pass">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-50"> Ingresar</button>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        var form = $('#formLogin');

        form.submit(function (event) {
            event.preventDefault();

            var user = $('#user').val();
            var pass = $('#pass').val();

            if (user === '' || pass === '') {
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Usuario y contraseña son requeridos",
                    showConfirmButton: true,
                });
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
                    if (data.exitoso) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            text: 'Inicio de sesión exitoso',
                            showConfirmButton: false,
                            timer: 2500
                        }).then(function () {
                            window.location.href = "{{ route('home') }}";
                        });
                    } else {
                        Swal.fire({
                        icon: 'error',
                        title: 'Usuario o clave incorrectos',
                        text: 'Verifique sus credenciales',
                    }).then(function () {
                        $('#user').prop('value','');
                        $('#pass').prop('value','');
                    });
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al iniciar sesión',
                    });
                }
            });
        });
    });
</script>

    </script>
</body>

</html>
