<head>
    <link rel="shortcut icon" href="{{ asset('img/SM.png') }}">
    @section('title', 'Mantenimiento')
    <link rel="stylesheet" href="{{ asset('css/createMante.css') }}">

</head>
@extends('layauts.header')
@section('contenido')
    <section>

        <div class="container card">
            <form action="{{ route('create.mant') }}" method="POST" id="formMant">
                @csrf

                <div class="Content col-12">
                    <details open>
                        <summary> <small class="defecto"> MANTENIMIENTO PREVENTIVO / CORRECTIVO</small>
                            <small class="oculto"> MANTENIMIENTO PREVENTIVO</small>
                            <i class="bi bi-caret-down-fill iconoBajar "></i><i class="bi bi-caret-up-fill iconoSubir "></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px;margin-right: 2px">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Número Interno*</label>
                                    <input type="text" class="form-control" id="nro_interno" name="nro_interno"
                                        placeholder="ingrese numero de interno">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">placa*</label>
                                    <input type="text" class="form-control" id="placa" readonly
                                        style="background-color: #eceeef;text-transform: uppercase;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Documento Propietario*</label>
                                    <input type="text" class="form-control" id="documento_propietario" readonly
                                        style="background-color: #eceeef;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Documento Conductor*</label>
                                    <input type="text" class="form-control" id="documento" name="documento" readonly
                                        style="background-color: #eceeef;"
                                        placeholder="ingrese  documento del conductor">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label  class="form-label">Nombre del Conductor</label>
                                    <input type="text" class="form-control" id="dato" name="nombre" readonly
                                    style="background-color: #eceeef;">
                                </div>


                                <div class="col-md-6">
                                    <label class="form-label">Ruta*</label>
                                    <input type="text" class="form-control" id="ruta" readonly
                                        style="background-color: #eceeef; text-transform: uppercase;">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Fecha Mantenimiento*</label>
                                        <input type="date" class="form-control" id="fecha" name="fecha"
                                        value="{{ auth()->check() && auth()->user()->idtipo_usuario == 1 ? old('fecha') : date('Y-m-d') }}"
                                        {{ auth()->check() && auth()->user()->idtipo_usuario != 1 ? 'readonly' : '' }}>
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
                        <summary> <small> DOCUMENTOS </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px;margin-right: 2px">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Vencimiento SOAT*</label>
                                    <input type="text" class="form-control" id="soat" placeholder="yyyy-dd-mm"
                                        readonly style="background-color: #eceeef;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Vencimientp Revisión TMC*
                                    </label>
                                    <input type="text" class="form-control" id="revision_tmc" placeholder="yyyy-dd-mm"
                                        readonly style="background-color: #eceeef;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Vencimiento EXTRA/CONTRA
                                        *</label>
                                    <input type="text" class="form-control" id="extra_contra" placeholder="yyyy-dd-mm"
                                        readonly style="background-color: #eceeef;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Vencimiento TARJETA OPERACION
                                        *</label>
                                    <input type="text" class="form-control" id="tarjeta_operacion"
                                        placeholder="yyyy-dd-mm" readonly style="background-color: #eceeef;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Kilometraje Actual</label>
                                    <input type="text" class="form-control" id="km_actual" placeholder="" readonly
                                        style="background-color: #eceeef;text-transform: uppercase;">
                                </div>
                                {{-- <div class="col-md-4 mb-4">
                                    <label class="form-label">Tarjeta de Propiedad</label>
                                    <input type="text" class="form-control" id="tarjetaPropText" placeholder=""
                                        readonly style="background-color: #eceeef;">
                                </div> --}}
                                {{-- INPUT DEL ID VEHICULO --}}
                                <input type="hidden"id="idVehi">
                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details open>
                        <summary> <small>ESTADO DEL MOTOR </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2 "style="margin-left: 2px">
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Estado Cableado Eléctrico
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="cableado_electrico" value="1"> BUENO
                                        <input type="radio" name="cableado_electrico" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Fugas de Aciete Motor
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio"name="fuga_aceite_motor" value="1"> BUENO
                                        <input type="radio" name="fuga_aceite_motor" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Soporte de Bateria
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="soporte_bateria" value="1"> BUENO
                                        <input type="radio" name="soporte_bateria" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Fugas Refrigerante
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="fuga_refrigerante" value="1"> BUENO
                                        <input type="radio" name="fuga_refrigerante" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Fugas Combusible
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="fuga_combustible" value="1"> BUENO
                                        <input type="radio" name="fuga_combustible" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Estado Bomba Inyección
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <input checked type="radio" name="bomba_inyeccion" value="1"> BUENO
                                        <input type="radio" name="bomba_inyeccion" value="2"> MALO
                                    </div>
                                </div>

                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details>
                        <summary> <small> CAJA DE VELOCIDADES
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px">
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Funcionamiento Embrague
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="funcionamiento_embrague" value="1">
                                        BUENO
                                        <input type="radio" name="funcionamiento_embrague" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Soportes de caja
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="soportes_caja" value="1"> BUENO
                                        <input type="radio" name="soportes_caja" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Fugas de Aceite
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="fugas_aceite" value="1"> BUENO
                                        <input type="radio" name="fugas_aceite" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Juego de mandos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="juego_mandos" value="1"> BUENO
                                        <input type="radio" name="juego_mandos" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Nivel de Aceite
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="nivel_aceite" value="1"> BUENO
                                        <input type="radio" name="nivel_aceite" value="2"> MALO
                                    </div>
                                </div>


                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details>
                        <summary> <small>TRANSMISIÓN</small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px">
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Ajuste
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="ajuste" value="1"> BUENO
                                        <input type="radio" name="ajuste" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Juego Excesivos de Cardan
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="juego_excesivo_cardan" value="1"> BUENO
                                        <input type="radio" name="juego_excesivo_cardan" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Fuga de Aceite
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="fuga_aceite" value="1"> BUENO
                                        <input type="radio" name="fuga_aceite" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Cadena Cardan
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="cadena_cardan" value="1"> BUENO
                                        <input type="radio" name="cadena_cardan" value="2"> MALO

                                    </div>
                                </div>


                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details>
                        <summary>
                            <small>SUSPENSIÓN</small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px">
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Fijación Elementos de la Suspensión
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="fija_elem_suspension" value="1"> BUENO
                                        <input type="radio" name="fija_elem_suspension" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Llantas Labrado
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="llanta_labrado" value="1"> BUENO
                                        <input type="radio" name="llanta_labrado" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Amortiguadores Existencia / Fugas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="amortiguador_exis_fuga" value="1"> BUENO
                                        <input type="radio" name="amortiguador_exis_fuga" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Tijeras
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="tijeras" value="1"> BUENO
                                        <input type="radio" name="tijeras" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Brazo Axial
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <input checked type="radio" name="brazo_axial" value="1"> BUENO
                                        <input type="radio" name="brazo_axial" value="2"> MALO
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Terminales de Dirección
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="terminales_direccion" value="1"> BUENO
                                        <input type="radio" name="terminales_direccion" value="2"> MALO

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Rótulas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <input checked type="radio" name="rotulas" value="1"> BUENO
                                        <input type="radio" name="rotulas" value="2"> MALO
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Ballestas / Resortes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <input checked type="radio" name="ballestas_resortes" value="1"> BUENO
                                        <input type="radio" name="ballestas_resortes" value="2"> MALO
                                    </div>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details>
                        <summary>
                            <small>
                                CARROCERÍA
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px">
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Colores y Avisos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="colores_avisos" value="1"> BUENO
                                        <input type="radio" name="colores_avisos" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Distintivos y Emblemas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="distintivos_emblemas" value="1"> BUENO
                                        <input type="radio" name="distintivos_emblemas" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Placas Laterales y Reflectivos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="placa_lateral_reflectivos" value="1">
                                        BUENO
                                        <input type="radio" name="placa_lateral_reflectivos" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Latoneria y Pintura
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="latoneria_pintura" value="1"> BUENO
                                        <input type="radio" name="latoneria_pintura" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Bomperes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="bomperes" value="1"> BUENO
                                        <input type="radio" name="bomperes" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Pisos y Estribos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <input checked type="radio" name="pisos_estribos" value="1"> BUENO
                                        <input type="radio" name="pisos_estribos" value="2"> MALO
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Mecanismos de Emergencia
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <input checked type="radio" name="mecanismos_emergencia" value="1"> BUENO
                                        <input type="radio" name="mecanismos_emergencia" value="2"> MALO
                                    </div>
                                </div>

                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details>
                        <summary>
                            <small>
                                FRENOS
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px">

                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Fugas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="fugas" value="1"> BUENO
                                        <input type="radio" name="fugas" value="2"> MALO
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Ductos Mangueras de Frenos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="ductos_manguera_frenos" value="1"> BUENO
                                        <input type="radio" name="ductos_manguera_frenos" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Nivel Liquido
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="nivel_liquido" value="1"> BUENO
                                        <input type="radio" name="nivel_liquido" value="2"> MALO

                                    </div>
                                </div>

                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details>
                        <summary>
                            <small>
                                EMISIONES CONTAMINANTES
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px">

                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Estado Sistema de Escape / Fugas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="escape_fugas" value="1"> BUENO
                                        <input type="radio" name="escape_fugas" value="2"> MALO
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Conexión Válvula PCV
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="conexion_valvula" value="1"> BUENO
                                        <input type="radio" name="conexion_valvula" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Emisión Humo Azul o Negro
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="emision_humo" value="1"> BUENO
                                        <input type="radio" name="emision_humo" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Tapas Aceite y Combustible
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="tapa_aceite" value="1"> BUENO
                                        <input type="radio" name="tapa_aceite" value="2"> MALO

                                    </div>
                                </div>

                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details>
                        <summary>
                            <small>
                                LUCES EXTERIORES
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px">

                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Luces Bajas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="luces_bajas" value="1"> BUENO
                                        <input type="radio" name="luces_bajas" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Luces Altas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="luces_altas" value="1"> BUENO
                                        <input type="radio" name="luces_altas" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Cocuyos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="cocuyos" value="1"> BUENO
                                        <input type="radio" name="cocuyos" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Direccionales
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="direccionales" value="1"> BUENO
                                        <input type="radio" name="direccionales" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Luz Freno
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="luz_freno" value="1"> BUENO
                                        <input type="radio" name="luz_freno" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Luz Reversa
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name=" luz_reversa" value="1"> BUENO
                                        <input type="radio" name=" luz_reversa" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Alarma de Reversa
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="alarma_reversa" value="1"> BUENO
                                        <input type="radio" name="alarma_reversa" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Luces de Parqueo
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="luces_parqueo" value="1"> BUENO
                                        <input type="radio" name="luces_parqueo" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
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
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Espejos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="espejos" value="1"> BUENO
                                        <input type="radio" name="espejos" value="2"> MALO

                                    </div>
                                </div>

                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details>
                        <summary>
                            <small>
                                EQUIPO DE CARRETERAS
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px">

                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Extintor Vencimiento
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="extintor" value="1"> BUENO
                                        <input type="radio" name="extintor" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Botiquin
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
                                        <label class="form-inline">
                                            Cruceta
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="cruceta" value="1"> BUENO
                                        <input type="radio" name="cruceta" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Tacos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="tacos" value="1"> BUENO
                                        <input type="radio" name="tacos" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Repuesto
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="repuesto" value="1"> BUENO
                                        <input type="radio" name="repuesto" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Gato
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="gato" value="1"> BUENO
                                        <input type="radio" name="gato" value="2"> MALO

                                    </div>
                                </div>



                            </div>
                        </div>
                    </details>
                </div>
                <div class="Content col-12">
                    <details>
                        <summary>
                            <small>
                                CABINA
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2" style="margin-left: 2px">

                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Tacometro
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="tacometro" value="1"> BUENO
                                        <input type="radio" name="tacometro" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Luces Interiores
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="luces_interiores" value="1"> BUENO
                                        <input type="radio" name="luces_interiores" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Luz de Techo
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="luz_techo" value="1"> BUENO
                                        <input type="radio" name="luz_techo" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Luz de Tablas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="luz_tablas" value="1"> BUENO
                                        <input type="radio" name="luz_tablas" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Anclaje de Sillas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="anclaje_sillas" value="1"> BUENO
                                        <input type="radio" name="anclaje_sillas" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Silletería y Cojinería
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="silleteria_cojineria" value="1"> BUENO
                                        <input type="radio" name="silleteria_cojineria" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Cinturones de Seguridad
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="cinturones_seguridad" value="1"> BUENO
                                        <input type="radio" name="cinturones_seguridad" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Timbre
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="timbre" value="1"> BUENO
                                        <input type="radio" name="timbre" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Estado Pisos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="estado_pisos" value="1"> BUENO
                                        <input type="radio" name="estado_pisos" value="2"> MALO

                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-inline">
                                            Dispositivo de Velocidad
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input checked type="radio" name="dispositivo_velocidad" value="1"> BUENO
                                        <input type="radio" name="dispositivo_velocidad" value="2"> MALO

                                    </div>
                                </div>

                            </div>
                        </div>
                    </details>
                </div>

                <div class="Content col-12 mb-4">
                    <details>
                        <summary> <small>OBSERVACIONES</small>

                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-inline">Observaciones</label>
                                    <textarea class="textarea form-control" name="observacion" id="observacion" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>

                <div class="Content col-12">
                    <details>
                        <summary>
                            <small>
                                APROBACIÓN
                            </small>
                            <i class="bi bi-caret-down-fill iconoBajar"></i><i class="bi bi-caret-up-fill iconoSubir"></i>

                        </summary>
                        <div class="card mb-2">
                            <div class="row mb-2 mt-2 d-flex flex-column align-items-center"
                                style="margin-left: 2px;margin-right: 2px">
                                <div class="col-md-6 mb-2 d-flex flex-column align-items-center Content">
                                    <label class="form-label mt-2">Responsable</label>
                                    @if (auth()->check() && auth()->user()->idtipo_usuario == 1)
                                        <select class="form-select form-select-lg mb-3 text-center"
                                            aria-label="Large select example" name="id_usuario" id="id_usuario">
                                            <option disabled selected>Seleccione el responsable</option>
                                            @foreach ($user as $users)
                                                <option value="{{ $users->idusuario }}">{{ $users->nombre_usuario }}
                                                    {{ $users->apellido }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <label style="font: bold">
                                            {{ strtoupper(auth()->user()->nombre_usuario.' '.auth()->user()->apellido) }}
                                            <input type="hidden" name="id_usuario" id="id_usuario"
                                                value="{{ auth()->user()->idusuario }}">
                                        </label>
                                    @endif
                                </div>
                                {{-- <div class="col-md-4 mt-4 d-flex flex-column align-items-center Content" >
                                    <label  class="form-label">Aprobado</label>
                                    <div class="form-group">
                                        <input checked type="radio" name="aprobado" value="1"> Si
                                        <input type="radio" name="aprobado" value="2"> No
                                    </div>
                                </div> --}}
                            </div>
                        </div>

                    </details>
                </div>

                <div class="col-md-12 mb-4">
                    <div class="button-form">
                        <button type="button" id="volverMant" class="btnCancel">Cancelar</button>

                        <button type="submit" id="Registrar" class="btnSave">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>

        </div>

        <script src="{{ asset('js/mante.js') }}"></script>
        <script>
            var mantenimiento = "{{ route('tableMante') }}"
            function rutaEdit() {
            var idVehiculo = $('#idVehi').val();
            var editVehi = "{{ route('vehiEdit', ['id' => ':id']) }}";
            editVehi = editVehi.replace(':id',idVehiculo)
            window.location.href = editVehi;
            }
        </script>

    </section>
@endsection
