<head>
    @section('title', 'Actualizar Informe')
    <link rel="stylesheet" href="{{ asset('css/createMante.css') }}">
</head>
@extends('layauts.header')
@section('contenido')
<div class="Content col-12">
    <details open >
        <summary>
            <small>
                Actualizar Informe
            </small>

        </summary>
        <div class="card mb-2">
            <div class="row mb-2 mt-2" style="margin-left: 2px;margin-right: 2px">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Número Interno*</label>
                    <input type="text" class="form-control" id="nro_interno" name="nro_interno" value="{{ $vehiculo->nro_interno }}">
                </div>
                {{-- INPUT DEL ID VEHICULO --}}
                <input type="hidden" id="idVehi">
                <div class="col-md-4 mb-4">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" placeholder="Digite la descripción" id="floatingTextarea">
                        {{ $informe->descripcion }}
                    </textarea>

                </div>
                <div class="col-md-4">
                    <label class="form-label">Seleccionar Tipo de Informe</label>
                    <select class="form-select" aria-label="Default select example" name="idTipoInfo"
                        id="idTipoInfo">
                        <option selected>Tipo de Informe</option>
                        @foreach ($allTipoInforme as $tipo)
                        <option value="{{ $tipo->id }}" {{ $tipo->id == $informe->id_tipo_informe ? 'selected' : '' }} >{{ $tipo->nombre }}
                        </option>
                        @endforeach

                    </select>
                </div>
                
                <div class="col-md-4 mb-4">
                    <label class="form-label">Seleccionar Item</label>
                    <div class="form-check">
                        @foreach ($allItems as $items)
                        <div>
                            <input class="form-check-input" type="checkbox" name="itemselect[]" value="{{ $items->id }}" id="item">
                            <label class="form-check-label">
                                {{ $items->nombre }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </details>
</div>
@endsection
