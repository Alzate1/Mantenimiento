<head>
    @section('title', 'Informe')
    <link rel="stylesheet" href="{{ asset('css/createMante.css') }}">
</head>
@extends('layauts.header')

@section('contenido')
<section>
    <style>
        .form-check {
            display: flex;
            flex-wrap: wrap;
        }
        .form-check > div {
            margin-right: 10px;
            margin-bottom: 10px;
            flex: 1 1 auto; /* Esto permitirá que los elementos se ajusten y envuelvan según sea necesario */
        }
    </style>
    <div class="container card">
        <div class="Content col-12">
            <form action="{{ route('createReport') }}" method="post" id="formCreate">
                <details open>
                    <summary>
                        <small>
                            Informe Analistas
                        </small>
                        <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                    </summary>
                    <div class="card mb-2">
                        <div class="row mb-2 mt-2" style="margin-left: 2px;margin-right: 2px">
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Número Interno*</label>
                                <input type="text" class="form-control" id="nro_interno" name="nro_interno"
                                    placeholder="ingrese numero de interno">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">placa*</label>
                                <input type="text" class="form-control" id="placa" readonly
                                    style="background-color: #eceeef;">
                            </div>
                            {{-- INPUT DEL ID VEHICULO --}}
                            <input type="hidden" id="idVehi">
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Documento Conductor</label>
                                <input type="text" class="form-control" id="documento" name="documento"
                                    placeholder="Ingrese  Documento del Conductor  ">
                            </div>
                            <div class="col-md-2 mt-4 mb-2">
                                <div class="mt-2">
                                    <div class="mb-2"></div>

                                    <button type="button" class="btnsearch" id="search">
                                        Buscar <img src="{{ asset('img/icons/buscar.png') }}" style="width: 20px">
                                    </button>
                                </div>

                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Nombre del Conductor</label>
                                <input type="text" class="form-control" id="dato" name="nombre" readonly
                                    style="background-color: #eceeef;">

                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Fecha Informe*</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ auth()->check() && auth()->user()->idtipo_usuario == 1 ? old('fecha_chequeo') : date('Y-m-d') }}"
                                    {{ auth()->check() && auth()->user()->idtipo_usuario != 1 ? 'readonly' : '' }}>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Seleccionar Tipo de Informe</label>
                                <select class="form-select" aria-label="Default select example" name="idTipoInfo"
                                    id="idTipoInfo">
                                    <option selected>Tipo de Informe</option>
                                    @forelse ($tipoInforme as $tipo)
                                    <option value="{{ $tipo->id }}" data-id="{{ $tipo->id }}">{{ $tipo->nombre }}
                                    </option>
                                    @empty
                                    <option selected>Tipo de Informe</option>
                                    @endforelse

                                </select>
                            </div>
                            <div class="col-md-4">

                                <div id="div-check" style="display: none">
                                    <div class="form-check">
                                        @forelse ($items as $item)
                                        <div>
                                            <input class="form-check-input" type="checkbox" name="itemselect[]" value="{{ $item->id }}" id="item">
                                            <label class="form-check-label">
                                                {{ $item->nombre }}
                                            </label>
                                        </div>
                                        @empty
                                        <div>
                                            <label class="form-check-label">
                                                No hay Items
                                            </label>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>

                            </div>


                            <div class="col-md-4 form-floating mt-2 ">
                                <div>
                                    <label>Descripción</label>
                                    <textarea class="form-control" placeholder="Digite la Descripcíon"
                                        style="height: 100px" name="descripcion" id="desc">
                                    </textarea>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="button-form">
                                <button type="button" id="abrir" name="crearItem" class="btnSave">
                                    Crear Item
                                </button>
                            </div>
                            <div class="modalDad  mt-4">

                                <div class="modal_content">
                                    <div class="modal-header" id="mHeader">
                                        <h2>Creación de Item</h2>
                                        <span class="close"><i class="bi bi-x-circle"></i></span>
                                    </div>
                                    <div>
                                        <div class="modalDad-body Content">
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label for="item" class="form-label">Nombre del Item</label>
                                                    <input type="text" class="form-control form-control-lg" id="newitem"
                                                        name="newitem" placeholder="Ingrese el Item">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label for="item" class="form-label">Descripción del
                                                        item</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                        id="desc_item" name="desc_item"
                                                        placeholder="Ingrese la descripción ">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 modal_footer">
                                        <div class="mb-2 mt-2">
                                            <button type="button" id="saveItem" class="btnUpdate">Crear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="button-form">
                            <button type="button" id="volverAnalist" class="btnCancel">Cancelar</button>

                            <button type="submit" id="registrar" class="btnSave">
                                Guardar Informe
                            </button>
                        </div>
                    </div>
                </details>

            </form>
        </div>

    </div>
    <script src="{{ asset('js/createInfo.js') }}"> </script>
    <script>
         document.addEventListener('DOMContentLoaded', function(){
            document.getElementById('volverAnalist').addEventListener('click',(e)=>{
                window.location.href ="{{ route('analistas') }}"
            })
        })

    </script>

</section>
@endsection

{{-- <div class="Content col-12">
        <details id="informeMensual"> //Este debe de terner el id del informe mensual
            <summary>
                <small>
                    Informe Mensual
                </small>
                <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

            </summary>
            <div class="card mb-2">
                <div class="row mb-2 mt-2" style="margin-left: 2px;margin-right: 2px">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Número Interno*</label>
                        <input type="text" class="form-control" id="nro_interno" name="nro_interno"
                            placeholder="ingrese numero de interno">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">placa*</label>
                        <input type="text" class="form-control" id="placa" readonly
                            style="background-color: #eceeef;">
                    </div>
                    {{-- INPUT DEL ID VEHICULO --}}
{{-- <input type="hidden" id="idVehi">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Documento Conductor</label>
                        <input type="text" class="form-control" id="documento" name="documento"
                            placeholder="Ingrese  Documento del Conductor  ">
                    </div>
                    <div class="col-md-2 mt-4 mb-2">
                        <div class="mt-2">
                            <div class="mb-2"></div>

                            <button type="button" class="btnsearch" id="search">
                                Buscar <img src="{{ asset('img/icons/buscar.png') }}" style="width: 20px">
</button>
</div>

</div>

<div class="col-md-4">
    <label class="form-label">Nombre del Conductor</label>
    <input type="text" class="form-control" id="dato" name="nombre" readonly style="background-color: #eceeef;">
    <div class="form-check form-check-inline mt-1" style="display: block" id="blockItemsView">
        <input class="form-check-input" type="radio" id="blockItems" value="option1">
        <label class="form-check-label" for="inlineRadio1">mostrar Items</label>
    </div>
    <div class="form-check form-check-inline mt-1" style="display: none" id="noneItemsView">
        <input class="form-check-input" type="radio" id="noneItems" value="option2">
        <label class="form-check-label" for="inlineRadio2">Ocultar Items</label>
    </div>
</div>
<div class="col-md-4 mb-2">
    <label class="form-label">Fecha Informe*</label>
    <input type="date" class="form-control" id="date" name="date"
        value="{{ auth()->check() && auth()->user()->idtipo_usuario == 1 ? old('fecha_chequeo') : date('Y-m-d') }}"
        {{ auth()->check() && auth()->user()->idtipo_usuario != 1 ? 'readonly' : '' }}>
</div>
<div class="col-md-4 form-floating mt-2 ">
    <div>
        <label>Descripción</label>
        <textarea class="form-control" placeholder="Digite la Descripcíon" style="height: 100px" name="descripcion"
            id="desc">
                        </textarea>
    </div>

</div>

</div>
<div class="col-md-12">
    <div class="button-form">
        <button type="button" id="abrir" name="crearItem" class="btnSave">
            Crear Item
        </button>
    </div>
    <div class="modalDad  mt-4">

        <div class="modal_content">
            <div class="modal-header" id="mHeader">
                <h2>Creación de Item</h2>
                <span class="close"><i class="bi bi-x-circle"></i></span>
            </div>
            <div>
                <div class="modalDad-body Content">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="item" class="form-label">Nombre del Item</label>
                            <input type="text" class="form-control form-control-lg" id="item" name="item"
                                placeholder="Ingrese el Item">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="item" class="form-label">Descripción del
                                item</label>
                            <input type="text" class="form-control form-control-lg" id="desc_item" name="desc_item"
                                placeholder="Ingrese la descripción ">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 modal_footer">
                <div class="mb-2 mt-2">
                    <button type="button" id="saveItem" class="btnUpdate">Crear</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="col-md-12">
    <div class="button-form">
        <button type="button" id="volverAlist" class="btnCancel">Cancelar</button>

        <button type="submit" id="registrar" class="btnSave">
            Guardar Informe
        </button>
    </div>
</div>
</details>

</div>
<div class="Content col-12">
    <details>
        <summary>
            <small>
                Informe Trimestral
            </small>
            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

        </summary>
        <div class="card mb-2">
            <div class="row mb-2 mt-2" style="margin-left: 2px;margin-right: 2px">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Número Interno*</label>
                    <input type="text" class="form-control" id="nro_interno" name="nro_interno"
                        placeholder="ingrese numero de interno">
                </div>
                <div class="col-md-2">
                    <label class="form-label">placa*</label>
                    <input type="text" class="form-control" id="placa" readonly style="background-color: #eceeef;">
                </div>
                {{-- INPUT DEL ID VEHICULO --}}
                {{-- <input type="hidden" id="idVehi">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Documento Conductor</label>
                        <input type="text" class="form-control" id="documento" name="documento"
                            placeholder="Ingrese  Documento del Conductor  ">
                    </div>
                    <div class="col-md-2 mt-4 mb-2">
                        <div class="mt-2">
                            <div class="mb-2"></div>

                            <button type="button" class="btnsearch" id="search">
                                Buscar <img src="{{ asset('img/icons/buscar.png') }}" style="width: 20px">
                </button>
            </div>

        </div>

        <div class="col-md-4">
            <label class="form-label">Nombre del Conductor</label>
            <input type="text" class="form-control" id="dato" name="nombre" readonly style="background-color: #eceeef;">
            <div class="form-check form-check-inline mt-1" style="display: block" id="blockItemsView">
                <input class="form-check-input" type="radio" id="blockItems" value="option1">
                <label class="form-check-label" for="inlineRadio1">mostrar Items</label>
            </div>
            <div class="form-check form-check-inline mt-1" style="display: none" id="noneItemsView">
                <input class="form-check-input" type="radio" id="noneItems" value="option2">
                <label class="form-check-label" for="inlineRadio2">Ocultar Items</label>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <label class="form-label">Fecha Informe*</label>
            <input type="date" class="form-control" id="date" name="date"
                value="{{ auth()->check() && auth()->user()->idtipo_usuario == 1 ? old('fecha_chequeo') : date('Y-m-d') }}"
                {{ auth()->check() && auth()->user()->idtipo_usuario != 1 ? 'readonly' : '' }}>
        </div>
        <div class="col-md-4 form-floating mt-2 ">
            <div>
                <label>Descripción</label>
                <textarea class="form-control" placeholder="Digite la Descripcíon" style="height: 100px"
                    name="descripcion" id="desc">
                        </textarea>
            </div> --}}

            {{-- </div>


                </div>
                <div class="col-md-12">
                    <div class="button-form">
                        <button type="button" id="abrir" name="crearItem" class="btnSave">
                            Crear Item
                        </button>
                    </div>
                    <div class="modalDad  mt-4">

                        <div class="modal_content">
                            <div class="modal-header" id="mHeader">
                                <h2>Creación de Item</h2>
                                <span class="close"><i class="bi bi-x-circle"></i></span>
                            </div>
                            <div>
                                <div class="modalDad-body Content">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label for="item" class="form-label">Nombre del Item</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="item" name="item" placeholder="Ingrese el Item">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="item" class="form-label">Descripción del
                                                item</label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="desc_item" name="desc_item"
                                                placeholder="Ingrese la descripción ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 modal_footer">
                                <div class="mb-2 mt-2">
                                    <button type="button" id="saveItem" class="btnUpdate">Crear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="button-form">
                    <button type="button" id="volverAlist" class="btnCancel">Cancelar</button>

                    <button type="submit" id="registrar" class="btnSave">
                        Guardar Informe
                    </button>
                </div>
            </div>
        </details>
</div>
<div class="Content col-12">
        <details>
            <summary>
                <small>
                    Informe Anual
                </small>
                <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

            </summary>
            <div class="card mb-2">
                <div class="row mb-2 mt-2" style="margin-left: 2px;margin-right: 2px">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Número Interno*</label>
                        <input type="text" class="form-control" id="nro_interno" name="nro_interno"
                            placeholder="ingrese numero de interno">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">placa*</label>
                        <input type="text" class="form-control" id="placa" readonly
                            style="background-color: #eceeef;">
                    </div> --}}
            {{-- INPUT DEL ID VEHICULO --}}
            {{-- <input type="hidden" id="idVehi">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Documento Conductor</label>
                        <input type="text" class="form-control" id="documento" name="documento"
                            placeholder="Ingrese  Documento del Conductor  ">
                    </div>
                    <div class="col-md-2 mt-4 mb-2">
                        <div class="mt-2">
                            <div class="mb-2"></div>

                            <button type="button" class="btnsearch" id="search">
                                Buscar <img src="{{ asset('img/icons/buscar.png') }}" style="width: 20px">
            </button>
        </div>

</div>

<div class="col-md-4">
    <label class="form-label">Nombre del Conductor</label>
    <input type="text" class="form-control" id="dato" name="nombre" readonly style="background-color: #eceeef;">
    <div class="form-check form-check-inline mt-1" style="display: block" id="blockItemsView">
        <input class="form-check-input" type="radio" id="blockItems" value="option1">
        <label class="form-check-label" for="inlineRadio1">mostrar Items</label>
    </div>
    <div class="form-check form-check-inline mt-1" style="display: none" id="noneItemsView">
        <input class="form-check-input" type="radio" id="noneItems" value="option2">
        <label class="form-check-label" for="inlineRadio2">Ocultar Items</label>
    </div>
</div>
<div class="col-md-4 mb-2">
    <label class="form-label">Fecha Informe*</label>
    <input type="date" class="form-control" id="date" name="date"
        value="{{ auth()->check() && auth()->user()->idtipo_usuario == 1 ? old('fecha_chequeo') : date('Y-m-d') }}"
        {{ auth()->check() && auth()->user()->idtipo_usuario != 1 ? 'readonly' : '' }}>
</div>
<div class="col-md-4 form-floating mt-2 ">
    <div>
        <label>Descripción</label>
        <textarea class="form-control" placeholder="Digite la Descripcíon" style="height: 100px" name="descripcion"
            id="desc">
                        </textarea>
    </div>

</div>

</div>
<div class="col-md-12">
    <div class="button-form">
        <button type="button" id="abrir" name="crearItem" class="btnSave">
            Crear Item
        </button>
    </div>
    <div class="modalDad  mt-4">

        <div class="modal_content">
            <div class="modal-header" id="mHeader">
                <h2>Creación de Item</h2>
                <span class="close"><i class="bi bi-x-circle"></i></span>
            </div>
            <div>
                <div class="modalDad-body Content">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="item" class="form-label">Nombre del Item</label>
                            <input type="text" class="form-control form-control-lg" id="item" name="item"
                                placeholder="Ingrese el Item">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="item" class="form-label">Descripción del
                                item</label>
                            <input type="text" class="form-control form-control-lg" id="desc_item" name="desc_item"
                                placeholder="Ingrese la descripción ">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 modal_footer">
                <div class="mb-2 mt-2">
                    <button type="button" id="saveItem" class="btnUpdate">Crear</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="col-md-12">
    <div class="button-form">
        <button type="button" id="volverAlist" class="btnCancel">Cancelar</button>

        <button type="submit" id="registrar" class="btnSave">
            Guardar Informe
        </button>
    </div>
</div> --}}
{{-- </details>
</div> --}}
