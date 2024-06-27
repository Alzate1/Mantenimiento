<head>
    @section('title', 'Registro de usuario')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
@extends('layauts.header')

@section('contenido')
    <section>
        <div class="container-fluid">

            <div>
                <form method="POST" action="{{ route('user.register') }}" class="w-100"class="form_register"
                    id="formRegistrar" enctype="multipart/form-data">
                    @csrf
                    <div class="container regiterContent ">
                        <div class="col-12">
                            <div class="regiterTitle  text-center">
                                <label><img src="{{ asset('img/icons/grupo.png') }}" class="iconsRg" alt="">Registro
                                    de usuario</label>
                            </div>
                        </div>
                        <div class=" row classRow">
                            <div class="columna col-md-4 mb-2 mt-2">
                                <label>Nombre Completo *</label>
                                <input class="form-control text-center" type="text" placeholder="Ingrese Nombre"
                                    name="nombre_usuario" required>
                            </div>
                            <div class="columna col-md-4 mb-2 mt-2">
                                <label>Apellido Completo</label>
                                <input class="form-control text-center" type="text" placeholder="Apellido"
                                    name="apellido" required>
                            </div>
                            <div class="columna col-md-4 mb-2 mt-2">

                                <label>Número de documento *</label>
                                <input class="form-control text-center" type="text" placeholder="Numero Doc."
                                    name="documento" required>

                            </div>
                            <div class="columna col-md-4 mb-2 mt-2">

                                <label>Dirección</label>
                                <input class="form-control text-center" type="text" placeholder="Dirección"
                                    name="direccion" required>

                            </div>


                            <div class="columna col-md-4 mb-2 mt-2">

                                <label>email</label>
                                <input class="form-control text-center" type="email" placeholder="Correo Electronico"
                                    name="correo" required>

                            </div>
                            <div class="columna col-md-4 mb-2 mt-2">

                                <label>Tipo usuario</label>
                                <select class="form-select" name="tipoUser" id="tipoUser" required>
                                    <option value="" selected disabled>Seleccione el tipo de usuario</option>
                                    @foreach ($tipoUsuario as $tipoUser)
                                        <option value="{{ $tipoUser->idtipousuario }}">{{ $tipoUser->nombre_tipo_usuario }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="columna col-md-4 mb-2 mt-2">

                                <label>Usuario</label>
                                <input class="form-control text-center" type="text" placeholder="Digite el usuario"
                                    required name="usuario">

                            </div>
                            <div class="columna col-md-4 mb-2 mt-2">

                                <label>Clave</label>
                                <input class="form-control text-center" type="password" placeholder="*****************"
                                    required name="pass">

                            </div>
                            <div class="d-flex mt-2">
                                <button style="display: block; margin-right: 3px" id="crearFirma" type="button" class="btn btn-primary">Crear Firma</button>

                                <button style="display: block" type="button" class="btn btn-info mr" id="clickFirma">Adjuntar Firma</button>

                            </div>
                            <div class="row mb-4 ">


                                {{-- EN ESTA PARTE VA UBICADA LA FIRMA --}}

                                <div class="col-md-12 text-center" id="createFirma" style="display: none">
                                    <input type="file" name="firma" id="firma" style="display: none">
                                    <div class="form-group">
                                        <div class="col-12">
                                            <div id="formFirma" style="display: block">
                                                <div class="pad-box">

                                                    <h5>Crear Firma </h5>
                                                    <div class="wraper mb-2">
                                                        <canvas id="signature-pad" class="signature-pad" width="400"
                                                            height="200"
                                                            style="border: 1px solid rgb(204, 204, 204); touch-action: none;"></canvas>
                                                    </div>

                                                    <div>
                                                        <button type="button" id="conf"
                                                            class="btn btn-primary">confirmar</button>
                                                        <button type="button" id="borrar">Borrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 text-center"style="display:flex">
                                                <div class="col-12 text-center" width="400" height="200"
                                                    id="containerImg" style="display: flex">
                                                    <h5 id="tuF" style="display: none">Tu Firma</h5>
                                                    <div id="imagenesFirmadasContainer" class="text-center">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="firmas" id="insertfirma">
                                </div>
                                <div class="mt-2  text-center d-flex justify-content-center align-items-center">
                                    <div id="AdjuntarFirma" class="card col-8" style="display: none">
                                        @csrf
                                        <input type="hidden" name="idrev" id="idrev">
                                        <div class="container mb-4">
                                            <div class="row">
                                                <div class="col-md-12 mt-2 text-center  mb-4">
                                                    <div id="adjunt">
                                                        <label>Adjuntar Firma</label>
                                                    </div>
                                                    <label for="enviarFile" id="fileid" class="fileid">
                                                        <i class="bi bi-hand-index"></i> Click aqui
                                                        <input type="file" id="enviarFile" name="firmaFile"
                                                            style="display: none">
                                                    </label>
                                                    <div class="text-center d-flex justify-content-center align-items-center"
                                                        id="viewFiles">
                                                    </div>
                                                    <div id="labelTitle" class="text-center">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>
                            <div class="col-12 mb-4 mt-4 md-4"
                                style="display: flex; justify-content: end; align-items: end;">
                                <button type="submit" id="Registrar" class="btnRegister">
                                    <span class="Defecto">Registrar usuario</span>
                                    <span class="smallText">Registrar</span>
                                </button>
                                <button type="button"class="btnCancel">Cancelar</button>
                            </div>
                        </div>


                    </div>

                </form>
            </div>
        </div>
        <script src="{{ asset('js/register.js') }}"></script>
        <script>
            const user = "{{ route('users') }}";
            const imgPdfg = "{{ asset('img/icons/pdf.png') }}";
            const imgFile = "{{ asset('img/icons/requisito.png') }}";
        </script>
    </section>

@endsection
