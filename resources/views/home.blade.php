<head>
@section('title','Panel')
<link rel="stylesheet" href="{{asset('css/home.css')}}">


</head>
@extends('layauts.header')

@section('contenido')
<section >
<div class="container">
    <div class="text-center mt-4 mb-4">
<h1>Bienvenido al Panel
<img src="{{asset('img/icons/hola.png')}}" class="logoHome" alt="">
</h1>
    @auth
        <h3>Señor(a) <strong >{{ strtoupper(auth()->user()->nombre_usuario.' '.auth()->user()->apellido ) }}</strong></h3>

    @endauth
    </div>

   <div class="container">
    <div class="classRow">
        @if(auth()->check() && auth()->user()->idtipo_usuario==1 ||
         auth()->check() && auth()->user()->idtipo_usuario==2 ||
         auth()->check() && auth()->user()->idtipo_usuario==3)
        <div class="columna-lg-6 col-md-6 columna-sm-6 columna-xs-6">
            <a href="{{ route('tableMante') }}">
                <div class="box">
                    <h5>Mantenimiento<i class="bi bi-chevron-compact-right"></i></h5>
                    <h4></h4>
                    <div class="containerIcon">
                        <div class="icon">
                            <i class="bi bi-gear-fill">
                            </i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if(auth()->check() && auth()->user()->idtipo_usuario==1 ||
         auth()->check() && auth()->user()->idtipo_usuario==2 ||
         auth()->check() && auth()->user()->idtipo_usuario==3)
        <div class="columna-lg-6 col-md-6 columna-sm-6 columna-xs-6">
            <a href="{{route('alistamiento')}}">

                <div class="box">
                    <h5>Alistamiento<i class="bi bi-chevron-compact-right"></i></h5>
                    <h4 class="alist"></h4>
                    <div class="containerIcon">
                        <div class="icon">
                        <i class="bi bi-wrench-adjustable-circle-fill"></i></i>
                    </div>
                    </div>
                     <!-- .icon -->
                </div> <!-- .box -->
            </a>
        </div>
        @endif
        <div class="columna-lg-6 col-md-6 columna-sm-6 columna-xs-6">
            <a href="{{route('vehiculos')}}">

                <div class="box">
                    <h5>Vehículos<i class="bi bi-chevron-compact-right"></i></h5>
                    <h4 class="line" ></h4>
                    <div class="containerIcon">
                        <div class="icon">
                        <i class="bi bi-bus-front"></i>
                    </div>
                    </div>
                     <!-- .icon -->
                </div> <!-- .box -->
            </a>
        </div>

        @if(auth()->check() && auth()->user()->idtipo_usuario==1)
        <div class="columna-lg-6 col-md-6 columna-sm-6 columna-xs-6">
            <a href="{{route('users')}}">

                <div class="box">
                    <h5>Usuarios<i class="bi bi-chevron-compact-right"></i></h5>
                    <h4 class="line" ></h4>
                    <div class="containerIcon">
                        <div class="icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    </div>
                     <!-- .icon -->
                </div> <!-- .box -->
            </a>
        </div>
        @endif
        <div class="columna-lg-6 col-md-6 columna-sm-6 columna-xs-6">
            <a href="{{route('motorista')}}">

                <div class="box">
                    <h5>Motoristas<i class="bi bi-chevron-compact-right"></i></h5>
                    <h4 class="line" ></h4>
                    <div class="containerIcon">
                        <div class="icon">
                        <i class="bi bi-person-standing"></i>
                    </div>
                    </div>
                     <!-- .icon -->
                </div> <!-- .box -->
            </a>
        </div>
        @if(auth()->check() && auth()->user()->idtipo_usuario==1 || auth()->check() && auth()->user()->idtipo_usuario==4)
        <div class="columna-lg-6 col-md-6 columna-sm-6 columna-xs-6">
            <a href="{{route('analistas')}}">

                <div class="box">
                    <h5>Analistas<i class="bi bi-chevron-compact-right"></i></h5>
                    <h4 class="line" ></h4>
                    <div class="containerIcon">
                        <div class="icon">
                            <i class="bi bi-database-fill-gear"></i>
                    </div>
                    </div>
                     <!-- .icon -->
                </div> <!-- .box -->
            </a>
        </div>
        @endif
    </div>
   </div>

</div>

</section>

@endsection
