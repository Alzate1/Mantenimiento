<head>
    <link rel="shortcut icon" href="{{asset('img/icon.png')}}">

    @section('title', 'Mantenimiento')
    <link rel="stylesheet" href="{{ asset('css/tManteniento.css') }}">
</head>
@extends('layauts.header')
@section('contenido')
    <section>
        <style>
            .thC {
                cursor: pointer;
            }
            td{
                cursor: default;
            }

        </style>
        <div class="container mt-4">
            <div class="card col-12">
                <div class="cards-headers">
                    <span>
                        <img src="{{ asset('img/icons/mantenimiento.png') }}" class="iconosTable" alt="">
                        Tabla de matenimiento
                    </span>
                    <a href="{{ route('createMante') }}" type="button" id="Registrar" class="btn-Addnew">
                        <span>Agregar Mantenimiento</span>
                    </a>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div>
                            <form  id="pagination_form" action="{{ route('tableMante') }}" method="get">
                                <label for="pagination_limit" class="form-inline mb-2 " style="margin-left: 15px;">mostrar
                                    <select name="per_page"  class="custom-select" id="pagination_limit" onchange="document.getElementById('pagination_form').submit()">
                                        <option value="25" @if($revisiones->perPage() == 25) selected @endif>25</option>
                                        <option value="50" @if($revisiones->perPage() == 50) selected @endif>50</option>
                                        <option value="75" @if($revisiones->perPage() == 75) selected @endif>75</option>
                                    </select>
                                 registros</label>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div>
                            <label  class="form-inline mb-2 " style="float: right;">Buscar:
                                <input type="search" class="custom-select" id="searchData" placeholder="Dato a buscar">
                            </label>
                        </div>
                    </div>
                </div>
                <table class="table table-striped  text-center" id="tableMant">
                    <thead class="theadMantenmiento">
                        <tr>
                            <th id="pos" class="thC">
                                #
                            </th>
                            <th id="placa" class="thC">
                                placa
                            </th>
                            <th id="NumeroInterno" class="thC">
                                Número Interno
                            </th>
                            <th id="motorista" class="thC">
                                Documento Motorista
                            </th>
                            <th id="ruta" class="thC">
                                Ruta
                            </th>
                            <th id="fecha" class="thC">
                                Fecha
                            </th>
                            <th id="hora" class="thC">
                                Hora
                            </th>

                            <th>
                                Revisiones
                            </th>

                            <th>
                                PDF
                            </th>

                            @if (auth()->check() && auth()->user()->idtipo_usuario == 1)
                            <th>
                               Eliminar
                            </th>
                           @endif
                        </tr>
                    </thead>
                    @if (count($revision) > 0)
                        <tbody>

                            @forelse ($revision as $revisionInfo)
                                <tr>

                                    <td class="delTd" style="{{ $revisionInfo->estado == 2 ? 'color:red;' : '' }}"
                                        @if($revisionInfo->estado == 2) data-tooltip="Revision correctiva: pendiente" @endif>
                                        {{ str_pad($revisionInfo->position, 3, '0', STR_PAD_LEFT) }}

                                    </td>
                                    <td>{{ strtoupper($revisionInfo->placa) }}</td>
                                    <td>{{ $revisionInfo->nro_interno }}</td>
                                    <td>{{ $revisionInfo->numconductor }}</td>
                                    <td>{{ strtoupper($revisionInfo->nombreRuta) }}</td>
                                    <td>{{ $revisionInfo->fecha }}</td>
                                    <td>{{ $revisionInfo->hora }}</td>

                                    <td>
                                        <a type="button" id="ver" class="abrir"
                                            data-revision-id="{{ $revisionInfo->idrevision }}"
                                            data-nro-interno="{{ $revisionInfo->nro_interno }}">
                                            <i class="bi bi-eye Objets"></i>
                                        </a>
                                    </td>

                                    <td><a href="{{ route('pdfD', ['id' => $revisionInfo->idrevision, 'idvehiculo' => $revisionInfo->id_vehiculo]) }}"
                                            target="_blank"><i class="bi bi-filetype-pdf Objets"></i></a></td>


                                    @if (auth()->check() && auth()->user()->idtipo_usuario == 1)
                                    <td>
                                             <a type="button" id="deleteTrash"  data-nro-interno="{{ $revisionInfo->nro_interno }}" data-revision-id="{{ $revisionInfo->idrevision }}"  data-placa="{{ $revisionInfo->placa}}"><i class="bi bi-trash3-fill Objets"></i></a>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">No hay datos disponibles</td>
                                </tr>
                            @endforelse
                        <tfoot id="filaNoResultados" style="display: none">
                            <tr>
                                <td colspan="12"><i class="bi bi-search"></i> No hay resultados que coincidan con la
                                    búsqueda.
                                </td>
                            </tr>
                        </tfoot>
                        </tbody>
                </table>
            </div>
            <div id="divPaginacion" class="d-flex justify-content-center">

                {{ $revisiones->links() }}

            </div>
            <div class="modalDad" id="modal">
                <div class="modal_content">
                    <div class="modal-header" id="mHeader" style=" ">
                        <span>
                            <h2>Listado de Revisiones</h2>
                        </span>

                        <span class="close"><i class="bi bi-x-circle"></i></span>
                    </div>
                    <div class="">
                        <div class="modalDad-body Content ">
                            <div class="cards-header">
                                <span><i class="bi bi-bus-front"></i>Vehiculo <strong id="strongInterno"></strong></span>
                            </div>
                            <div class="tableRev" id="tableRev" style="display: none;">
                                <div class="card " style="padding: 10px">
                                    <div class="mb-4">
                                        <a type="button" id="addRev" class="addRev btn-Addnew"
                                            data-nro-interno="{{ $revisionInfo->nro_interno }}">
                                            Agregar Revisión
                                        </a>
                                    </div>
                                    <table class="table table-striped  text-center tableRevCorrectiva"
                                        id="revTable">
                                        <thead class="theadMantenmiento">
                                            <tr>
                                                <th>
                                                    #
                                                </th>
                                                <th>
                                                    Fecha
                                                </th>
                                                <th>
                                                    Centro Especializado
                                                </th>
                                                <th>
                                                  Detalle Mantenimiento
                                                </th>
                                                <th>
                                                    Subir Archivos
                                                </th>
                                                <th>
                                                   Ver Archivos
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modalDad2" id="modal2">
                <div class="modal_content">
                    <div class="modal-header" id="mHeader">
                        <span>
                            <h2>Revisión correctiva</h2>
                        </span>
                        <span class="close2"> <i class="bi bi-x-circle"></i></span>
                    </div>
                    <div class="">
                        <div class="modalDad-body Content ">
                            <div class="cards-header">
                                <span><i class="bi bi-bus-front"></i>Veh+iculo <strong id="strongInterno2"></strong></span>
                            </div>
                            <div class="formRevision" id="formRevision">
                                <div class="card" style="padding: 10px">
                                    <form action="{{ route('create.serve') }}" method="post" id="formServ">
                                        @csrf
                                        <input type="hidden" name="id_revision" id="id_revision"
                                            value="{{ $revisionInfo->idrevision }}">
                                        <div class="container mb-4">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>fecha</label>
                                                    <input class="form-control" type="date" name="fecha"
                                                        id="fechaRev" aria-label="default input example">

                                                </div>
                                                <div class="col-md-4">
                                                    <label>Centro Revisión</label>
                                                    <input class="form-control" type="text" name="centroRev"
                                                        id="centroRev" placeholder="Digite el centro de revisión"
                                                        aria-label="default input example">

                                                </div>
                                                <div class="col-md-4">
                                                    <label>Detalle Revisión</label>
                                                    <textarea name="detailsRev" class="textarea form-control" id="detailsRev" cols="30" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">

                                            <div class="button-form d-flex justify-content-end align-items-end">
                                                <button type="submit" id="registrar" class="btn-Addnew"
                                                    data-idrevision="{{ $revisionInfo->idrevision }}">
                                                    Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modalDad3" id="modal3">
                <div class="modal_content">
                    <div class="modal-header" id="mHeader" style=" ">
                        <span>
                            <h2>Listado de Archivos</h2>
                        </span>
                        <span class="close3"> <i class="bi bi-x-circle"></i></span>
                    </div>
                    <div class="">
                        <div class="modalDad-body Content ">
                            <div class="cards-header">
                                <span><i class="bi bi-bus-front"></i>Vehiculo <strong id="strongInterno3"></strong></span>

                            </div>
                            <div class="tableArch">
                                <div class="card" style="padding: 10px">
                                    <table class="table table-striped table-hover text-center" id="tableAnexo">
                                        <thead class="theadMantenmiento">
                                            <tr>
                                                <th>
                                                    #
                                                </th>
                                                <th>
                                                    Fecha de Cargue
                                                </th>
                                                <th>
                                                    Nombre Archivo
                                                </th>
                                                <th>
                                                    Ver Archivo
                                                </th>
                                                <th>
                                                    eliminar
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modalDad4" id="modal4">
                <div class="modal_content">
                    <div class="modal-header" id="mHeader" style=" ">
                        <span>
                            <h2>Cargar Archivos</h2>
                        </span>
                        <span class="close4"> <i class="bi bi-x-circle"></i></span>
                    </div>
                    <div class="">
                        <div class="modalDad-body Content ">
                            <div class="cards-header">
                                <span><i class="bi bi-bus-front"></i>Vehiculo <strong
                                        id="strongInterno4">NumInterno</strong></span>
                            </div>
                            <div class="tableArch">
                                <div class="card" style="padding: 10px">
                                    <form action="{{ route('create.anexo') }}" enctype="multipart/form-data"
                                        method="post" id="formAnexo">
                                        @csrf
                                        <input type="hidden" name="idrev" id="idrev">
                                        <div class="container mb-4">
                                            <div class="row">
                                                <div class="col-md-12 mt-2 text-center  mb-4">
                                                    <div id="adjunt">
                                                        <label>Adjuntar Archivos</label>
                                                    </div>
                                                    <label for="enviarFile" id="fileid" class="fileid">
                                                        <i class="bi bi-hand-index"></i> Click aqui
                                                        <input type="file" id="enviarFile" name="anexos[]"
                                                            style="display: none" multiple>
                                                    </label>
                                                    <div class="text-center d-flex justify-content-center align-items-center"
                                                        id="viewFiles">
                                                    </div>
                                                    <div id="labelTitle" class="text-center">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">

                                            <div class="button-form d-flex justify-content-end align-items-end">
                                                <button type="submit" id="registrar" class="btn-Addnew">
                                                    Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        @else
            <tr>
                <td colspan="12" class="text-center">No hay datos disponibles</td>
            </tr>
            @endif
        </div>

        <script src="{{ asset('js/tableMant.js') }}"></script>
        <script>
            const imgPdfg = "{{ asset('img/icons/pdf.png') }}";
            const imgFile = "{{ asset('img/icons/requisito.png') }}";
            var base = "{{ asset('storage/app/public') }}";
        </script>
    </section>
@endsection
