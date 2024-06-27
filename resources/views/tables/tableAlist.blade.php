<head>

    @section('title', 'Alistamiento')
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
                    <img src="{{ asset('img/icons/chequeo.png') }}" class="iconosTable" alt="">
                    Tabla de Alistamiento
                </span>
                <a href="{{route('createAlist')}}" type="button" id="Registrar" class="btn-Addnew">
                    <span>Agregar Alistamiento</span>
                </a>
            </div>
            <div class="row">
                 <div class="col-sm-4 col-md-4">
                        <div>
                            <form  id="pagination_form" action="{{ route('alistamiento') }}" method="get">
                                <label for="pagination_limit" class="form-inline mb-2 " style="margin-left: 15px;">mostrar
                                    <select name="per_page"  class="custom-select" id="pagination_limit" onchange="document.getElementById('pagination_form').submit()">
                                        <option value="25" @if($alistamiento->perPage() == 25) selected @endif>25</option>
                                        <option value="50" @if($alistamiento->perPage() == 50) selected @endif>50</option>
                                        <option value="75" @if($alistamiento->perPage() == 75) selected @endif>75</option>
                                    </select>
                                 registros</label>
                            </form>

                        </div>
                    </div>

                <div class="col-sm-4 col-md-4">
                @if (auth()->check() && auth()->user()->idtipo_usuario == 1 || auth()->check() && auth()->user()->idtipo_usuario == 2 )
                    <div>
                        <span class="excel" id="desc1"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                                width="20px" viewBox="0 0 30 30">
                                <path d="M 15 3 A 2 2 0 0 0 14.599609 3.0429688 L 14.597656 3.0410156
                                    L 4.6289062 5.0351562 L 4.6269531 5.0371094 A 2 2 0 0 0 3 7 L 3 23 A 2 2 0 0 0
                                    4.6289062 24.964844 L 14.597656 26.958984 A 2 2 0 0 0 15 27 A 2 2 0 0 0 17 25 L 17
                                     5 A 2 2 0 0 0 15 3 z M 19 5 L 19 8 L 21 8 L 21 10 L 19 10 L 19 12 L 21 12 L 21 14 L
                                     19 14 L 19 16 L 21 16 L 21 18 L 19 18 L 19 20 L 21 20 L 21 22 L 19 22 L 19 25 L 25
                                     25 C 26.105 25 27 24.105 27 23 L 27 7 C 27 5.895 26.105 5 25 5 L 19 5 z M 23 8 L 24 8
                                     C 24.552 8 25 8.448 25 9 C 25 9.552 24.552 10 24 10 L 23 10 L 23 8 z M 6.1855469 10
                                      L 8.5878906 10 L 9.8320312 12.990234 C 9.9330313 13.234234 10.013797 13.516891
                                      10.091797 13.837891 L 10.125 13.837891 C 10.17 13.644891 10.258531 13.351797
                                      10.394531 12.966797 L 11.785156 10 L 13.972656 10 L 11.359375 14.955078 L 14.050781
                                      19.998047 L 11.716797 19.998047 L 10.212891 16.740234 C 10.155891 16.625234 10.089203
                                      16.393266 10.033203 16.072266 L 10.011719 16.072266 C 9.9777187 16.226266 9.9105937
                                      16.458578 9.8085938 16.767578 L 8.2949219 20 L 5.9492188 20 L 8.7324219 14.994141 L
                                      6.1855469 10 z M 23 12 L 24 12 C 24.552 12 25 12.448 25 13 C 25 13.552 24.552 14 24
                                      14 L 23 14 L 23 12 z M 23 16 L 24 16 C 24.552 16 25 16.448 25 17 C 25 17.552 24.552
                                      18 24 18 L 23 18 L 23 16 z M 23 20 L 24 20 C 24.552 20 25 20.448 25 21 C 25 21.552
                                      24.552 22 24 22 L 23 22 L 23 20 z">
                                </path>
                            </svg><span id="des">Descargar</span> </span>
                    </div>
                    @endif
                </div>
                <div class="col-sm-4 col-md-4">
                    <div>
                        <label class="form-inline mb-2 " style="float: right;">Buscar:
                            <input type="search" class="custom-select" id="searchData" placeholder="Dato a buscar">
                        </label>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover text-center" id="tableAlist">
                <thead class="theadMantenmiento">
                    <tr>
                        <th id="pos" class="thC">
                            #
                        </th>
                        <th id="fecha" class="thC">
                            fecha
                        </th>

                        <th id="placa" class="thC">
                            placa
                        </th>
                        <th id="docMotorista" class="thC">
                            Documento Motorista
                        </th>
                        <th id="motorista" class="thC">
                            Nombre de Motorista
                        </th>
                        <th id="NumeroInterno" class="thC">
                            Número Interno
                        </th>

                        <th id="hora" class="thC">
                            Hora
                        </th>

                        <th id="aprobado" class="thC">
                            Aprobado
                        </th>
                        <th id="reponsable" class="thC">
                            Responsable
                        </th>
                        <th id="view">
                            PDF
                        </th>
                        @if (auth()->check() && auth()->user()->idtipo_usuario == 1)
                        <th id="delete">
                           Eliminar
                        </th>
                       @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alistamiento as $alistamientos )
                    <tr>
                        <td class="delTd">{{str_pad( $alistamientos->position,2,'0',STR_PAD_LEFT) }}</td>
                        <td>{{ $alistamientos->fecha_chequeo }}</td>
                        <td>{{ strtoupper($alistamientos->placa) }}</td>
                        <td>{{ $alistamientos->numconductor }}</td>
                        <td>{{ strtoupper($alistamientos->nombreConduc) }}</td>
                        <td >{{ $alistamientos->nroInterno }}</td>
                        <td class="delTd">{{ $alistamientos->hora }}</td>
                        <td>
                            {{ $alistamientos->aprobadoText }}
                        </td>
                        <td>
                            {{strtoupper($alistamientos->responsable) }}
                        </td>

                        <td>
                            <a href="{{ route('detailPdf', ['id' => $alistamientos->idchequeo]) }}" target="_blank"><i
                                    class="bi bi-filetype-pdf Objets"></i></a>

                        </td>
                        @if (auth()->check() && auth()->user()->idtipo_usuario == 1)
                        <td>
                                 <a type="button" id="deleteTrash"  data-nro-interno="{{ $alistamientos->nroInterno }}" data-alist-id="{{ $alistamientos->idchequeo }}"><i class="bi bi-trash3-fill Objets"></i></a>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center" id="noData">No hay datos disponibles</td>
                    </tr>
                    @endforelse
                <tfoot id="filaNoResultados" style="display: none">
                    <tr>
                        <td colspan="11"><i class="bi bi-search"></i> No hay resultados que coincidan con la búsqueda
                        </td>
                    </tr>
                </tfoot>

                </tbody>
            </table>
            <div id="divPaginacion" class="d-flex justify-content-center">
                {{ $alistamiento->withQueryString()->links() }}
            </div>
        </div>
    </div>
    <script>
        var imgSultanaUrl = "{{ asset('img/sultana.jpg') }}";
    </script>
    <script src="{{ asset('js/tableAlist.js') }}"></script>
</section>
@endsection
