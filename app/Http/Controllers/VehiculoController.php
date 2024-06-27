<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\Vehiculo;
use App\Models\Documentos;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Grupos;
use App\Models\Motorista;
use Illuminate\Support\Facades\Log;

class VehiculoController extends Controller
{
    //TRAER LOS DATOS DE  LA TABLA RUTA
    public function grupo_ruta()
    {
        $grupos = Grupos::all();
        $ruta = Ruta::all();
        return view('create.createVehi', ['grupos' => $grupos, 'ruta' => $ruta]);
    }
    public function busquedaPorDocumento($documento)
    {
        $motorista = Motorista::where('documento', $documento)->first();
        if ($motorista) {
            return response()->json([
                'nombre' => $motorista->nombre,
                'apellido' => $motorista->apellido,
                'documento' => $documento
            ]);
        } else {
            return response()->json(['error' => 'Documento no encontrado']);
        }
    }

    public function createNewGroup(Request $request)
    {
        try {
            $request->validate([
                'desc_grupo' => 'required|string|max:255|unique:grupos,desc_grupo',
            ]);

            $nuevoGrupo = new Grupos();
            $nuevoGrupo->desc_grupo = $request->input('desc_grupo');
            $nuevoGrupo->save();

            return response()->json(['exitoso' => true]);
        } catch (\Throwable $th) {
            Log::error('Error al crear el grupo: ' . $th->getMessage());
            return response()->json(['exitoso' => false, 'error' => $th->getMessage()], 500);
        }
    }

    public function createVehi(Request $request)
    {
        $request->validate([
            'placa' => [
                'required','string',
                Rule::unique('vehiculo')->where(function($query) use($request){
                    return $query->where('placa', $request->placa);
                })
            ],
            'id_ruta' => 'required|exists:ruta,id_ruta',
            'nro_interno' => [
                'required','string',
                Rule::unique('vehiculo')->where(function($query) use($request){
                    return $query->where('nro_interno', $request->nro_interno);
                })
            ],
            'documento_propietario' => 'required',
        ]);
       try {
        $documento = $request->input('documento');
        $motorista = Motorista::where('documento', $documento)->first();
           // GUARDAR VEHICULO
        $vehiculo = new Vehiculo();
        $vehiculo->id_motorista = $motorista->idmotorista;
        $vehiculo->documento_propietario = $request->input('documento_propietario');
        $vehiculo->id_grupo = $request->input('id_grupo');
        $vehiculo->nro_interno = $request->input('nro_interno');
        $vehiculo->placa = $request->input('placa');
        $vehiculo->id_ruta = $request->input('id_ruta');
        $vehiculo->save();
           //GUARDAR DOCUMENTOS
        $documento = new Documentos();
        $documento->id_vehiculo = $vehiculo->idvehiculo;
        $documento->soat = $request->input('soat');
        $documento->revision_tmc = $request->input('revision_tmc');
        $documento->extra_contra = $request->input('extra_contra');
        $documento->tarjeta_operacion = $request->input('tarjeta_operacion');
        $documento->km_actual = $request->input('km_actual');
        $documento->tarjeta_propiedad = $request->input('tarjeta_propiedad');
        $documento->save();
        return response()->json(['exitoso' => true]);

       } catch (\Exception $e) {
        Log::error('Error al guardar el vehículo: ' . $e->getMessage());
        return response()->json(['exitoso' => false, 'error' => 'Hubo un error al registrar el vehiculo'. $e->getMessage()],500);
    }

    }
    public function tableVehi(Request $request){
        $paginations= $request->input('per_page',25);
            $vehiculo = Vehiculo::orderBy('created_at','desc')->paginate($paginations);
            $currentPage = $vehiculo->currentPage();
            $itemsPage = $vehiculo->perPage();
            $Startposition =($currentPage -1)*$itemsPage +1;
            foreach ($vehiculo as $vehiculos) {
               $ruta= Ruta::find($vehiculos->id_ruta);
               $motorista= Motorista::find($vehiculos->id_motorista);
               $vehiculos->nombreRuta=$ruta ? $ruta->descripcion:'Ruta no encontrada';
               $vehiculos->nombre_motorista =$motorista ? $motorista->nombre:'Nombre no encontrado';
               $vehiculos->documento =$motorista ? $motorista->documento:'documento no encontrado';
               $vehiculos->position = $Startposition;
               $Startposition++;
            }
            return view("tables.tableVehi",['vehiculo'=>$vehiculo]);

    }



    public function editVehi($id){
        $vehiculo = Vehiculo::find($id);
        $grupos = Grupos::all();
        $ruta = Ruta::all();
        $documento= Documentos::where('id_vehiculo',$id)->first();
            $motorista = Motorista::find($vehiculo->id_motorista);
            $motoristas = $motorista ? ['nombre' => $motorista->nombre, 'apellido' => $motorista->apellido,'documento' => $motorista->documento] : null;
            return view('update.updateVehi',compact('vehiculo','grupos','ruta','documento','motoristas'));
    }
    public function updateVehi(Request $request, $id){
        try {
            $request ->validate([
                'numconductor' => 'required',
                'nro_interno' =>[
                    'nullable','string',
                    Rule::unique('vehiculo')->ignore($id, 'idvehiculo'),
            ],
                'documento_propietario' => 'required',
                'id_ruta' => 'required|exists:ruta,id_ruta',
                // 'soat' => 'required',
                // 'revision_tmc' => 'required',
                // 'extra_contra' => 'required',
                // 'tarjeta_operacion' => 'required',
            ]);
            //ACTUALIZAR VEHÍCULO
            $documento = $request->input('numconductor');
            $motorista = Motorista::where('documento', $documento)->first();
            $vehiculo = Vehiculo::findOrFail($id);
            $vehiculo->id_motorista = $motorista->idmotorista;
            $vehiculo->documento_propietario = $request->input('documento_propietario');
            $vehiculo-> id_grupo= $request->input('id_grupo');
            $vehiculo-> id_ruta= $request->input('id_ruta');
            $vehiculo->nro_interno= $request->input('nro_interno');
            $vehiculo->save();
            //ACTUALIZAR DOCUMENTOS
            $documento = Documentos::where('id_vehiculo', $vehiculo->idvehiculo)->first();

            if ($documento) {
                // Si se encontró el documento, actualizarlo
                $documento->soat = $request->input('soat');
                $documento->revision_tmc = $request->input('revision_tmc');
                $documento->extra_contra = $request->input('extra_contra');
                $documento->tarjeta_operacion = $request->input('tarjeta_operacion');
                $documento->km_actual = $request->input('km_actual');
                $documento->save();
            } else {
                $documento = new Documentos();
                $documento->id_vehiculo = $vehiculo->idvehiculo;
                $documento->soat = $request->input('soat');
                $documento->revision_tmc = $request->input('revision_tmc');
                $documento->extra_contra = $request->input('extra_contra');
                $documento->tarjeta_operacion = $request->input('tarjeta_operacion');
                $documento->km_actual = $request->input('km_actual');
                $documento->tarjeta_propiedad = $request->input('tarjeta_propiedad');
                $documento->save();
            }

           return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error al guardar el vehículo: ' . $e->getMessage());
        return response()->json(['exitoso' => false, 'error' => 'Hubo un error al actualizar  el vehiculo '. $e->getMessage()],500);
        }

    }
    public function tableMotorista(Request $request){
        $paginations = $request->input('per_page',25);
        $motorista = Motorista::orderBy('created_at','desc')->paginate($paginations);
        $currentPage = $motorista->currentPage();
        $itemsPage = $motorista->perPage();
        $position = ($currentPage - 1) * $itemsPage +1;

        return view ('tables.tableMotorista',['motorista' => $motorista, 'position' => $position]);
    }
    public function createMotorista(Request $request){
        $existingDocuments = Motorista::where('documento', '=', $request->input('documento'))->first();
         if($existingDocuments){
            // correo ya existe, devuelve una respuesta con mensaje de error
           return response()->json(['documentError' => true ]);
       }
        $motorista = new Motorista();

        $motorista->documento = $request->input('documento');
        $motorista->nombre = $request->input('nombre');
        $motorista->apellido = $request->input('apellido');
        $motorista->save();
        return response()->json(['exitoso' => true]);
    }
    public function editMotorista($id){
        $motorista = Motorista::find($id);
        return response()->json($motorista);

    }
    public function updateMotorista(Request $request,$id){
        $motorista = Motorista::find($id);
        if(!$motorista){
            return-redirect()->back()->whit('error','motorista no en contrado');
        }
        $motorista->nombre = $request->input('nombreUpdate');
        $motorista->apellido = $request->input('apellidoUpdate');
        $motorista->save();
        return response()->json(['success'=>true]);

    }
}


// <?php

// namespace App\Http\Controllers;

// use App\Models\Ruta;
// use App\Models\Vehiculo;
// use App\Models\Documentos;
// use Illuminate\Validation\Rule;
// use Illuminate\Http\Request;
// use App\Models\Grupos;
// use App\Models\Motorista;
// use Illuminate\Support\Facades\Log;
// class VehiculoController extends Controller
// {
//     public function grupo_ruta()
//     {
//         $grupos = Grupos::all();
//         $ruta = Ruta::all();
//         return view('create.createVehi', ['grupos' => $grupos, 'ruta' => $ruta]);
//     }
//     public function busquedaPorDocumento($documento)
//     {
//         $motorista = Motorista::where('documento', $documento)->first();
//         if ($motorista) {
//             return response()->json([
//                 'nombre' => $motorista->nombre,
//                 'apellido' => $motorista->apellido,
//                 'documento' => $documento
//             ]);
//         } else {
//             return response()->json(['error' => 'Documento no encontrado']);
//         }
//     }

//     public function createNewGroup(Request $request)
//     {
//         try {
//             $request->validate([
//                 'desc_grupo' => 'required|string|max:255|unique:grupos,desc_grupo',
//             ]);

//             $nuevoGrupo = new Grupos();
//             $nuevoGrupo->desc_grupo = $request->input('desc_grupo');
//             $nuevoGrupo->save();

//             return response()->json(['exitoso' => true]);
//         } catch (\Throwable $th) {
//             Log::error('Error al crear el grupo: ' . $th->getMessage());
//             return response()->json(['exitoso' => false, 'error' => $th->getMessage()], 500);
//         }
//     }

//     public function createVehi(Request $request)
//     {
//         $request->validate([
//             'placa' => [
//                 'required','string',
//             Rule::unique('vehiculo')->where(function($query) use($request){
//                 return $query->where('placa', $request->placa);
//             })
//             ],

//             'id_ruta' => 'required|exists:ruta,id_ruta',
//             'nro_interno' =>[
//                 'required','string',
//                 Rule::unique('vehiculo')->where(function($query) use($request){
//                     return $query->where('nro_interno', $request->nro_interno);
//                 })
//         ],
//             'documento_propietario' => 'required',
//         ]);
//        try {
//         $fecha = now()->format('Y-m-d A');
//         $documento = $request->input('documento');
//         $motorista = Motorista::where('documento', $documento)->first();
//            // GUARDAR VEHICULO
//         $vehiculo = new Vehiculo();
//         $vehiculo->id_motorista = $motorista->idmotorista;
//         $vehiculo->documento_propietario = $request->input('documento_propietario');
//         $vehiculo->id_grupo = $request->input('id_grupo');
//         $vehiculo->nro_interno = $request->input('nro_interno');
//         $vehiculo->placa = $request->input('placa');
//         $vehiculo->id_ruta = $request->input('id_ruta');
//         $vehiculo->user_created_at = $request->input('id_usuario');
//         $vehiculo->save();
//            //GUARDAR DOCUMENTOS
//         $documento = new Documentos();
//         $documento->id_vehiculo = $vehiculo->idvehiculo;
//         $documento->soat = $request->input('soat');
//         $documento->revision_tmc = $request->input('revision_tmc');
//         $documento->extra_contra = $request->input('extra_contra');
//         $documento->tarjeta_operacion = $request->input('tarjeta_operacion');
//         $documento->km_actual = $request->input('km_actual');
//         $documento->tarjeta_propiedad = $request->input('tarjeta_propiedad');
//         $documento->fecha = $fecha;
//         $documento->save();
//         return response()->json(['exitoso' => true]);

//        } catch (\Exception $e) {
//         Log::error('Error al guardar el vehículo: ' . $e->getMessage());
//         return response()->json(['exitoso' => false, 'error' => 'Hubo un error al registrar el vehiculo'. $e->getMessage()],500);
//     }

//     }
//     public function tableVehi(Request $request){
//         $paginations= $request->input('per_page',25);
//             // $vehiculo = Vehiculo::orderBy('created_at','desc')->paginate($paginations);
//              $vehiculo = Vehiculo::orderByRaw('CASE WHEN updated_at IS NOT NULL THEN updated_at ELSE created_at END DESC')
//                          ->paginate($paginations);
//             $currentPage = $vehiculo->currentPage();
//             $itemsPage = $vehiculo->perPage();
//             $Startposition =($currentPage -1)*$itemsPage +1;
//             foreach ($vehiculo as $vehiculos) {
//                $ruta= Ruta::find($vehiculos->id_ruta);
//                $motorista= Motorista::find($vehiculos->id_motorista);
//                $vehiculos->nombreRuta=$ruta ? $ruta->descripcion:'Ruta no encontrada';
//                $vehiculos->nombre_motorista =$motorista ? $motorista->nombre:'Nombre no encontrado';
//                $vehiculos->documento =$motorista ? $motorista->documento:'documento no encontrado';
//                $vehiculos->position = $Startposition;
//                $Startposition++;
//             }
//             return view("tables.tableVehi",['vehiculo'=>$vehiculo]);

//     }



//     public function editVehi($id){
//         $vehiculo = Vehiculo::find($id);
//         $grupos = Grupos::all();
//         $ruta = Ruta::all();
//         $documento= Documentos::where('id_vehiculo',$id)->first();

//             $motorista = Motorista::find($vehiculo->id_motorista);
//             $motoristas = $motorista ? ['nombre' => $motorista->nombre, 'apellido' => $motorista->apellido,'documento' => $motorista->documento] : null;
//             return view('update.updateVehi',compact('vehiculo','grupos','ruta','documento','motoristas'));

//     }
//     public function updateVehi(Request $request, $id){
//         try {
//             $request ->validate([
//                 'numconductor' => 'required',
//                 'nro_interno' =>[
//                     'nullable','string',
//                     Rule::unique('vehiculo')->ignore($id, 'idvehiculo'),
//             ],
//                 'documento_propietario' => 'required',
//                 'id_ruta' => 'required|exists:ruta,id_ruta',
//             ]);
//             //ACTUALIZAR VEHÍCULO
//             $documento = $request->input('numconductor');
//             $motorista = Motorista::where('documento', $documento)->first();
//             $vehiculo = Vehiculo::findOrFail($id);
//             $vehiculo->id_motorista = $motorista->idmotorista;
//             $vehiculo->documento_propietario = $request->input('documento_propietario');
//             $vehiculo-> id_grupo= $request->input('id_grupo');
//             $vehiculo-> id_ruta= $request->input('id_ruta');
//             $vehiculo->nro_interno= $request->input('nro_interno');
//             $vehiculo->user_updated_at = $request->input('id_usuario');
//             $vehiculo->save();
//             //ACTUALIZAR DOCUMENTOS
//             $documento = Documentos::where('id_vehiculo', $vehiculo->idvehiculo)->first();

//             if ($documento) {
//                 // Si se encontró el documento, actualizarlo
//                 $documento->soat = $request->input('soat');
//                 $documento->revision_tmc = $request->input('revision_tmc');
//                 $documento->extra_contra = $request->input('extra_contra');
//                 $documento->tarjeta_operacion = $request->input('tarjeta_operacion');
//                 $documento->km_actual = $request->input('km_actual');
//                 $documento->save();
//             } else {
//                 $documento = new Documentos();
//                 $documento->id_vehiculo = $vehiculo->idvehiculo;
//                 $documento->soat = $request->input('soat');
//                 $documento->revision_tmc = $request->input('revision_tmc');
//                 $documento->extra_contra = $request->input('extra_contra');
//                 $documento->tarjeta_operacion = $request->input('tarjeta_operacion');
//                 $documento->km_actual = $request->input('km_actual');
//                 $documento->tarjeta_propiedad = $request->input('tarjeta_propiedad');
//                 $documento->save();
//             }

//            return response()->json(['success' => true]);
//         } catch (\Exception $e) {
//             Log::error('Error al guardar el vehículo: ' . $e->getMessage());
//         return response()->json(['exitoso' => false, 'error' => 'Hubo un error al actualizar  el vehiculo '. $e->getMessage()],500);
//         }

//     }
//     public function tableMotorista(Request $request){
//         $paginations = $request->input('per_page',25);
//         $motorista = Motorista::orderBy('created_at','desc')->paginate($paginations);
//         $currentPage = $motorista->currentPage();
//         $itemsPage = $motorista->perPage();
//         $position = ($currentPage - 1) * $itemsPage +1;

//         return view ('tables.tableMotorista',['motorista' => $motorista, 'position' => $position]);
//     }
//     public function createMotorista(Request $request){
//         $existingDocuments = Motorista::where('documento', '=', $request->input('documento'))->first();
//          if($existingDocuments){
//             // correo ya existe, devuelve una respuesta con mensaje de error
//            return response()->json(['documentError' => true ]);
//        }
//         $motorista = new Motorista();
//         $motorista->user_created_at = $request->input('id_usuario');
//         $motorista->documento = $request->input('documento');
//         $motorista->nombre = $request->input('nombre');
//         $motorista->apellido = $request->input('apellido');
//         $motorista->save();
//         return response()->json(['exitoso' => true]);
//     }
//     public function editMotorista($id){
//         $motorista = Motorista::find($id);
//         return response()->json($motorista);

//     }
//     public function updateMotorista(Request $request,$id){
//         $motorista = Motorista::find($id);
//         if(!$motorista){
//             return-redirect()->back()->whit('error','motorista no en contrado');
//         }
//         $motorista->nombre = $request->input('nombreUpdate');
//         $motorista->user_updated_at = $request->input('id_usuario');
//         $motorista->apellido = $request->input('apellidoUpdate');
//         $motorista->save();
//         return response()->json(['success'=>true]);

//     }
// }
