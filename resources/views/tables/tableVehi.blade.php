<head>
    <link rel="shortcut icon" href="{{ asset('img/SM.png') }}">
    @section('title', 'Vehículo')
    <link rel="stylesheet" href="{{ asset('css/tManteniento.css') }}">
</head>
@extends('layauts.header')
@section('contenido')
    <section>
        <style>
            .thC {
                cursor: pointer;
            }
        </style>
        <div class="container mt-4">
            <div class="card col-12">
                <div class="cards-headers">
                    <span>
                        <img src="{{ asset('img/icons/autobus.png') }}" class="iconosTable"alt="">
                        Tabla de Vehículos
                    </span>
                @if (auth()->check() && auth()->user()->idtipo_usuario == 1 || auth()->check() && auth()->user()->idtipo_usuario == 2 )
                    <a href="{{ route('createVehiculo') }}" type="button" id="Registrar" class="btn-Addnew">
                        <span>Agregar vehículo</span>
                    </a>
                    @endif
                </div>
                <div class="row d-flex">
                    <div class="col-sm-12 col-md-6">
                        <div>
                            <form id="pagination_form" action="{{ route('vehiculos') }}" method="get">
                                <label for="pagination_limit" class="form-inline mb-2 " style="margin-left: 15px;">mostrar
                                    <select name="per_page" class="custom-select" id="pagination_limit"
                                        onchange="document.getElementById('pagination_form').submit()">
                                        <option value="25" @if ($vehiculo->perPage() == 25) selected @endif>25</option>
                                        <option value="50" @if ($vehiculo->perPage() == 50) selected @endif>50</option>
                                        <option value="75" @if ($vehiculo->perPage() == 75) selected @endif>75</option>
                                    </select>
                                    registros</label>
                            </form>

                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 mt-2">
                        <div class="">
                            <label class="form-inline mb-2 " style="float: right;">Buscar:
                                <input type="search" class="custom-select" placeholder="Dato a buscar" id="searchData">
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <div id="dataContainerV">
                        <table class="table table-responsive table-striped table-hover text-center" id="tableVehi">
                            <thead class="theadMantenmiento">
                                <tr>
                                    <th class="thC" id="pos">
                                        #
                                    </th>
                                    <th class="thC" id="motorista">
                                        Nombre Motorista
                                    </th>
                                    <th class="thC" id="nroInt">
                                        Número Interno
                                    </th>
                                    <th class="thC" id="placa">
                                        placa
                                    </th>
                                    <th class="thC" id="conductor">
                                        Doc. Conductor
                                    </th>

                                    <th class="thC" id="ruta">
                                        Ruta
                                    </th>
                                    @if (
                                        (auth()->check() && auth()->user()->idtipo_usuario == 1) ||
                                            (auth()->check() && auth()->user()->idtipo_usuario == 2))
                                        <th>
                                            Editar
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                                <div>

                                </div>
                                @forelse ($vehiculo as $vehiculos)
                                    <tr>
                                        <td>{{ str_pad($vehiculos->position, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ strtoupper($vehiculos->nombre_motorista) }}</td>
                                        <td>{{ $vehiculos->nro_interno }}</td>
                                        <td>{{ strtoupper($vehiculos->placa) }}</td>
                                        <td>{{ strtoupper($vehiculos->documento) }}</td>
                                        <td>{{ strtoupper($vehiculos->nombreRuta) }}</td>
                                        @if (auth()->check() && auth()->user()->idtipo_usuario == 1 || auth()->check() && auth()->user()->idtipo_usuario == 2 )
                                        <td>

                                            <a href="{{ route('vehiEdit', $vehiculos->idvehiculo) }}"><i
                                                    class="bi bi-pencil-square Objets"></i></a>
                                        </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            <tfoot id="filaNoResultados" style="display: none">
                                <tr>
                                    <td colspan="7"><i class="bi bi-search"></i> No hay resultados que coincidan con la
                                        búsqueda.</td>
                                </tr>
                            </tfoot>
                            </tbody>

                        </table>
                        <div id="divPaginacion" class="d-flex justify-content-center">
                            {{ $vehiculo->withQueryString()->links() }}
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <script src="{{ asset('js/tableVehi.js') }}"></script>


    </section>
@endsection
