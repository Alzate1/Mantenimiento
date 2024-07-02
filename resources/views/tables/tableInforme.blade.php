<head>
    @section('title', 'Alistamiento')
    <link rel="stylesheet" href="{{ asset('css/tManteniento.css') }}">

</head>
@extends('layauts.header')
@section("contenido")
<section>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

        .thC {
            cursor: pointer;
        }

        * {
            font-family: 'Roboto', sans-serif;

        }

        label {
            font-size: large;

        }


        .regiterTitle {
            width: 100%;
            border: 1px solid #3f7ea8;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 10px;
            margin-left: -3px;
            background: #3f7ea8;
            color: #fff;
        }

        .iconsRg {
            width: 30px;
            margin-right: 3px;

        }

        .btnRegister {
            background-color: #3f7ea8;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-right: 3px;
        }

        .btnRegister:hover {
            color: #fff;
            background-color: #549dcf;
            border-color: #6197ef;
        }

        .btnCancel {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btnCancel:hover {
            background-color: #b64b5d;
            color: #fff;
            border-color: #d82334;
        }

        .Defecto {
            display: inline;
        }

        .smallText {
            display: none;
        }

        @media only screen and (max-width:768px) {
            .Defecto {
                display: none;
            }

            .smallText {
                display: inline;
            }

            form {
                width: 100% !important;
            }

            .btnRegister {
                padding: 3% 5%;
                border-radius: 5px;
                width: auto;
                max-width: 100%;
            }

            .btnCancel {
                padding: 3% 5%;
                border-radius: 5px;
                width: auto;
                max-width: 100%;
            }

        }
    </style>
    <div class="container mt-4">
        <div class="card col-12" id="allDate" style="display: block">
            <div class="cards-headers">
                <span>
                    <img src="{{ asset('img/icons/bd.png') }}" class="iconosTable" alt="">
                    Tabla Analista
                </span>
                @if (auth()->check() && (auth()->user()->idtipo_usuario == 1 || auth()->user()->idtipo_usuario == 4))
                {{-- <button type="button" id="verArch" class="btn-Addnew abrir">
                    <span>Agregar Informe</span>
                </button> --}}
                <a href="{{ route('crearInforme') }}" type="button" class="btn-Addnew">
                    <span>Agregar Informe</span>
                </a>
                @endif
            </div>
            <div class="row d-flex col-12">
                <div class="container">
                    <div class="row mt-1">
                        <div class="col-md-6">
                            <form id="pagination_form" action="{{ route('analistas') }}" method="get">
                                <label for="pagination_limit" class="form-inline mb-2">
                                    Mostrar
                                    <select name="per_page" class="custom-select ml-2" id="pagination_limit"
                                        onchange="document.getElementById('pagination_form').submit()">
                                        <option value="25" @if($informe->perPage() == 25) selected @endif>25</option>
                                        <option value="50" @if($informe->perPage() == 50) selected @endif>50</option>
                                        <option value="75" @if($informe->perPage() == 75) selected @endif>75</option>
                                    </select>
                                    registros
                                </label>
                            </form>
                        </div>
                        <div class="col-md-6 text-center">
                            <label class="form-inline" style="float: right;">Buscar:
                                <input type="search" class="custom-select" placeholder="Dato a buscar" id="searchData">
                            </label>
                        </div>
                    </div>

                    <form id="filter_form" action="{{ route('analistas') }}" class="form-inline d-flex" method="get">
                        <input type="hidden" name="per_page" value="{{ $informe->perPage() }}">

                        <div class="col-md-2 text-center">
                            <label class="">Corinto</label>
                            <input type="checkbox" name="corinto" id="checkCorinto"
                                onchange="document.getElementById('filter_form').submit()" @if(request('corinto'))
                                checked @endif>
                        </div>

                        <div class="col-md-2 text-center">
                            <label>Palmira</label>
                            <input type="checkbox" name="palmira" id="checkPalmira"
                                onchange="document.getElementById('filter_form').submit()" @if(request('palmira'))
                                checked @endif>
                        </div>

                        <div class="col-md-2 text-center">
                            <label>Mensual</label>
                            <input type="checkbox" name="mensual" id="checkMensual"
                                onchange="document.getElementById('filter_form').submit()" @if(request('mensual'))
                                checked @endif>
                        </div>

                        <div class="col-md-2 text-center">
                            <label>Trimestral</label>
                            <input type="checkbox" name="trimestral" id="checkTrimestral"
                                onchange="document.getElementById('filter_form').submit()" @if(request('trimestral'))
                                checked @endif>
                        </div>

                        <div class="col-md-1.5 text-center">
                            <label>Anual</label>
                            <input type="checkbox" name="anual" id="checkAnual"
                                onchange="document.getElementById('filter_form').submit()" @if(request('anual')) checked
                                @endif>

                        </div>

                    </form>
                </div>
                <!-- Otros filtros -->

            </div>
            <table class="table table-striped table-hover text-center" id="tableAnalista">
                <thead class="theadMantenmiento">
                    <tr>
                        <th id="pos" class="thC">#</th>
                        <th id="interno" class="thC">Interno</th>
                        <th id="responsable" class="thC">Responsable</th>
                        <th id="fecha" class="thC">Fecha</th>
                        <th id="ruta" class="thC">Ruta</th>
                        <th id="descrip"class="thC">Descripción</th>
                        @if (auth()->check() && (auth()->user()->idtipo_usuario == 1 || auth()->user()->idtipo_usuario
                        == 4 ))
                        <th>Editar</th>
                        @endif
                        @if (auth()->check() && auth()->user()->idtipo_usuario == 1)
                        <th>Eliminar</th>
                        @endif

                    </tr>
                </thead>
                @forelse ($date as $items)
                <tbody>
                    <tr>
                        @if ($items->style)
                        <td style="{{ $items->style }}" data-tooltip="{{$items->texto  }}">{{ $items->position }}</td>
                        @else
                        <td>{{ $items->position }}</td>
                        @endif
                        <td>{{ $items->interno }}</td>
                        <td>{{ $items->usuario }}</td>
                        <td>{{ $items->fecha }}</td>
                        <td>{{ $items->nombreRuta }}</td>
                        <td>
                            <a type="button" class="viewDesc" data-id="{{ $items->idinforme }}"><i
                                    class="bi bi-eye-fill Objets"></i></a>
                        </td>
                        @if (auth()->check() && (auth()->user()->idtipo_usuario == 1 || auth()->user()->idtipo_usuario
                        == 4 ))

                        <td>
                            <a href="{{ route('Actualizar',$items->idinforme) }}" type="button"
                                data-id="{{ $items->idinforme }}"><i class="bi bi-pencil-square Objets"></i></a>
                        </td>
                        @endif
                        @if (auth()->check() && auth()->user()->idtipo_usuario == 1)
                        <td>
                            <a type="button" id="deleteInfo" data-id="{{ $items->idinforme }}"
                                data-interno="{{ $items->interno }}"><i class="bi bi-trash3-fill Objets"></i></a>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center" id="noData">No hay datos disponibles</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot id="filaNoResultados" style="display: none">
                    <tr>
                        <td colspan="10"><i class="bi bi-search"></i> No hay resultados que coincidan con la búsqueda
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div id="divPaginacion" class="d-flex justify-content-center">
                {{ $informe->withQueryString()->links() }}
            </div>
        </div>

        @if (auth()->check() && auth()->user()->idtipo_usuario == 1 || auth()->check() && auth()->user()->idtipo_usuario
        == 4 )
        <div class="modalDad">
            <div class="modal_content">
                <div class="modal-header" id="mHeader">
                    <div class="regiterTitle  text-center">
                        <label><img src="{{ asset('img/icons/conductor.png') }}" class="iconsRg" alt="">Registro
                            de Bitácora</label>
                    </div>
                    <span class="close cerrar"> <i class="bi bi-x-circle"></i></span>
                </div>
                <div class="modalDad-body Content ">
                    <div class="card" style="padding: 10px">
                        <form action="{{ route('createReport') }}" method="post" class="col-12" id="formCreate">
                            @csrf
                            <div class="row col-12">
                                <div class="col-md-6 ">
                                    <label>Interno</label>
                                    <input class="form-control text-center" type="text" placeholder="Ingrese Interno"
                                        id="nro_int" name="interno">
                                </div>
                                <div class="col-md-4">
                                    <label>Fecha *</label>
                                    <input type="date" class="form-control" id="date" name="fecha"
                                        value="{{ auth()->check() && auth()->user()->idtipo_usuario == 1 ? old('fecha') : date('Y-m-d') }}"
                                        {{ auth()->check() && auth()->user()->idtipo_usuario != 1 ? 'readonly' : '' }}>
                                </div>

                                <div class="col-md-6 form-floating mt-2 ">
                                    <div>
                                        <label>Descripción</label>
                                        <textarea class="form-control" placeholder="Digite la Descripcíon"
                                            style="height: 100px" name="descripcion" id="desc">
                                        </textarea>
                                    </div>

                                </div>

                                <div class="col-md-6 mt-4">
                                    <button type="submit" id="Registrar" class="btnRegister">
                                        <span class="Defecto">Registrar Informe</span>
                                        <span class="smallText">Registrar</span>
                                    </button>
                                    <a type="button" class="btnCancel cerrar">Cancelar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        {{-- <div class="modalDad2" id="modal2">
            <div class="modal_content">
                <div class="modal-header" id="mHeader">
                    <div class="regiterTitle  text-center">
                        <label><img src="{{ asset('img/icons/conductor.png') }}" class="iconsRg" alt="">Actualizar
        Bitácora</label>
    </div>
    <span class="close cerrarMod2"> <i class="bi bi-x-circle"></i></span>
    </div>
    <div class="modalDad-body Content ">
        <div class="card" style="padding: 10px">
            <form id="formUpdate">
                @csrf
                <div class="row col-12">
                    <div class="col-md-6 ">
                        <label>Nombre Completo *</label>
                        <input class="form-control text-center" type="text" placeholder="Ingrese Nombre"
                            name="nombreUpdate" required>
                    </div>
                    <div class="col-md-6 ">
                        <label>Apellido Completo *</label>
                        <input class="form-control text-center" type="text" placeholder="Ingrese Apellido"
                            name="apellidoUpdate" required>
                    </div>
                    <div class="col-md-6">
                        <label>Número de documento *</label>
                        <input class="form-control text-center" type="text" placeholder="Numero Doc."
                            name="documentoUpdate" readonly style="background-color: #eceeef;">
                    </div>
                    <div class="col-md-6 mt-4">
                        <button type="submit" id="UpdateM" class="btnRegister">
                            <span class="Defecto">Actualizar Motorista</span>
                            <span class="smallText">Actualizar</span>
                        </button>
                        <a type="button" class="btnCancel cerrarMod2">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>

    </div> --}}
    @endif
    <div class="modalDad3" id="modal3">
        <div class="modal_content">
            <div class="modal-header" id="mHeader">
                <div class="regiterTitle  text-center">
                    <label><img src="{{ asset('img/icons/conductor.png') }}" class="iconsRg" alt="">Descripción</label>
                </div>
                <span class="close" id="cerrar"> <i class="bi bi-x-circle"></i></span>
            </div>
            <div class="modalDad-body Content ">
                <div class="card" style="padding: 10px">
                    <div class="col-md-12 form-floating mt-2 ">
                        <div>
                            <label>Descripción</label>
                            <textarea class="form-control w-100" readonly id="description">
                                </textarea>
                        </div>
                    </div>
                    <div class="container Content">
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <label>Item:</label>
                                <ul class="list-group mb-2 mt-2">
                                    <span><strong id="itemsList"></strong></span>
                                </ul>
                            </div>

                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label class="form-inline">
                                        Estado Bitácora:
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <input type="radio" name="state" value="0"> Pendiente
                                    <input type="radio" name="state" value="1"> Realizado
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    </div>

    <script src="{{ asset('js/analista.js') }}"></script>
</section>
@endsection
