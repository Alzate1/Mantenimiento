<head>
    <link rel="shortcut icon" href="{{ asset('img/SM.png') }}">
    @section('title', 'Vehículo')
    <link rel="stylesheet" href="{{ asset('css/createMante.css') }}">

</head>
@extends('layauts.header')
@section('contenido')
    <section>

        <div class="container card">
            <form id="formRegistrar">

                <div class="Content col-12">
                    <details open>
                        <summary> <small class="defecto">Datos del Vehículo </small>
                            <i class="bi bi-caret-down-fill iconoBajar "></i><i class="bi bi-caret-up-fill iconoSubir "></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px;margin-right: 2px">
                                <div class="col-md-4 mb-2">
                                    <label  class="form-label">Documento de
                                        Propietario*</label>
                                        <input type="text" class="form-control" name="documento_propietario" id="documento_propietario" placeholder="Ingrese Documento de identidad">
                                </div>
                                <div class="col-md-4   mb-2">
                                    <label  class="form-label">Clase Vehículo</label>
                                    <select class="form-select" id="id_grupo" name="id_grupo">
                                            <option  value="" selected disabled>Seleccione la clase</option>
                                        @foreach ($grupos as $grupo)
                                            <option value="{{ $grupo->id_grupo }}">{{ $grupo->desc_grupo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mt-4 mb-2">
                                    <div class="mt-2">
                                        <div class="mb-2"></div>
                                        <button type="button" id="abrir" name="crearGrupo" class="btnSave">
                                            Crear Clase
                                        </button>
                                    </div>
                                </div>
                                <div class="modalDad  mt-4">

                                    <div class="modal_content">
                                        <div class="modal-header" id="mHeader">
                                            <h2>Creación de Clase</h2>
                                            <span class="close"><i class="bi bi-x-circle"></i></span>
                                        </div>
                                        <div>
                                            <div class="modalDad-body Content">
                                                <div class="row">
                                                    <div class="col-md-12 mb-2">
                                                        <label for="desc_grupo" class="form-label">Nombre de la clase</label>
                                                        <input type="text" class="form-control form-control-lg" id="desc_grupo" name="desc_grupo" placeholder="Ingrese el clase">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 modal_footer">
                                            <div class="mb-2 mt-2">
                                                <button type="button" id="saveGroup" class="btnUpdate">Crear</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <label  class="form-label">Número Interno*</label>
                                    <input type="text" class="form-control" id="nro_interno"
                                        placeholder="Ingrese Numero de Interno" name="nro_interno" >
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label  class="form-label" >Placa*</label>
                                    <input type="text" class="form-control" id="placa"
                                        placeholder="Ingrese la placa del vehiculo" name="placa">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label  class="form-label">Documento Conductor</label>
                                    <input type="text" class="form-control" id="documento" name="documento"
                                        placeholder="Ingrese  Documento del Conductor  ">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label  class="form-label">Nombre del Conductor</label>
                                    <input type="text" class="form-control" id="dato" name="nombre" readonly
                                    style="background-color: #eceeef;">
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label  class="form-label">Ruta*</label>
                                    <select class="form-select" id="id_ruta" name="id_ruta" >
                                        <option  value="" selected disabled>Seleccione la Ruta</option>
                                    @foreach ($ruta as $rutas)
                                        <option value="{{ $rutas->id_ruta }}">{{ $rutas->descripcion }}</option>
                                    @endforeach
                                </select>
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
                                    <label  class="form-label">Vencimiento SOAT*</label>
                                    <input type="date" class="form-control" id="soat" name="soat" required >
                                </div>
                                <div class="col-md-4   mb-2">
                                    <label  class="form-label">Vencimiento Revisión
                                        TMC*</label>
                                    <input type="date" class="form-control" id="revision_tmc" name="revision_tmc" required >
                                </div>
                                <div class="col-md-4">
                                    <label  class="form-label">vencimiento EXTRA/CONTRA*
                                    </label>
                                    <input type="date" class="form-control" id="extra_contra" name="extra_contra" required >
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label  class="form-label">Vencimiento TARJETA
                                        OPERACIÓN*</label>
                                    <input type="date" class="form-control" id="tarjeta_operacion" required name="tarjeta_operacion" >
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label  class="form-label">Kilometraje Actual*</label>
                                    <input type="text" class="form-control" id="km_actual"
                                        placeholder="Ingrese el kilometraje del Vehiculo" name="km_actual" required >
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label  class="form-label">Tarjeta de Propiedad*</label>
                                    <select class="form-select form-select" aria-label="Small select example" id="tarjeta_propiedad" name="tarjeta_propiedad" required >
                                        <option selected disabled>Seleccione </option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>
                <div>

                    <div class="col-md-12">
                        <div class="button-form">
                            <button type="button" id="volverVehi" class="btnCancel">Cancelar</button>

                            <button type="button" id="Registrar" class="btnSave">
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
<script src="{{asset('js/vehiculo.js')}}"></script>
        <script>
            var vehiculo = "{{ route('vehiculos') }}"
           var createVehiculo="{{ route('createVehiculo') }}"
        </script>

    </section>
@endsection
