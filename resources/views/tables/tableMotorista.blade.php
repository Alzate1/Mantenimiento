<head>
    @section('title', 'Motorista')
    <link rel="stylesheet" href="{{ asset('css/tManteniento.css') }}">
</head>
@extends('layauts.header')
@section('contenido')
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
        <div class="card col-12">
            <div class="cards-headers">
                <span>
                    <img src="{{ asset('img/icons/conductor.png') }}" class="iconosTable" alt="">
                    Tabla de Motorista
                </span>
                @if (auth()->check() && auth()->user()->idtipo_usuario == 1 || auth()->check() && auth()->user()->idtipo_usuario == 2 )
                <button type="button" id="verArch" class="btn-Addnew abrir">
                    <span>Agregar Motorista</span>
                </button>
                @endif
            </div>
            <div class="row d-flex">
                <div class="col-sm-12 col-md-6">
                    <div>
                        <form  id="pagination_form" action="{{ route('motorista') }}" method="get">
                            <label for="pagination_limit" class="form-inline mb-2 " style="margin-left: 15px;">mostrar
                                <select name="per_page"  class="custom-select" id="pagination_limit" onchange="document.getElementById('pagination_form').submit()">
                                    <option value="25" @if($motorista->perPage() == 25) selected @endif>25</option>
                                    <option value="50" @if($motorista->perPage() == 50) selected @endif>50</option>
                                    <option value="75" @if($motorista->perPage() == 75) selected @endif>75</option>
                                </select>
                             registros</label>
                        </form>

                    </div>
                </div>
                <div class="col-sm-12 col-md-6 mt-2">
                    <div class="">
                        <label  class="form-inline mb-2 " style="float: right;">Buscar:
                            <input type="search" class="custom-select" placeholder="Dato a buscar" id="searchData">
                        </label>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover text-center" id="tableMotorista">
                <thead class="theadMantenmiento">
                    <tr>
                        <th id="pos" class="thC">
                            #
                        </th>
                        <th id="nombre" class="thC">
                            Nombre
                        </th>
                        <th id="apellido" class="thC">
                            Apellido
                        </th>
                        <th id="documento" class="thC">
                            Documento
                        </th>
                        @if (auth()->check() && auth()->user()->idtipo_usuario == 1 || auth()->check() && auth()->user()->idtipo_usuario == 2 )
                        <th>
                            Editar
                        </th>
                        @endif
                    </tr>
                </thead>
                @forelse ($motorista as $motoristas )
                <tbody>
                    <tr>
                        <td>{{str_pad( $position,2,'0',STR_PAD_LEFT) }}</td>
                        <td>{{ $motoristas->nombre }}</td>
                        <td>{{ $motoristas->apellido }}</td>
                        <td>{{ $motoristas->documento }}</td>
                        @if (auth()->check() && auth()->user()->idtipo_usuario == 1 || auth()->check() && auth()->user()->idtipo_usuario == 2 )
                        <td>
                            <a type="button" class="editMotorista"  data-id="{{ $motoristas->idmotorista }}"><i class="bi bi-pencil-square Objets"></i></a>
                        </td>
                        @endif
                    </tr>
                    @php
                    $position++;
                @endphp
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
                {{ $motorista->withQueryString()->links() }}
            </div>
        </div>
        @if (auth()->check() && auth()->user()->idtipo_usuario == 1 || auth()->check() && auth()->user()->idtipo_usuario == 2 )
        <div class="modalDad">
            <div class="modal_content">
                <div class="modal-header" id="mHeader">
                    <div class="regiterTitle  text-center">
                        <label><img src="{{ asset('img/icons/conductor.png') }}" class="iconsRg" alt="">Registro
                            de Motorista</label>
                    </div>
                    <span class="close cerrar"> <i class="bi bi-x-circle"></i></span>
                </div>
                <div class="modalDad-body Content ">
                    <div class="card" style="padding: 10px">
                        <form action="{{ route('motoristaCreate') }}" method="post" class="col-12" id="formCreate">
                            @csrf
                            <div class="row col-12">
                                <div class="col-md-6 ">
                                    <label>Nombre Completo *</label>
                                    <input class="form-control text-center" type="text" placeholder="Ingrese Nombre"
                                        name="nombre" required>
                                </div>
                                <div class="col-md-6 ">
                                    <label>Apellido Completo *</label>
                                    <input class="form-control text-center" type="text" placeholder="Ingrese Apellido"
                                        name="apellido" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Número de documento *</label>
                                    <input class="form-control text-center" type="text" placeholder="Numero Doc."
                                        name="documento" required>
                                </div>
                                <div class="col-md-6 mt-4">
                                    <button type="submit" id="Registrar" class="btnRegister">
                                        <span class="Defecto">Registrar usuario</span>
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
        <div class="modalDad2" id="modal2">
            <div class="modal_content">
                <div class="modal-header" id="mHeader">
                    <div class="regiterTitle  text-center">
                        <label><img src="{{ asset('img/icons/conductor.png') }}" class="iconsRg" alt="">Actualizar
                            Motorista</label>
                    </div>
                    <span class="close cerrar"> <i class="bi bi-x-circle"></i></span>
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
                                    <a type="button" class="btnCancel cerrar">Cancelar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        @endif
    </div>

   <script src="{{ asset('js/js.js') }}"></script>
</section>
@endsection
