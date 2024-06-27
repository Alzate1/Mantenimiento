<head>
    <link rel="shortcut icon" href="{{ asset('../img/SM.png') }}">
    @section('title','Modificación de Perfil')
    <link rel="stylesheet" href="{{asset('css/register.css')}}">

</head>
@extends('layauts.header')

@section('contenido')
<section>
    <div class="container-fluid">
        <div>
            <form method="POST"  class="w-100" class="form_register" id="formUpdate" action="{{ route('perfil.update') }}">
                @csrf
                @method('PUT')
                <div class="container regiterContent ">
                    <div class="col-12">
                        <div class="regiterTitle  text-center">
                            <label for=""><img src="{{ asset('img/icons/usuario.png') }}" class="iconsRg" alt="">Modificar
                                Perfil</label>
                        </div>
                    </div>
                    <div class=" row classRow">
                        <div class="columna col-md-4 mb-2 mt-2">
                            <label for="">Nombre Completo *</label>
                            <input class="form-control text-center" type="text" placeholder="Ingrese Nombre"
                                name="nombre_usuario" required value="{{ $users->nombre_usuario }}" required>
                        </div>
                        <div class="columna col-md-4 mb-2 mt-2">
                            <label for="">Apellido Completo</label>
                            <input class="form-control text-center" type="text" placeholder="Ingrese Apellido"
                                name="apellido" required value="{{ $users->apellido }}" >
                        </div>
                        <div class="columna col-md-4 mb-2 mt-2">

                            <label for="">Número de documento *</label>
                            <input class="form-control text-center" type="text" placeholder="Numero Doc."
                                name="documento" readonly value="{{ $users->documento }}"  >

                        </div>
                        <div class="columna col-md-4 mb-2 mt-2">

                            <label for="">Dirección</label>
                            <input class="form-control text-center" type="text" placeholder="Dirección" name="direccion"
                                required  value="{{ $users->direccion }}">

                        </div>

                        <div class="columna col-md-4 mb-2 mt-2">

                            <label for="">email</label>
                            <input class="form-control text-center" type="text" placeholder="Correo Electronico"
                                required name="correo" value="{{ $users->correo }}" >

                        </div>
                        <div class="columna col-md-4 mb-2 mt-2">

                            <label for="">Usuario</label>
                            <input class="form-control text-center" type="text" placeholder="Digite el usuario"
                                name="usuario" id="usuario" required value="{{ $users->usuario }}" >

                        </div>
                        <div class="columna col-md-4 mb-2 mt-2">

                            <label for="">Clave</label>
                            <input class="form-control text-center" type="password" placeholder="*****************"
                                name="pass" id="pass">
                        </div>
                        <div class="col-8 text-right mb-4 mt-4 md-4" style="display: flex">
                            <button type="submit" id="Registrar" class="btnRegister">
                                <span class="Defecto">Modificar usuario</span>
                                <span class="smallText">Modificar</span>
                            </button>
                            <a type="button" href="{{ route('home') }}" class="btnCancel" id="cancel">Cancelar</a>
                        </div>

                    </div>

                </div>
            </form>

        </div>
    </div>

    <script>
        const home = "{{route('home')}}";
        $('#formUpdate').submit(function(event) {
            event.preventDefault();
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
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            text: 'Usuario actualizado correctamente',
                        }).then(function() {
                            // Redireccionar después del Sweet Alert
                            window.location.href = home;
                        });
                    }else if (data.emailError) {
                        Swal.fire({
                        icon: 'error',
                        title: 'Correo Electronico existente.',
                        text: 'Este correo electrónico ya está registrado. ',
                        });
                    }else if(data.userError){
                          // Error: Mostrar SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Usuario existente.',
                            text: 'Por favor, elija otro usuario. ',
                        });
                    }
                }
            });
        });
    </script>
</section>

@endsection
