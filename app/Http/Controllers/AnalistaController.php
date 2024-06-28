<?php

namespace App\Http\Controllers;

use App\Models\Informe;
use App\Models\InformeItem;
use App\Models\Item;
use App\Models\Ruta;
use App\Models\Users;
use App\Models\Vehiculo;
use App\Models\TipoInforme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;
use Illuminate\Support\Facades\DB;

class AnalistaController extends Controller
{
    function craeateReport(Request $request)
    {
        try {
            $user = auth()->user();
            $interno = $request->input('nro_interno');
            $idTipoInfo = $request->input('idTipoInfo');
            $tipoInfo = TipoInforme::find($idTipoInfo);
            $vehiculo = Vehiculo::where("nro_interno", $interno)->first();
            $date = Carbon::now();
            if (!$vehiculo) {
                return response()->json(['notVehicle' => true]);
            }
            $informe = new Informe();
            $informe->id_usuario = $user->idusuario;
            $informe->id_vehiculo = $vehiculo->idvehiculo;
            $informe->descripcion = $request->input('descripcion');
            $informe->id_tipo_informe = $tipoInfo->id;
            $informe->fecha = $request->input('date');
            $informe->estado = 0;
            $informe->save();
            if ($request->has('itemselect')) {
                $items = $request->input('itemselect');
                foreach ($items as $itemId) {
                    DB::table('informe_item')->insert([
                        'id_informe' => $informe->idinforme,
                        'id_item' => $itemId,
                        'fecha' => $date
                    ]);
                }
            } else {
                // Si no se enviaron items, devolver un mensaje de error
                return response()->json(['error' => 'Debe seleccionar al menos un item.'], 422);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    function tableAnalist(Request $request)
    {
        $pagination = $request->input('per_page', 25);

        // Filtra informes por la ruta si el checkbox está marcado
        $rutaCorinto = $request->input('corinto', false);
        $rutaPalmira = $request->input('palmira', false);
        $query = Informe::query();
        $query->whereHas('vehiculo', function ($q) {
            $q->whereIn('id_ruta', [6, 7]);
        });

        if ($rutaCorinto) {
            $query->whereHas('vehiculo', function ($q) {
                $q->where('id_ruta', 7); // Filtra por la ruta con ID 6
            });
        }
        if ($rutaPalmira) {
            $query->whereHas('vehiculo', function ($q) {
                $q->where('id_ruta', 6); // Filtra por la ruta con ID 6
            });
        }
        $informe = $query->orderBy('created_at', 'desc')->paginate($pagination);
        $dateInfo = [];
        $currentPage = $informe->currentPage();
        $itemsPage = $informe->perPage();
        $startPosition = ($currentPage - 1) * $itemsPage + 1;

        foreach ($informe as $date) {
            $idvehiculo = $date->id_vehiculo;
            $iduser = $date->id_usuario;
            $vehiculo = Vehiculo::find($idvehiculo);
            $user = Users::find($iduser);
            $ruta = Ruta::find($vehiculo->id_ruta);
            $infoData = new stdClass();
            $infoData->idinforme = $date->idinforme;
            $infoData->fecha = $date->fecha;
            $infoData->descripcion = $date->descripcion;
            $infoData->nombreRuta = $ruta ? $ruta->descripcion : 'Ruta no encontrada';
            $infoData->usuario = $user->nombre_usuario . " " . $user->apellido;
            $infoData->interno = $vehiculo->nro_interno;
            $infoData->position = $startPosition;
            if ($date->estado == 0) {
                $infoData->style = 'color:red;';
                $infoData->texto = 'Informe Diario: pendiente';

            } else {
                $infoData->style = '';
            }


            $dateInfo[] = $infoData;
            $startPosition++;
        }

        return view('tables.tableInforme', ['informe' => $informe, 'date' => $dateInfo, 'rutaCorinto' => $rutaCorinto, 'rutaPalmira' => $rutaPalmira]);
    }

    function viewDesc(Request $request, $id)
    {
        $informe = Informe::find($id);
        if ($informe) {
            $items = InformeItem::where('id_informe', $id)->get();
            $itemsDetails = $items->map(function ($item) {
                $itemDetail = Item::find($item->id_item);
                return $itemDetail ? $itemDetail->nombre : 'Nombre no encontrado';
            });

            return response()->json([
                'success' => true,
                'desc' => $informe->descripcion,
                'estado' => $informe->estado,
                'items' => $itemsDetails
            ]);
        } else {
            return response()->json(['success' => false, 'error' => 'Not Found']);
        }
    }
    public function NewState(Request $request, $id)
    {
        try {
            $items = Informe::where('idinforme', $id)->first();
            if ($items) {
                $newState = $request->input('estado');
                $items->estado = $newState;
                $items->save();

                return response()->json(['success' => true, 'estado' => $items->estado]);
            } else {
                return response()->json(['success' => false, 'message' => 'InformeItem no encontrado']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    function deleteInforme($id)
    {
        DB::beginTransaction();

        try {
            $informe = Informe::find($id);
            if (!$informe) {
                return response()->json(['error' => 'Informe no encontrado'], 404);
            }

            // Eliminar los elementos relacionados en la tabla informe_item
            $informe->items()->delete();

            // Eliminar el informe
            $informe->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    function createItems(Request $request)
    {
        try {
            $request->validate(
                [
                    'newitem' => 'required|string|max:255|unique:item,nombre',
                ],
                [
                    'item.unique' => 'El nombre del item ya existe.',
                    'item.required' => 'El nombre del item es obligatorio.',
                    'item.max' => 'El nombre del item no puede exceder 255 caracteres.',
                ]
            );

            $items = new Item();
            $items->nombre = $request->input('newitem');
            $items->descripcion = $request->input('desc_item');
            $items->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    function viewItems()
    {
        try {
            $items = Item::orderBy('created_at', 'desc')->get();
            $tipoInforme = TipoInforme::All();
            return view('create.createInforme', ['items' => $items, 'tipoInforme' => $tipoInforme]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    function editInforme($id)
    {
        try {
            $informe = Informe::find($id);
            if (!$informe) {
                return redirect()->route('home');
            }
            $allItems = Item::all();
            $allTipoInforme = TipoInforme::all();
            $vehiculo = Vehiculo::find($informe->id_vehiculo);
            $tipoInforme = TipoInforme::find($informe->id_tipo_informe);
            $relatedItems = InformeItem::where('id_informe',$id)->pluck('id_item')->toArray();

            return view('update.updateInforme', [
                'informe' => $informe,
                'allItems' => $allItems,
                'allTipoInforme' => $allTipoInforme,
                'vehiculo' => $vehiculo,
                'tipoInforme' => $tipoInforme,
                'relatedItems'=>$relatedItems,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function updateInforme(Request $request){
        
    }
}
