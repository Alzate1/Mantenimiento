<head>
    @section('title', 'Alistamiento')
    <link rel="stylesheet" href="{{ asset('css/createMante.css') }}">

</head>
@extends('layauts.header')
@section('contenido')
    <section>

        <div class="container card">
            <form action="{{ route('create.alist') }}" method="post" id="formCraete">
                @csrf
                <div class="Content col-12">
                    <details open>
                        <summary>
                            <small>
                                 ALISTAMIENTO
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px;margin-right: 2px">
                                <div class="col-md-4 mb-2">
                                    <label  class="form-label">Número Interno*</label>
                                    <input type="text" class="form-control" id="nro_interno" name="nro_interno"
                                        placeholder="ingrese numero de interno">
                                </div>
                                <div class="col-md-4">
                                    <label  class="form-label">placa*</label>
                                    <input type="text" class="form-control" id="placa" readonly
                                        style="background-color: #eceeef;">
                                </div>
                                {{-- INPUT DEL ID VEHICULO --}}
                                    <input type="hidden"id="idVehi">
                                <div class="col-md-4 mb-2">
                                    <label  class="form-label">Documento Conductor</label>
                                    <input type="text" class="form-control" id="documento" name="documento"
                                        placeholder="Ingrese  Documento del Conductor  ">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label  class="form-label">Nombre del Conductor</label>
                                    <input type="text" class="form-control" id="dato" name="nombre" readonly
                                    style="background-color: #eceeef;">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label  class="form-label">Fecha alistamiento*</label>
                                    <input type="date" class="form-control" id="fecha_chequeo" name="fecha_chequeo"
                                        value="{{ auth()->check() && auth()->user()->idtipo_usuario == 1 ? old('fecha_chequeo') : date('Y-m-d') }}"
                                        {{ auth()->check() && auth()->user()->idtipo_usuario != 1 ? 'readonly' : '' }}>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label  class="form-label">Vencimiento SOAT</label>
                                    <input type="text" class="form-control" id="soat" name="soat" readonly
                                    style="background-color: #eceeef;">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label  class="form-label">Vencimiento Revisión
                                        TMC</label>
                                    <input type="text" class="form-control" id="revision_tmc" name="revision_tmc" readonly
                                    style="background-color: #eceeef;">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label  class="form-label">vencimiento EXTRA/CONTRA</label>
                                    <input type="text" class="form-control" id="extra_contra" name="extra_contra" readonly
                                    style="background-color: #eceeef;">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label  class="form-label">Vencimiento TARJETA
                                        OPERACIÓN</label>
                                    <input type="text" class="form-control" id="tj_operacion" name="tj_operacion2"readonly
                                    style="background-color: #eceeef;">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="button-form">
                                    <button type="button" class="btnsearch" id="search">
                                        Buscar <img src="{{ asset('img/icons/buscar.png') }}" style="width: 20px">
                                    </button>

                                </div>
                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details open>
                        <summary>
                            <small>
                                IDENTIFICACIÓN DE:
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px">
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                            Fugas de Motor
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="fugasmotor" value="1"> BUENO
                                        <input type="radio" name="fugasmotor" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                           Tensión de Correas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="tensioncorrea" value="1"> BUENO
                                        <input type="radio" name="tensioncorrea" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                            Tapas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="tapas" value="1"> BUENO
                                        <input type="radio" name="tapas" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                            Nivel de Aceite de Motor
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="aceitemotor" value="1"> BUENO
                                        <input type="radio" name="aceitemotor" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                           Transmisión - Fugas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="transmision" value="1"> BUENO
                                        <input type="radio" name="transmision" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                          Sistema de Dirección
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="direccion" value="1"> BUENO
                                        <input type="radio" name="direccion" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                            Frenos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="frenos" value="1"> BUENO
                                        <input type="radio" name="frenos" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                            Estado de Limpia Brisas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="limpia_brisas" value="1"> BUENO
                                        <input type="radio" name="limpia_brisas" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                           Adictivos de Radiador
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="aditivo_radiador" value="1"> BUENO
                                        <input type="radio" name="aditivo_radiador" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                           Filtros Húmedos y Secos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="filtros" value="1"> BUENO
                                        <input type="radio" name="filtros" value="2"> MALO

                                    </div>
                                </div>

                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                          Batería: Nivel Eléctrico
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="bateria_electrico" value="1"> BUENO
                                        <input type="radio" name="bateria_electrico" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                            Batería: Ajustes de Bornes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="bateria_bornes" value="1"> BUENO
                                        <input type="radio" name="bateria_bornes" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                            Llantas: Desgaste
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="llantas_desgaste" value="1"> BUENO
                                        <input type="radio" name="llantas_desgaste" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                           Llantas: Presión de Aire
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="llantas_presion" value="1"> BUENO
                                        <input type="radio" name="llantas_presion" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                            Equipo de Carretera
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="kit_carretera" value="1"> BUENO
                                        <input type="radio" name="kit_carretera" value="2"> MALO

                                    </div>
                                </div>




                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                           Botiquín
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                       <input checked type="radio" name="botiquin" value="1"> BUENO
                                        <input type="radio" name="botiquin" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                          Estado de Luces
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="luces" value="1"> BUENO
                                        <input type="radio" name="luces" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                            Documentación
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="documentacion" value="1"> BUENO
                                        <input type="radio" name="documentacion" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                            Cinturón de Seguridad
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="cinturon" value="1"> BUENO
                                        <input type="radio" name="cinturon" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                          Reposa Cabezas
                                        </label>
                                    </div>
                                </div>





                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="reposacabezas" value="1"> BUENO
                                        <input type="radio" name="reposacabezas" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label  class="form-inline">
                                          Pito
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="pito" value="1"> BUENO
                                        <input type="radio" name="pito" value="2"> MALO

                                    </div>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>

                <div class="Content col-12">
                    <details open>
                        <summary> <small>OBSERVACIONES</small>

                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <label  class="form-inline">Observaciones</label>
                                    <textarea class="textarea form-control" name="observacion" id="observacion" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details open>
                        <summary>
                            <small>
                                 APROBACIÓN
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2 d-flex flex-column align-items-center" style="margin-left: 2px;margin-right: 2px">
                                <div class="col-md-6 mb-2 d-flex flex-column align-items-center Content">
                                    <label  class="form-label mt-2">Responsable</label>
                                    @if(auth()->check() && auth()->user()->idtipo_usuario==1)
                                    <select class="form-select form-select-lg mb-3 text-center" aria-label="Large select example" name="id_usuario" id="id_usuario">
                                        <option disabled selected>Seleccione el responsable</option>
                                        @foreach ($user as $users )
                                        <option value="{{ $users->idusuario }}">{{ $users->nombre_usuario }} {{ $users->apellido }}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    <label style="font: bold">
                                        {{ strtoupper(auth()->user()->nombre_usuario.' '.auth()->user()->apellido) }}
                                        <input type="hidden" name="id_usuario" id="id_usuario" value="{{ auth()->user()->idusuario }}" >
                                    </label>
                                    @endif
                                </div>
                                <div class="col-md-4 mt-4 d-flex flex-column align-items-center Content" >
                                    <label  class="form-label">Aprobado</label>
                                    <div class="form-group">
                                        <input checked type="radio" name="aprobado" value="1"> Si
                                        <input type="radio" name="aprobado" value="2"> No
                                    </div>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>

               <div>

                <div class="col-md-12">
                    <div class="button-form">
                        <button type="button" id="volverAlist" class="btnCancel">Cancelar</button>

                        <button type="submit" id="registrar" class="btnSave">
                            Guardar
                        </button>
                    </div>
                </div>
               </div>
            </form>

        </div>

        <script src="{{ asset('js/alist.js') }}">
        </script>
        <script>
        var alistamiento = "{{route('alistamiento')}}";

        function rutaEdit() {
        var idVehiculo = $('#idVehi').val();
        var editVehi = "{{ route('vehiEdit', ['id' => ':id']) }}";
        editVehi = editVehi.replace(':id',idVehiculo)
        window.location.href = editVehi;
        }
        </script>

    </section>
@endsection
