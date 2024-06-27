<head>
    <link rel="shortcut icon" href="{{ asset('img/SM.png') }}">
    @section('title', 'Vehículo')
    <link rel="stylesheet" href="{{ asset('css/createMante.css') }}">

</head>
@extends('layauts.header')
@section('contenido')
    <section>

        <div class="container card">
            <form action="{{ route('vehiUpdate', $vehiculo->idvehiculo) }}" method="POST" id="formUpdate">
                @csrf
                @method('PUT')
                  <input type="hidden" name="id_usuario" id="id_usuario"
                value="{{ auth()->user()->idusuario }}">
                <div class="Content col-12">
                    <details open>
                        <summary> <small class="defecto">Datos del Vehículo </small>
                            <i class="bi bi-caret-down-fill iconoBajar "></i><i class="bi bi-caret-up-fill iconoSubir "></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px;margin-right: 2px">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Documento de
                                        Propietario*</label>
                                    @if (auth()->check() && auth()->user()->idtipo_usuario == 3)
                                        <input type="text" class="form-control" id="documento_propietario"
                                            name="documento_propietario" value="{{ $vehiculo->documento_propietario }}"
                                            placeholder="ingrese Documento de identidad" readonly
                                            style="background-color: #eceeef;">
                                    @else
                                        <input type="text" class="form-control" id="documento_propietario"
                                            name="documento_propietario" value="{{ $vehiculo->documento_propietario }}"
                                            placeholder="ingrese Documento de identidad">
                                    @endif
                                </div>


                                <div class="col-md-4">
                                    <label class="form-label">Número Interno</label>
                                    @if (auth()->check() && auth()->user()->idtipo_usuario == 3)
                                        <input type="text" class="form-control" id="nro_interno" name="nro_interno"
                                            placeholder="Ingrese Numero de Interno" value="{{ $vehiculo->nro_interno }}"
                                            readonly style="background-color: #eceeef;">
                                    @else
                                        <input type="text" class="form-control" id="nro_interno" name="nro_interno"
                                            placeholder="Ingrese Numero de Interno" value="{{ $vehiculo->nro_interno }}">
                                    @endif
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Placa</label>
                                    <input type="text" class="form-control" placeholder="Ingrese la placa del vehiculo"
                                        @readonly(true) style="opacity: 0.8" value="{{ $vehiculo->placa }}">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Documento de Conductor</label>
                                    @if (auth()->check() && auth()->user()->idtipo_usuario == 3)
                                        <input type="text" class="form-control" id="documento" name="numconductor"
                                            placeholder="Ingrese Documento de Indentidad"
                                            value="{{ $motoristas ? $motoristas['documento'] : '' }}" readonly
                                            style="background-color: #eceeef;">
                                    @else
                                        <input type="text" class="form-control" id="documento" name="numconductor"
                                            placeholder="Ingrese Documento de Indentidad"
                                            value="{{ $motoristas ? $motoristas['documento'] : '' }}">
                                    @endif
                                </div>


                                <div class="col-md-6 mb-2">
                                    @php

                                        $apellido = isset($motoristas['apellido']) ? $motoristas['apellido'] : '';
                                        $nombre = isset($motoristas['nombre']) ? $motoristas['nombre'] : '';

                                        $nombreCompleto = $apellido . ' ' . $nombre;
                                    @endphp
                                    <label class="form-label">Nombre del Conductor</label>
                                    <input type="text" class="form-control" id="dato" name="nombre" readonly
                                        style="background-color: #eceeef;" value="{{ $nombreCompleto }}">
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Ruta*</label>
                                    @if (auth()->check() && auth()->user()->idtipo_usuario == 3)
                                        <select class="form-select form-select" aria-label="Small select example"
                                            name="id_ruta" id="id_ruta" disabled style="background-color: #eceeef;">
                                            <option value="">Seleccione la Ruta</option>
                                            @foreach ($ruta as $rutas)
                                                <option value="{{ $rutas->id_ruta }}"
                                                    {{ $rutas->id_ruta == $vehiculo->id_ruta ? 'selected' : '' }}>
                                                    {{ $rutas->descripcion }}
                                                </option>
                                            @endforeach

                                        </select>
                                        <input type="hidden" name="id_ruta" value="{{ $vehiculo->id_ruta }}">
                                    @else
                                        <select class="form-select form-select" aria-label="Small select example"
                                            name="id_ruta" id="id_ruta">
                                            <option value="">Seleccione la Ruta</option>
                                            @foreach ($ruta as $rutas)
                                                <option value="{{ $rutas->id_ruta }}"
                                                    {{ $rutas->id_ruta == $vehiculo->id_ruta ? 'selected' : '' }}>
                                                    {{ $rutas->descripcion }}
                                                </option>
                                            @endforeach

                                        </select>
                                    @endif
                                </div>
                                <div class="col-md-4   mb-2">
                                    <label class="form-label">Clase</label>
                                    @if (auth()->check() && auth()->user()->idtipo_usuario == 3)
                                        <select class="form-select" id="id_grupo" name="id_grupo" disabled
                                            style="background-color: #eceeef;">
                                            <option value="" disabled>Seleccione la Clase</option>
                                            @foreach ($grupos as $grupo)
                                                <option value="{{ $grupo->id_grupo }}"
                                                    {{ $vehiculo->id_grupo == $grupo->id_grupo ? 'selected' : '' }}>
                                                    {{ $grupo->desc_grupo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select class="form-select" id="id_grupo" name="id_grupo">
                                            <option value="" disabled>Seleccione la Clase</option>
                                            @foreach ($grupos as $grupo)
                                                <option value="{{ $grupo->id_grupo }}"
                                                    {{ $vehiculo->id_grupo == $grupo->id_grupo ? 'selected' : '' }}>
                                                    {{ $grupo->desc_grupo }}
                                                </option>
                                            @endforeach
                                        </select>

                                    @endif

                                </div>
                                <div class="col-md-12">
                                    <div class="button-form">
                                        <button type="button" class="btnsearch" id="search">
                                            Buscar <img src="{{ asset('img/icons/buscar.png') }}" style="width: 20px">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details open>
                        <summary>
                            <small>
                                Documentos
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Vencimiento SOAT*</label>
                                    @php
                                        if($documento && $documento->soat){
                                            $fechaVencimiento = Carbon\Carbon::createFromFormat(
                                                'Y-m-d',
                                                $documento->soat,
                                            )->startOfDay();
                                            $diff = now()->startOfDay()->diffInDays($fechaVencimiento, false);
                                            if ($diff <= 0) {
                                                $color = '#FFCDD2'; // Rojo suave
                                                $message = 'Documento vencido';
                                            } elseif ($diff <= 5) {
                                                $color = '#FFF9C4'; // Amarillo suave
                                                $message = "Faltan {$diff} días para el vencimiento";
                                            } else {
                                                $color = 'white';
                                                $message = '';
                                            }
                                        }else {
                                                $color = 'white';
                                                $message = '';
                                            }
                                    @endphp
                                        <input type="date" class="form-control" id="soat" name="soat"
                                            value="{{ $documento->soat ?? '' }}"
                                            style="background-color: {{ $color }};" title="{{ $message }}">
                                </div>
                                <div class="col-md-4   mb-2">
                                    <label class="form-label">Vencimiento Revisión
                                        TMC*</label>
                                    @php
                                        if ($documento && $documento->revision_tmc) {
                                            $fechaVencimiento = Carbon\Carbon::createFromFormat(
                                                'Y-m-d',
                                                $documento->revision_tmc,
                                            )->startOfDay();
                                            $diff = now()->startOfDay()->diffInDays($fechaVencimiento, false);
                                            if ($diff <= 0) {
                                                $color = '#FFCDD2'; // Rojo suave
                                                $message = 'Documento vencido';
                                            } elseif ($diff <= 5) {
                                                $color = '#FFF9C4'; // Amarillo suave
                                                $message = "Faltan {$diff} días para el vencimiento";
                                            } else {
                                                $color = 'white';
                                                $message = '';
                                            }
                                        }else {
                                            $color = 'white';
                                            $message = '';
                                        }

                                    @endphp

                                    <input type="date" class="form-control" id="revision_tmc" name="revision_tmc"
                                        value="{{ $documento->revision_tmc ?? '' }}"
                                        style="background-color: {{ $color }};" title="{{ $message }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">vencimiento EXTRA/CONTRA*</label>
                                    @php
                                        if ($documento && $documento->extra_contra) {
                                            $fechaVencimiento = Carbon\Carbon::createFromFormat(
                                                'Y-m-d',
                                                $documento->extra_contra,
                                            )->startOfDay();
                                            $diff = now()->startOfDay()->diffInDays($fechaVencimiento, false); // Calcula la diferencia de días sin considerar el sentido del tiempo
                                            if ($diff <= 0) {
                                                $color = '#FFCDD2'; // Rojo suave
                                                $message = 'Documento vencido';
                                            } elseif ($diff <= 5) {
                                                $color = '#FFF9C4'; // Amarillo suave
                                                $message = "Faltan {$diff} días para el vencimiento";
                                            } else {
                                                $color = 'white';
                                                $message = '';
                                            }
                                        } else {
                                            $color = 'white';
                                            $message = '';
                                        }
                                    @endphp
                                    <input type="date" class="form-control" id="extra_contra" name="extra_contra"
                                        value="{{ $documento->extra_contra ?? '' }}"
                                        style="background-color: {{ $color }};" title="{{ $message }}">

                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Vencimiento TARJETA OPERACIÓN*</label>
                                    @php
                                        if ($documento && $documento->tarjeta_operacion) {
                                            $fechaVencimiento = Carbon\Carbon::createFromFormat(
                                                'Y-m-d',
                                                $documento->tarjeta_operacion,
                                            )->startOfDay();
                                            $diff = now()->startOfDay()->diffInDays($fechaVencimiento, false); // Calcula la diferencia de días sin considerar el sentido del tiempo
                                            if ($diff <= 0) {
                                                // Si la diferencia es menor o igual a cero, el documento está vencido
                                                $color = '#FFCDD2'; // Rojo suave
                                                $message = 'Documento vencido';
                                            } elseif ($diff <= 5) {
                                                // Si la diferencia es menor o igual a 5, faltan pocos días para el vencimiento
                                                $color = '#FFF9C4'; // Amarillo suave
                                                $message = "Faltan {$diff} días para el vencimiento";
                                            } else {
                                                // De lo contrario, la fecha de vencimiento está en el futuro y el documento está vigente
                                                $color = 'white';
                                                $message = '';
                                            }
                                        } else {
                                            // Si no hay fecha de vencimiento definida, se muestra en blanco
                                            $color = 'white';
                                            $message = '';
                                        }
                                    @endphp
                                    <input type="date" class="form-control" id="tarjeta_operacion"
                                        name="tarjeta_operacion" value="{{ $documento->tarjeta_operacion ?? '' }}"
                                        style="background-color: {{ $color }};" title="{{ $message }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Kilometraje Actual*</label>
                                    @if ($documento)
                                        <input type="text" class="form-control" id="km_actual"
                                            placeholder="Ingrese el kilometraje del Vehiculo" name="km_actual"
                                            value="{{ $documento->km_actual }}">
                                    @else
                                        <input type="text" class="form-control" id="km_actual"
                                            placeholder="Ingrese el kilometraje del Vehiculo" name="km_actual"
                                            value="">
                                    @endif

                                </div>
                                <div class="col-md-4 mb-4">
                                    {{-- <label  class="form-label">Tarjeta de Propiedad*</label>
                                <select class="form-select form-select" aria-label="Small select example" disabled>
                                    <option {{ $documento->tarjeta_propiedad == 1 ? 'selected' : '' }} value="1">
                                        SI</option>
                                    <option {{ $documento->tarjeta_propiedad == 2 ? 'selected' : '' }} value="2">
                                        NO</option>
                                </select> --}}
                                </div>
                            </div>
                        </div>
                    </details>
                </div>
                <div>

                    <div class="col-md-12">
                        <div class="button-form">
                            <button type="button" id="volverVehi" class="btnCancel">Cancelar</button>

                            <button type="submit" id="Registrar" class="btnSave">
                                Actualizar
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>

        <script src="{{ asset('js/updateVehi.js') }}"></script>
        <script>
            var vehiculo = "{{ route('vehiculos') }}"
        </script>
    </section>
@endsection
