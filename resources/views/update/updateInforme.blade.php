<head>
    @section('title', 'Actualizar Informe')
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
    <div class="Content col-12">
        <form method="POST" action="{{ route('informe.update',$informe->idinforme) }}" id="updateInforme">
            @csrf
            @method('PUT')
            <details open >
                <summary>
                    <small>
                        Actualizar Informe
                    </small>

                </summary>
                <div class="card mb-2">
                    <div class="row mb-2 mt-2" style="margin-left: 2px;margin-right: 2px">
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Número Interno*</label>
                            <input type="text" class="form-control" id="nro_interno" name="nro_interno" value="{{ $vehiculo->nro_interno }}">
                        </div>
                        {{-- INPUT DEL ID VEHICULO --}}
                        <input type="hidden" id="idVehi">
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
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" placeholder="Digite la descripción" id="desc" name="descripcion">
                                {{ $informe->descripcion }}
                            </textarea>

                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label">Seleccionar Item</label>
                            <div class="form-check">
                                @foreach ($allItems as $items)
                                <div>
                                    <input class="form-check-input" type="checkbox" name="itemselect[]" value="{{ $items->id }}" id="item" {{in_array($items->id,$relatedItems)? 'checked ' : ''  }}>
                                    <label class="form-check-label">
                                        {{ $items->nombre }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="button-form">
                            <button type="button" id="volverAnalist" class="btnCancel">Cancelar</button>

                            <button type="submit" id="registrar" class="btnSave">
                                Actualizar Informe
                            </button>
                        </div>
                    </div>
                </div>

            </details>
        </form>


    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            document.getElementById('volverAnalist').addEventListener('click',(e)=>{
                // var vehiculo =
                window.location.href ="{{ route('analistas') }}"
            })
            $('#updateInforme').submit(function (event) {
            event.preventDefault();
            var desc = $("#desc").val().trim();
            var fecha = $("#date").val();
            var nroInterno = $('#nro_interno').val();
            var idTipoInfo = $('#idTipoInfo').val();
            var item = $('.form-check-input:checked').length > 0;
            if (nroInterno === '' || nroInterno === null) {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Debes ingresar el numero de interno",
                showConfirmButton: false,
                timer: 1500
            });
            return false;
            } if (fecha === '' || fecha === null) {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Debes ingresar la fecha",
                showConfirmButton: false,
                timer: 1500
            });
            return false;
            } if (idTipoInfo == '' || idTipoInfo == null  || idTipoInfo == 'Tipo de Informe') {
            Swal.fire({
                icon: 'info',
                title: 'Seleccionar el Informe',
                text: 'Debe seleccionar al menos un tipo de Informe.',
            });
            return false;
            }if (!item) {
            Swal.fire({
                icon: 'info',
                title: 'Seleccionar Item',
                text: 'Debe seleccionar al menos un item.',
            });
            return false;
            }
            if (desc === '' || desc === null) {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Debes ingresar la descripción",
                showConfirmButton: false,
                timer: 1500
            });
                return false;
            }
            else {
                $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr('action'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success) {
                        Swal.fire({
                            title: 'Cargando...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 3500

                        }).then(function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Correcto',
                                text: 'Informe registrado correctamente',
                                showCancelButton: true,
                            }).then(function() {
                                window.location.href ="{{ route('analistas') }}"
                            })
                        })

                    } if (data.notVehicle) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No existe el numero de Interno',
                        });

                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al registrar el Informe',
                    });
                }

            });
        }

    });

        })


    </script>
</section>

@endsection
