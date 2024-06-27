<head>
    <link rel="shortcut icon" href="{{ asset('img/SM.png') }}">
    @section('title', 'Usuarios')
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
                        <img src="{{ asset('img/icons/grupo.png') }}" class="iconosTable"alt="">
                        Tabla de usuarios
                    </span>
                    <a href="{{ route('register') }}" type="button" id="Registrar" class="btn-Addnew">
                        <span>Agregar Usuario</span>
                    </a>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div>
                            <form  id="pagination_form" action="{{ route('users') }}" method="get">
                                <label for="pagination_limit" class="form-inline mb-2 " style="margin-left: 15px;">mostrar
                                    <select name="per_page"  class="custom-select" id="pagination_limit" onchange="document.getElementById('pagination_form').submit()">
                                        <option value="25" @if($users->perPage() == 25) selected @endif>25</option>
                                        <option value="50" @if($users->perPage() == 50) selected @endif>50</option>
                                        <option value="75" @if($users->perPage() == 75) selected @endif>75</option>
                                    </select>
                                 registros</label>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div>
                            <form action="" method="post">
                                <label  class="form-inline mb-2 " style="float: right;">Buscar:
                                    <input type="search" id="searchData" class="custom-select" placeholder="Dato a buscar  ">
                                </label>
                            </form>

                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover text-center" id="tableUsers">
                    <thead class="theadMantenmiento">
                        <tr>
                            <th id="thNumber" class="thC" colspan="1"  data-order="asc">
                                #
                                     <i class="fas fa-sort"></i>
                            </th>

                            <th id="thName" class="thC" colspan="1"  data-order="asc">
                               Nombres
                                    <i class="fas fa-sort"></i>
                            </th>
                            <th id="thLast_name" class="thC" colspan="1"  data-order="asc">
                                Apellidos
                                     <i class="fas fa-sort"></i>
                            </th>
                            <th id="thUsers" class="thC" colspan="1"  data-order="asc">
                               Usuario
                                    <i class="fas fa-sort"></i>
                            </th>

                            <th id="thEmail" class="thC" colspan="1"  data-order="asc">
                                Correo
                                     <i class="fas fa-sort"></i>
                            </th>

                            <th id="" colspan="1">
                                Editar
                            </th>
                            <th id="" colspan="1">
                                Eliminar
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{str_pad( $position,2,'0',STR_PAD_LEFT) }}</td>
                            <td>{{ mb_strtoupper($user->nombre_usuario) }}</td>
                            <td>{{ mb_strtoupper($user->apellido) }}</td>
                            <td>{{ mb_strtoupper($user->usuario) }}</td>
                            <td>{{ mb_strtoupper($user->correo) }}</td>
                            <td>
                                <a href="{{ route('user.edit',$user->idusuario)}}">
                                    <i class="bi bi-pencil-square Objets"></i></a>
                            </td>
                            <td>
                                <button type="button" style="background-color: transparent" class="btn-delete" data-id="{{ $user->idusuario }}" data-name="{{ $user->nombre_usuario }}" data-apellido="{{ $user->apellido }}" ><i class="bi bi-trash3-fill Objets" ></i></button>
                            </td>
                        </tr>
                        @php
                         $position++;
                     @endphp
                     @empty
                     <tr>
                        <td colspan="7" class="text-center">No hay datos disponibles</td>
                    </tr>
                     @endforelse

                    </tbody>
                    <tfoot id="filaNoResultados" style="display: none">
                        <tr>
                            <td colspan="7"><i class="bi bi-search"></i> No hay resultados que coincidan con la búsqueda.</td>
                        </tr>
                    </tfoot>
                </table>
                <div id="divPaginacion" class="d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    <script src="{{asset('js/tableUser.js')}}"></script>

    </section>
@endsection
