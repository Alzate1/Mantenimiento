<!doctype html>
<html lang="en">

<head>
 <link rel="shortcut icon" href="{{ asset('img/icon.png') }}">
    <title>Sultana @yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
</head>

<body>
    <header class="contenedor">
        <nav class="banner" id="element">
            <ul class="ul-banner">
                <li class="panel"><a href="{{route('home')}}"class="a"><img src="{{ asset('img/icons/Pcontrol.png') }}" class="iconos"
                            alt=""><span class="nav-item">panel</span></a></li>
                            @if(auth()->check() && auth()->user()->idtipo_usuario==1 ||
                            auth()->check() && auth()->user()->idtipo_usuario==2 ||
                            auth()->check() && auth()->user()->idtipo_usuario==3)
                <li><a href="{{route('tableMante')}}"class="a"><img src="{{ asset('img/icons/mantenimiento.png') }}" class="iconos"
                            alt=""> <span class="nav-item"> mantenimiento</span></a></li>

                @endif
                @if(auth()->check() && auth()->user()->idtipo_usuario==1 ||
                 auth()->check() && auth()->user()->idtipo_usuario==2 ||
                 auth()->check() && auth()->user()->idtipo_usuario==3)
                <li><a href="{{route('alistamiento')}}"class="a"><img src="{{ asset('img/icons/chequeo.png') }}" class="iconos"
                            alt=""> <span class="nav-item"> Alistamiento</span> </a>
                </li>
                @endif
                <li>
                    <a href="{{route('vehiculos')}}"class="a"><img src="{{ asset('img/icons/autobus.png') }}" class="iconos"
                            alt=""><span class="nav-item">vehículos</span></a>
                </li>

                <li>
                    <a href="{{route('motorista')}}"class="a"><img src="{{ asset('img/icons/conductor.png') }}" class="iconos"
                            alt=""><span class="nav-item">Motoristas</span></a>
                </li>
                @if(auth()->check() && auth()->user()->idtipo_usuario==1 || auth()->check() && auth()->user()->idtipo_usuario==4)
                <li>
                    <a href="{{route('analistas')}}"class="a"><img src="{{ asset('img/icons/bd.png') }}" class="iconos"
                            alt=""><span class="nav-item">Analistas</span> </a>
                </li>
                @endif
                @if(auth()->check() && auth()->user()->idtipo_usuario==1)
                <li class="users">
                    <a href="{{route('users')}}"class="a"><img src="{{ asset('img/icons/grupo.png') }}" class="iconos"
                            alt=""><span class="nav-item">Usuarios</span> </a>
                </li>

                @endif
                <li class="salir">
                    <a href="#"class="a exit" ><img src="{{ asset('img/icons/exit.png') }}"
                            class="iconos" alt=""><span class="nav-item"> salir</span></a>

                </li>
            </ul>
        </nav>

        <div class="back"  >
            <div class="menu contenedor2">
                <div>
                   <a href="{{route('home')}}" > <img src="{{ asset('img/img.png') }}" alt=""
                    style="width: 160px; border-radius: 10px" ></a>

                <input type="checkbox"id="Open_menu" style="display: none">
                <label for="Open_menu" ><i class="bi bi-list" id="iconMenu"></i></label>
                <Small >
                    <a href="{{route('profile')}}"  style="color: #fff;" class="dashboard-a" ><img src="{{ asset('img/icons/usuario.png') }}"
                        class="dashboard-icons" alt="" style="margin-right: 4px">PERFIL</a>
                </Small>
            </div>
                <nav class="navbar">
                    <ul class="">
                        <li class="panel"><a href="{{route('home')}}"class="dashboard-a"><img src="{{ asset('img/icons/Pcontrol.png') }}"
                             class="dashboard-icons" alt="" style="margin-right: 4px">panel</a>
                        </li>
                        @if(auth()->check() && auth()->user()->idtipo_usuario==1)
                        <li class="usuarios"><a href="{{route('users')}}"class="dashboard-a"><img src="{{ asset('img/icons/grupo.png') }}"
                            class="dashboard-icons" alt="" style="margin-right: 4px">Usuarios</a>
                       </li>
                        @endif




                        <li class="salir">
                            <a href="#"  class="dashboard-a exit"><img
                                    src="{{ asset('img/icons/exit.png') }}" class="dashboard-icons"
                                    alt="">salir</a>

                            </li>
                </nav>
            </div>

        </div>



<div class="contenido" id="contendio">
    @yield('contenido')

</div>




    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
<script src="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="{{ asset('js/header.js') }}"></script>
    <script src="https://unpkg.com/xlsx@0.16.9/dist/xlsx.full.min.js"></script>
    <script src="https://unpkg.com/file-saverjs@latest/FileSaver.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css">
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        if ('undefined' !== typeof window) {
            window.GlobalWorkerOptions = {
                workerSrc: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/build/pdf.worker.min.js'
            };
        }
    </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        var ruta="{{ route('login') }}";
     </script>
</body>

</html>
