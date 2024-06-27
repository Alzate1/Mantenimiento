<?php

namespace App\Http\Controllers;


use App\Models\Vehiculo;
use App\Models\Ruta;
use App\Models\Cabina;
use App\Models\Caja;
use App\Models\Carroceria;
use App\Models\Emisiones;
use App\Models\EquipoCarretera;
use App\Models\Frenos;
use App\Models\Luces;
use App\Models\Motor;
use App\Models\Motorista;
use App\Models\Observaciones;
use App\Models\Revision;
use App\Models\RevisionCorrectiva;
use App\Models\Suspension;
use App\Models\Transmision;
use App\Models\Anexos;
use App\Models\Users;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;

class MantenimientoController extends Controller
{
    public function users()
    {

        $user = Users::whereIn("idtipo_usuario", [1, 2, 3])->where('estado', 1)->get();
        return view('create.createMant', ['user' => $user]);

    }

    public function busquedaPorInterno($nroInterno)
    {
        $vehiculo = Vehiculo::where('nro_interno', $nroInterno)->with('motorista', 'documento')->first();
        if ($vehiculo) {
            $ruta = Ruta::where('id_ruta', $vehiculo->id_ruta)->value('descripcion');
            $vehiculo->load('motorista', 'documento');
            $idvehiculo = $vehiculo->idvehiculo;
            $placa = $vehiculo->placa;
            $motorista = $vehiculo->motorista;
            $documentVehi = $vehiculo->documento;
            $propietario = $vehiculo->documento_propietario;

            $response = [
                'idVehi' => $idvehiculo,
                'placa' => $placa,
                'propietario' => $propietario,
                'ruta' => $ruta,
            ];

            if ($motorista) {
                $response += [
                    'nombre' => $motorista->nombre,
                    'apellido' => $motorista->apellido,
                    'documento' => $motorista->documento,
                ];
            }

            if ($documentVehi) {
                $km_Act = "No tiene";
                if ($documentVehi->km_actual) {
                    $km_Act = $documentVehi->km_actual;
                }
                $response += [
                    'soat' => $documentVehi->soat,
                    'revision_tmc' => $documentVehi->revision_tmc,
                    'extra_contra' => $documentVehi->extra_contra,
                    'tarjeta_operacion' => $documentVehi->tarjeta_operacion,
                    'km_actual' => $km_Act
                ];
            }
            if($documentVehi){
                $vence = [];

    // Verifica el vencimiento de cada tipo de documento y calcula los días restantes
            if ($documentVehi->soat) {
                 $fechaVence= Carbon::createFromFormat('Y-m-d', $documentVehi->soat)->startOfDay();
                if($fechaVence->isPast()){
                    $vence['soat'] ="Vencido";
                }else{
                    $vence['soat'] =$fechaVence->diffInDays(now());
                }

            }
            if ($documentVehi->revision_tmc) {
                 $fechaVence= Carbon::createFromFormat('Y-m-d', $documentVehi->revision_tmc)->startOfDay();
                if($fechaVence->isPast()){
                    $vence['revision_tmc'] ="Vencido";
                }else{
                    $vence['revision_tmc'] =$fechaVence->diffInDays(now());
                }
            }
            if ($documentVehi->extra_contra) {
                $fechaVence = Carbon::createFromFormat('Y-m-d', $documentVehi->extra_contra)->startOfDay();
                if($fechaVence->isPast()){
                    $vence['extra_contra']="Vencido";

                }else{
                    $vence['extra_contra'] =$fechaVence->diffInDays(now());
                }
            }
            if ($documentVehi->tarjeta_operacion) {
                 $fechaVence= Carbon::createFromFormat('Y-m-d', $documentVehi->tarjeta_operacion)->startOfDay();
                if($fechaVence->isPast()){
                    $vence['tarjeta_operacion'] = "Vencido";
                }else{
                    $vence['tarjeta_operacion'] = $fechaVence->diffInDays(now());
                }
            }
            $response['vence'] = $vence;
            }else{
                $response['vence'] = [];
            }

            // Responde con todos los datos acumulados
            return response()->json($response);
        } else {
            return response()->json(['error' => 'Numero de Interno no encontrado']);
        }
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
    public function create(Request $request)
    {
        try {
            $nroInterno = $request->input('nro_interno');
            $documento = $request->input('documento');
            $vehiculo = Vehiculo::where('nro_interno', $nroInterno)->first();
            $hora = now()->format('h:i:s A');
            $motorista = Motorista::where('documento', $documento)->first();

            $revision = new Revision();
            $revision->id_vehiculo = $vehiculo->idvehiculo;
            $revision->id_motorista = $motorista->idmotorista;
            $revision->fecha = $request->input('fecha');
            $revision->hora = $hora;
            $revision->id_usuario = $request->input('id_usuario');
            $revision->estado = 2;
            $revision->save();

            $cabina = new Cabina();
            $cabina->id_revision = $revision->idrevision;
            $cabina->tacometro = $request->input('tacometro');
            $cabina->luces_interiores = $request->input('luces_interiores');
            $cabina->luz_techo = $request->input('luz_techo');
            $cabina->luz_tablas = $request->input('luz_tablas');
            $cabina->anclaje_sillas = $request->input('anclaje_sillas');
            $cabina->silleteria_cojineria = $request->input('silleteria_cojineria');
            $cabina->cinturones_seguridad = $request->input('cinturones_seguridad');
            $cabina->timbre = $request->input('timbre');
            $cabina->estado_pisos = $request->input('estado_pisos');
            $cabina->dispositivo_velocidad = $request->input('dispositivo_velocidad');
            $cabina->save();



            $motor = new Motor();
            $motor->id_revision = $revision->idrevision;
            $motor->cableado_electrico = $request->input('cableado_electrico');
            $motor->fuga_aceite_motor = $request->input('fuga_aceite_motor');
            $motor->soporte_bateria = $request->input('soporte_bateria');
            $motor->fuga_refrigerante = $request->input('fuga_refrigerante');
            $motor->fuga_combustible = $request->input('fuga_combustible');
            $motor->bomba_inyeccion = $request->input('bomba_inyeccion');
            $motor->save();

            $caja = new Caja();
            $caja->id_revision = $revision->idrevision;
            $caja->funcionamiento_embrague = $request->input('funcionamiento_embrague');
            $caja->soportes_caja = $request->input('soportes_caja');
            $caja->fugas_aceite = $request->input('fugas_aceite');
            $caja->juego_mandos = $request->input('juego_mandos');
            $caja->nivel_aceite = $request->input('nivel_aceite');
            $caja->save();

            $transmision = new Transmision();
            $transmision->id_revision = $revision->idrevision;
            $transmision->ajuste = $request->input('ajuste');
            $transmision->juego_excesivo_cardan = $request->input('juego_excesivo_cardan');
            $transmision->cadena_cardan = $request->input('cadena_cardan');
            $transmision->fuga_aceite = $request->input('fuga_aceite');
            $transmision->save();

            $suspension = new Suspension();
            $suspension->id_revision = $revision->idrevision;
            $suspension->fija_elem_suspension = $request->input('fija_elem_suspension');
            $suspension->llanta_labrado = $request->input('llanta_labrado');
            $suspension->amortiguador_exis_fuga = $request->input('amortiguador_exis_fuga');
            $suspension->tijeras = $request->input('tijeras');
            $suspension->brazo_axial = $request->input('brazo_axial');
            $suspension->terminales_direccion = $request->input('terminales_direccion');
            $suspension->rotulas = $request->input('rotulas');
            $suspension->ballestas_resortes = $request->input('ballestas_resortes');
            $suspension->save();

            $carroceria = new Carroceria();
            $carroceria->id_revision = $revision->idrevision;
            $carroceria->colores_avisos = $request->input('colores_avisos');
            $carroceria->distintivos_emblemas = $request->input('distintivos_emblemas');
            $carroceria->placa_lateral_reflectivos = $request->input('placa_lateral_reflectivos');
            $carroceria->latoneria_pintura = $request->input('latoneria_pintura');
            $carroceria->bomperes = $request->input('bomperes');
            $carroceria->pisos_estribos = $request->input('pisos_estribos');
            $carroceria->mecanismos_emergencia = $request->input('mecanismos_emergencia');
            $carroceria->save();
            $frenos = new Frenos();
            $frenos->id_revision = $revision->idrevision;
            $frenos->fugas = $request->input('fugas');
            $frenos->ductos_manguera_frenos = $request->input('ductos_manguera_frenos');
            $frenos->nivel_liquido = $request->input('nivel_liquido');
            $frenos->save();
            $emisiones = new Emisiones();
            $emisiones->id_revision = $revision->idrevision;
            $emisiones->sistema_escape_fugas = $request->input('escape_fugas');
            $emisiones->conexion_valvula_pcv = $request->input('conexion_valvula');
            $emisiones->emision_humo_azul_negro = $request->input('emision_humo');
            $emisiones->tapa_aceite_combustible = $request->input('tapa_aceite');
            $emisiones->save();

            $luces = new Luces();
            $luces->id_revision = $revision->idrevision;
            $luces->luces_bajas = $request->input('luces_bajas');
            $luces->luces_altas = $request->input('luces_altas');
            $luces->cocuyos = $request->input('cocuyos');
            $luces->direccionales = $request->input('direccionales');
            $luces->luz_freno = $request->input('luz_freno');
            $luces->luz_reversa = $request->input('luz_reversa');
            $luces->alarma_reversa = $request->input('alarma_reversa');
            $luces->luces_parqueo = $request->input('luces_parqueo');
            $luces->pito = $request->input('pito');
            $luces->espejos = $request->input('espejos');
            $luces->save();

            $equipo = new EquipoCarretera();
            $equipo->id_revision = $revision->idrevision;
            $equipo->extintor_vencimiento = $request->input('extintor');
            $equipo->botiquin = $request->input('botiquin');
            $equipo->cruceta = $request->input('cruceta');
            $equipo->tacos = $request->input('tacos');
            $equipo->repuesto = $request->input('repuesto');
            $equipo->gato = $request->input('gato');
            $equipo->save();

            $observacion = new Observaciones();
            $observacion->id_revision = $revision->idrevision;
            $observacion->observacion = $request->input('observacion');
            $observacion->save();



            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function tableRev($idRevision)
    {
        $revCorrectiva = RevisionCorrectiva::orderBy('created_at','desc')->where('id_revision', $idRevision)->get();
        $revCorrectivaArray = $revCorrectiva->toArray();
            foreach ($revCorrectivaArray as &$rev) {
                $rev['estado_dos'] = $rev['estado'] == 2;
            }

        return response()->json(['revCorrectiva' => $revCorrectiva]);
    }

    public function createRevision(Request $request)
    {
        try {
            $id_revision =$request->input('id_revision');
            $revCorrectiva = new RevisionCorrectiva();
            $revCorrectiva->id_revision = $id_revision;
            $revCorrectiva->fecha = $request->input('fecha');
            $revCorrectiva->centro_especializado = $request->input('centroRev');
            $revCorrectiva->detalle_mantenimiento = $request->input('detailsRev');
            $revCorrectiva->estado =2;
            $revCorrectiva->save();
            // CAMBIA EL ESTADO DE LA REVISIÓN A 1=REALIZADO



            $revCorrectiva = RevisionCorrectiva::where('id_revision', $request->input('id_revision'))->get();
            return response()->json(['success' => true, 'revCorrectiva' => $revCorrectiva]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


    }
    public function tableAnexo($idRevision)
    {
        $anexo = Anexos::orderBy('created_at','desc')->where('id_correccion', $idRevision)->get();
        return response()->json(['anexo' => $anexo]);
    }
    public function createAnexo(Request $request)
    {
        try {
            $request->validate([
                'anexos' => 'required|array',
                'anexos.*' => 'mimes:pdf,docx,jpg,jpeg,png|max:2048'
            ]);

            $idcorrectiva = $request->input('idrev');
            $revisionCorrec = RevisionCorrectiva::find($idcorrectiva);
            foreach ($request->file('anexos') as $archivos) {
                $idcorrectiva = $revisionCorrec->idcorreccion;
                $ruta = $archivos->store('anexos', 'public');
                $anexo = new Anexos();
                $anexo->id_correccion = $idcorrectiva;
                $anexo->nombre = $archivos->getClientOriginalName();
                $anexo->ruta = $ruta;
                $anexo->fecha = now();
                $anexo->save();
            }

            $revisionCorrec->estado =1;
            $revisionCorrec->save();
            $revision = $revisionCorrec->revision;
            $revision->estado =1;
            $revision->save();
            $anexo = Anexos::where('id_correccion', $request->input('idrev'))->get();
            return response()->json(['success' => true, 'anexo' => $anexo]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function deleteAnexo($idAnexo)
    {
        try {
            $anexo = Anexos::find($idAnexo);
            if (!$anexo) {
                return response()->json(['error' => 'Anexo no encontrado'], 404);
            }
            $path = 'public/' . $anexo->ruta;
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
            $anexo->delete();
            $idRev_correccion = $anexo->id_correccion;
            $stockAnex = Anexos::where('id_correccion', $idRev_correccion)->exists();
            if (!$stockAnex){
                $revisionCorrec = RevisionCorrectiva::find($idRev_correccion);
                $revisionCorrec->estado = 2;
                $revisionCorrec->save();
                $revision = $revisionCorrec->revision;
                $revision->estado =2;
                $revision->save();
            }


            return response()->json(['success' => 'Anexo eliminado']);
        } catch (\Exception $e) {
            DB::rollBack();
        }

    }

    public function deleteRevision($id){
        try {
            $revision = Revision::find($id);
            if ($revision->revCorrectiva) {
                $anexos = $revision->revCorrectiva->anexos()->get();
                foreach ($anexos as $anexo) {
                    $ruta = public_path('storage/' . $anexo->ruta);
                    if (file_exists($ruta)) {
                        unlink($ruta);
                    }
                }
                $revision->revCorrectiva->anexos()->delete();
            }

            // Luego, eliminar la fila en la tabla "revision_correctiva"
            if ($revision->revCorrectiva) {
                $revision->revCorrectiva()->delete();
            }
            $revision->cabina()->delete();
            $revision->motor()->delete();
            $revision->caja()->delete();
            $revision->transmision()->delete();
            $revision->suspension()->delete();
            $revision->carroceria()->delete();
            $revision->frenos()->delete();
            $revision->emisiones()->delete();
            $revision->luces()->delete();
            $revision->equipoCarr()->delete();
            $revision->observacion()->delete();
            $revision->delete();
            return response()->json(['success' => true]);
        }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function tableMant(Request $request)
    {
        $pagination = $request->input('per_page',25);
        $revisiones = Revision::orderBy('created_at','desc')->paginate($pagination);

        $revisionInfo = [];
        $currentPage = $revisiones->currentPage();
        $itemsPage = $revisiones->perPage();
        $startPosition = ($currentPage - 1) * $itemsPage +1;
        // $revisiones = Revision::whereHas('usuario',function($query){
        //     $query->where('estado',1);
        // })->get();
        foreach ($revisiones as $revision) {
            $idVehiculo = $revision->id_vehiculo;
            $vehiculo = Vehiculo::find($idVehiculo);
            $ruta = Ruta::find($vehiculo->id_ruta);
            $motorista = $revision->motorista;
            $infoRevision = new stdClass();
            $infoRevision->idrevision = $revision->idrevision;
            $infoRevision->nombreRuta = $ruta ? $ruta->descripcion : 'Ruta no encontrada';
            $infoRevision->placa = $vehiculo->placa;
            $infoRevision->nro_interno = $vehiculo->nro_interno;

            $infoRevision->id_vehiculo = $vehiculo->idvehiculo;
            $infoRevision->fecha = $revision->fecha;
            $infoRevision->hora = $revision->hora;
            $infoRevision->estado = $revision->estado;
            $infoRevision->numconductor = $motorista->documento;
            $infoRevision->position = $startPosition;
            $revisionInfo[] = $infoRevision;
            $startPosition ++;
        }

        return view('tables.tableMantenimiento', ['revision' => $revisionInfo,'revisiones'=>$revisiones]);
    }
    public function pdfDetalle($id, $idvehiculo)
    {
        try {
            $revision = Revision::with([
                'cabina',
                'caja',
                'carroceria',
                'emisiones',
                'equipoCarr',
                'frenos',
                'luces',
                'motor',
                'observacion',
                'suspension',
                'transmision',
                'vehiculo',
                'usuario',
                'motorista',
                'revCorrectiva',

            ])->find($id);
            $vehiculo = Vehiculo::with([
                'documento',

            ])->find($idvehiculo);

            if (!$revision) {
                return response()->json(['error' => 'Revision inexistente '], 404);
            }
            $pdf = new Fpdf();
            $pdf->AddPage('L', 'A4');

            $pdf->SetY(25);
            $pdf->SetFont('Arial', '', 25);
            $pdf->SetTextColor(63, 126, 168);
            $pdf->Cell(0, 10, utf8_decode('Mantenimiento Preventivo y Correctivo'), 0, 1, 'L');
            $pdf->SetX(30);
            $pdf->SetFont('Arial', '', 10); // Tamaño 10
            $pdf->SetTextColor(128, 127, 127);
            $pdf->Cell(0, 5, utf8_decode('Resolución 0315 de 2013'), 0, 1, 'L');
            $pdf->SetY(25);
            $pdf->SetX(80);
            $pdf->Image('img/img.png', 200, 25, 50, 20, 'PNG');
            $pdf->Cell(200, 6, '', 0, 1, 'R');
            $pdf->Cell(217, 4, '', 0, 1, 'R');
            $pdf->Cell(257, 4, '', 0, 1, 'R'); //0
            $pdf->Cell(239, 4, '', 0, 0, 'R');

            // $pdf->Cell(200, 6, 'Empresa de Transportes Sultana del Valle S.A.S', 0, 1, 'R');
            // $pdf->Cell(217, 4, '890.301.296-4', 0, 1, 'R');
            // $pdf->Cell(257, 4, 'Terminal de Transporte Piso 2 Of. 201-1', 0, 1, 'R'); //0
            // $pdf->Cell(239, 4, '(2) 667 54 65 - (2) 653 51 41', 0, 0, 'R');

            $pdf->Ln(10);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(20, $pdf->GetY(), 280, $pdf->GetY());


            $pdf->Ln(5);
            $pdf->SetY(54);
            $pdf->SetX(50);
            $pdf->SetFont('Arial', 'B', 15); // Tamaño 15
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('PLACA'), 0, 1, 'L');
            $pdf->SetX(52);
            $pdf->SetFont('Arial', '', 13);
            $pdf->SetTextColor(128, 127, 127);
            $pdf->Cell(0, 5, strtoupper($revision->vehiculo->placa), 0, 0, 'L');

            $pdf->SetY(54);
            $pdf->SetX(80);
            $pdf->SetFont('Arial', 'B', 15); // Tamaño 15
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('NÚMERO INTERNO'), 0, 1, 'L');
            $pdf->SetX(97);
            $pdf->SetFont('Arial', '', 13);
            $pdf->SetTextColor(128, 127, 127);
            $pdf->Cell(0, 5, $revision->vehiculo->nro_interno, 0, 0, 'L');

            $pdf->SetY(54);
            $pdf->SetX(140);
            $pdf->SetFont('Arial', 'B', 15); // Tamaño 15
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('CONDUCTOR'), 0, 1, 'L');
            $pdf->SetX(135);
            $pdf->SetFont('Arial', '', 13);
            $pdf->SetTextColor(128, 127, 127);
            $nombre = strtoupper($revision->motorista->nombre . ' ' . $revision->motorista->apellido);

            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(60, 5, utf8_decode($nombre), 0, 0, 'L');

            $pdf->SetY(54);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', 'B', 15); // Tamaño 15
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('FECHA'), 0, 1, 'L');
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13);
            $pdf->SetTextColor(128, 127, 127);
            $pdf->Cell(0, 5, $revision->fecha, 0, 0, 'L');

            $pdf->Ln(10);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(20, $pdf->GetY(), 280, $pdf->GetY());
            $pdf->Ln(5);

            $pdf->SetY(73);
            $pdf->SetX(30);
            $pdf->SetFont('Arial', 'B', 11); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Resultado de la verificación:'), 0, 1, 'L');

            // Sección con fondo de color
            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8, 'DOCUMENTOS', 1, 1, 'C', true);
            $pdf->Ln(10);

            $pdf->SetY(95);
            $pdf->SetX(45);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Vencimiento SOAT:'), 0, 1, 'L');

            $pdf->SetY(95);
            $pdf->SetX(95);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode($vehiculo->documento->soat), 0, 1, 'L');

            $pdf->SetY(95);
            $pdf->SetX(135);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Vencimiento Revision TCM:'), 0, 1, 'L');

            $pdf->SetY(95);
            $pdf->SetX(205);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode($vehiculo->documento->revision_tmc), 0, 1, 'L');


            $pdf->Ln(4);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
            $pdf->Ln(5);

            $pdf->SetY(110);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Vencimiento EXTRA/CONTRA:'), 0, 1, 'L');

            $pdf->SetY(110);
            $pdf->SetX(105);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode($vehiculo->documento->extra_contra), 0, 1, 'L');

            $pdf->SetY(110);
            $pdf->SetX(140);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Vencimiento TARJETA OPERACIÓN:'), 0, 1, 'L');

            $pdf->SetY(110);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode($vehiculo->documento->tarjeta_operacion), 0, 1, 'L');

            $pdf->SetY(120);
            $pdf->SetX(90);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('KILOMETRAJE ACTUAL: '), 0, 1, 'L');

            $pdf->SetY(120);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $mensaje = strtoupper('No tiene Kilometraje');
            if ($vehiculo->documento->km_actual) {
                $mensaje = strtoupper($vehiculo->documento->km_actual);
            }
            $pdf->Cell(0, 8, utf8_decode($mensaje), 0, 1, 'L');


            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8, 'ESTADO DEL MOTOR ', 1, 1, 'C', true);
            $pdf->Ln(10);



            $pdf->SetY(140);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Estado Cableado Electrico:'), 0, 1, 'L');

            $pdf->SetY(140);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->motor && $revision->motor->cableado_electrico == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(140);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Fugas de Aceite Motor:'), 0, 1, 'L');

            $pdf->SetY(140);
            $pdf->SetX(210);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->motor && $revision->motor->fuga_aceite_motor == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->Ln(4);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
            $pdf->Ln(5);

            $pdf->SetY(155);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Sopote de Bateria:'), 0, 1, 'L');

            $pdf->SetY(155);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->motor && $revision->motor->soporte_bateria == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(155);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Fugas Refrigerante:'), 0, 1, 'L');

            $pdf->SetY(155);
            $pdf->SetX(210);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->motor && $revision->motor->fuga_refrigerante == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(4);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
            $pdf->Ln(5);

            $pdf->SetY(170);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Fugas Combustible :'), 0, 1, 'L');

            $pdf->SetY(170);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->motor && $revision->motor->fuga_combustible == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->SetY(170);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Estado Bomba Inyección:'), 0, 1, 'L');

            $pdf->SetY(170);
            $pdf->SetX(210);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->motor && $revision->motor->bomba_inyeccion == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->AddPage('L', 'A4');

            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8, 'CAJA DE VELOCIDADES:', 1, 1, 'C', true);
            $pdf->Ln(10);
            $pdf->SetY(20);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Funcionamiento Embrague :'), 0, 1, 'L');

            $pdf->SetY(20);
            $pdf->SetX(105);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->caja && $revision->caja->funcionamiento_embrague	 == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(20);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode(' Soportes de Caja:'), 0, 1, 'L');

            $pdf->SetY(20);
            $pdf->SetX(205);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->caja && $revision->caja->soportes_caja == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->Ln(4);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
            $pdf->Ln(5);

            $pdf->SetY(35);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Fugas de Aceite  :'), 0, 1, 'L');

            $pdf->SetY(35);
            $pdf->SetX(80);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->caja && $revision->caja->fugas_aceite == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(35);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode(' Juego de Mandos:'), 0, 1, 'L');

            $pdf->SetY(35);
            $pdf->SetX(145);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->caja && $revision->caja->juego_mandos == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->SetY(35);
            $pdf->SetX(175);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode(' Nivel de Aceite:'), 0, 1, 'L');

            $pdf->SetY(35);
            $pdf->SetX(215);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->caja && $revision->caja->nivel_aceite == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8,utf8_decode( 'TRANSMISIÓN: '), 1, 1, 'C', true);
            $pdf->Ln(10);

            $pdf->SetY(55);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Cadena Cardan :'), 0, 1, 'L');

            $pdf->SetY(55);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->transmision && $revision->transmision->cadena_cardan == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(55);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Juego Excesivos de Cardan:'), 0, 1, 'L');

            $pdf->SetY(55);
            $pdf->SetX(230);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->transmision && $revision->transmision->juego_excesivo_cardan== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(4);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
            $pdf->Ln(5);

            $pdf->SetY(70);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Ajuste :'), 0, 1, 'L');

            $pdf->SetY(70);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->transmision && $revision->transmision->ajuste == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(70);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode(' Fugas de Aceite:'), 0, 1, 'L');

            $pdf->SetY(70);
            $pdf->SetX(230);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->transmision && $revision->transmision->fuga_aceite== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8,utf8_decode( 'SUSPENSIÓN:'), 1, 1, 'C', true);
            $pdf->Ln(10);

            $pdf->SetY(90);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Tijeras:'), 0, 1, 'L');

            $pdf->SetY(90);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->suspension && $revision->suspension->tijeras== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->SetY(90);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Fijación Elementos Suspensión :'), 0, 1, 'L');

            $pdf->SetY(90);
            $pdf->SetX(230);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->suspension && $revision->suspension->fija_elem_suspension == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(4);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
            $pdf->Ln(5);

            $pdf->SetY(105);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Rotulas:'), 0, 1, 'L');

            $pdf->SetY(105);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->suspension && $revision->suspension->rotulas== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(105);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Amortiguador Existencia / Fugas :'), 0, 1, 'L');

            $pdf->SetY(105);
            $pdf->SetX(230);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->suspension && $revision->suspension->amortiguador_exis_fuga == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(4);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
            $pdf->Ln(5);

            $pdf->SetY(120);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Brazo Axial:'), 0, 1, 'L');

            $pdf->SetY(120);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->suspension && $revision->suspension->brazo_axial== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(120);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode(' Terminales de Dirección:'), 0, 1, 'L');

            $pdf->SetY(120);
            $pdf->SetX(230);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->suspension && $revision->suspension->terminales_direccion == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->Ln(4);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->Ln(5);
            $pdf->SetY(135);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Llantas Labrado:'), 0, 1, 'L');

            $pdf->SetY(135);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->suspension && $revision->suspension->llanta_labrado== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(135);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Ballestas / Resortes:'), 0, 1, 'L');

            $pdf->SetY(135);
            $pdf->SetX(230);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->suspension && $revision->suspension->ballestas_resortes == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8,utf8_decode( 'CARROCERIA:'), 1, 1, 'C', true);
            $pdf->Ln(10);

            $pdf->Ln(5);
            $pdf->SetY(155);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Colores y Avisos:'), 0, 1, 'L');

            $pdf->SetY(155);
            $pdf->SetX(80);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->carroceria && $revision->carroceria->colores_avisos== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(155);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Bomperes:'), 0, 1, 'L');

            $pdf->SetY(155);
            $pdf->SetX(130);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->carroceria && $revision->carroceria->bomperes == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(155);
            $pdf->SetX(155);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Distintivos y Emblemas:'), 0, 1, 'L');

            $pdf->SetY(155);
            $pdf->SetX(230);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->carroceria && $revision->carroceria->distintivos_emblemas == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(4);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->Ln(5);
            $pdf->SetY(170);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Placas Laterales y Reflectivos:'), 0, 1, 'L');

            $pdf->SetY(170);
            $pdf->SetX(105);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->carroceria && $revision->carroceria->placa_lateral_reflectivos== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }


            $pdf->SetY(170);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Latonería y Pintura:'), 0, 1, 'L');

            $pdf->SetY(170);
            $pdf->SetX(230);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->carroceria && $revision->carroceria->latoneria_pintura == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());


            $pdf->Ln(5);
            $pdf->SetY(181);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Mecanismos de Emergencia:'), 0, 1, 'L');

            $pdf->SetY(181);
            $pdf->SetX(100);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->carroceria && $revision->carroceria->mecanismos_emergencia== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }


            $pdf->SetY(181);
            $pdf->SetX(150);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Pisos y Estribos:'), 0, 1, 'L');

            $pdf->SetY(181);
            $pdf->SetX(230);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->carroceria && $revision->carroceria->pisos_estribos == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8, 'FRENOS:', 1, 1, 'C', true);
            $pdf->Ln(10);

            $pdf->SetY(20);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Ductos Mangueras de Frenos:'), 0, 1, 'L');

            $pdf->SetY(20);
            $pdf->SetX(105);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->frenos && $revision->frenos->ductos_manguera_frenos	== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->SetY(20);
            $pdf->SetX(130);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Fugas:'), 0, 1, 'L');

            $pdf->SetY(20);
            $pdf->SetX(148);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->frenos && $revision->frenos->fugas == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(20);
            $pdf->SetX(170);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Nivel Líquido :'), 0, 1, 'L');

            $pdf->SetY(20);
            $pdf->SetX(205);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->frenos && $revision->frenos->nivel_liquido == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8, 'EMISIONES CONTAMINANTES:', 1, 1, 'C', true);
            $pdf->Ln(10);

            $pdf->SetY(38);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Estado Sistema de Escape / Fugas:'), 0, 1, 'L');

            $pdf->SetY(38);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->emiciones && $revision->emiciones->sistema_escape_fugas	== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->SetY(38);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Conexión Válvula PCV :'), 0, 1, 'L');

            $pdf->SetY(38);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->emiciones && $revision->emiciones->conexion_valvula_pcv == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->SetY(50);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Emisión Humo Azul o Negro:'), 0, 1, 'L');

            $pdf->SetY(50);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->emiciones && $revision->emiciones->emision_humo_azul_negro	== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(50);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Tapas Aceite y Combustible :'), 0, 1, 'L');

            $pdf->SetY(50);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->emiciones && $revision->emiciones->tapa_aceite_combustible == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8, 'LUCES EXTERIORES:', 1, 1, 'C', true);
            $pdf->Ln(10);

            $pdf->SetY(68);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Luces Bajas:'), 0, 1, 'L');

            $pdf->SetY(68);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->luces && $revision->luces->luces_bajas	== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(68);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Luces Altas  :'), 0, 1, 'L');

            $pdf->SetY(68);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->luces && $revision->luces->luces_altas == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->SetY(80);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Cocuyos :'), 0, 1, 'L');

            $pdf->SetY(80);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->luces && $revision->luces->cocuyos	== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(80);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Direccionales  :'), 0, 1, 'L');

            $pdf->SetY(80);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->luces && $revision->luces->direccionales == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->SetY(95);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Luz Freno  :'), 0, 1, 'L');

            $pdf->SetY(95);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->luces && $revision->luces->luz_freno	== 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(95);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Luz Reversa  :'), 0, 1, 'L');

            $pdf->SetY(95);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->luces && $revision->luces->luz_reversa == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->SetY(110);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Alarma de Reversa :'), 0, 1, 'L');

            $pdf->SetY(110);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->luces && $revision->luces->alarma_reversa == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(110);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Luces de Parqueo  :'), 0, 1, 'L');

            $pdf->SetY(110);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->luces && $revision->luces->luces_parqueo == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->SetY(125);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Pito :'), 0, 1, 'L');

            $pdf->SetY(125);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->luces && $revision->luces->pito == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(125);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Espejos  :'), 0, 1, 'L');

            $pdf->SetY(125);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->luces && $revision->luces->espejos == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8, 'EQUIPO DE CARRETERAS:', 1, 1, 'C', true);
            $pdf->Ln(10);

            $pdf->SetY(145);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Extintor Venicimiento  :'), 0, 1, 'L');

            $pdf->SetY(145);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->equipoCarr && $revision->equipoCarr->extintor_vencimiento == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(145);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Botiquin  :'), 0, 1, 'L');

            $pdf->SetY(145);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->equipoCarr && $revision->equipoCarr->botiquin == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->SetY(160);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Cruceta  :'), 0, 1, 'L');

            $pdf->SetY(160);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->equipoCarr && $revision->equipoCarr->cruceta == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(160);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Tacos  :'), 0, 1, 'L');

            $pdf->SetY(160);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->equipoCarr && $revision->equipoCarr->tacos == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->SetY(175);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Repuesto  :'), 0, 1, 'L');

            $pdf->SetY(175);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->equipoCarr && $revision->equipoCarr->repuesto == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(175);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Gato :'), 0, 1, 'L');

            $pdf->SetY(175);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->equipoCarr && $revision->equipoCarr->gato == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8, 'CABINA:', 1, 1, 'C', true);
            $pdf->Ln(10);

            $pdf->SetY(20);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Tacómetro  :'), 0, 1, 'L');

            $pdf->SetY(20);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->cabina && $revision->cabina->tacometro == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }
            $pdf->SetY(20);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Luces Interiores :'), 0, 1, 'L');

            $pdf->SetY(20);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->cabina && $revision->cabina->luces_interiores == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->SetY(35);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Luz de Techo  :'), 0, 1, 'L');

            $pdf->SetY(35);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->cabina && $revision->cabina->luz_techo == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(35);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Luz de Tablas  :'), 0, 1, 'L');

            $pdf->SetY(35);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->cabina && $revision->cabina->luz_tablas == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->SetY(50);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Anclaje de Sillas  :'), 0, 1, 'L');

            $pdf->SetY(50);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->cabina && $revision->cabina->anclaje_sillas == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(50);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Silletería y Cojinería  :'), 0, 1, 'L');

            $pdf->SetY(50);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->cabina && $revision->cabina->silleteria_cojineria == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->SetY(65);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Cinturones de Seguridad  :'), 0, 1, 'L');

            $pdf->SetY(65);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->cabina && $revision->cabina->cinturones_seguridad == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(65);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Timbre  :'), 0, 1, 'L');

            $pdf->SetY(65);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->cabina && $revision->cabina->timbre == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->Ln(3);
            $pdf->SetDrawColor(128, 127, 127);
            $pdf->SetLineWidth(0.6);
            $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());

            $pdf->SetY(80);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Estado Pisos  :'), 0, 1, 'L');

            $pdf->SetY(80);
            $pdf->SetX(115);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->cabina && $revision->cabina->estado_pisos == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetY(80);
            $pdf->SetX(160);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode('Dispositivo de Velocidad  :'), 0, 1, 'L');

            $pdf->SetY(80);
            $pdf->SetX(225);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($revision->cabina && $revision->cabina->dispositivo_velocidad	 == 1) {
                $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
            }

            $pdf->SetX(30);
            $pdf->SetFillColor(63, 126, 168); // Color de fondo
            $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
            $pdf->Cell(230, 8, 'OBSERVACIONES:', 1, 1, 'C', true);
            $pdf->Ln(10);
            $pdf->SetY(100);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $mensajeOb = strtoupper('Sin observacion');
            if ($revision->observacion) {
                $mensajeOb = strtoupper($revision->observacion->observacion);
            }
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(0, 8, utf8_decode($mensajeOb), 0, 1, 'L');

            $pdf->Ln(10);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode("Nombre del Responsable"), 0, 1, 'L');
            $pdf->Ln(2);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $respon = mb_strtoupper($revision->usuario->nombre_usuario.' '.$revision->usuario->apellido);
            $pdf->Cell(0, 8, utf8_decode($respon ), 0, 1 , 'L');
            $pdf->Ln(2);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode("Firma del Responsable"), 0, 1, 'L');
                $firma=('storage/public/'.$revision->usuario->firma);
                $firma_extension = mime_content_type($firma);
                $firmaY = $pdf->GetY();
                if($firma_extension === 'image/jpeg' || $firma_extension === 'image/jpg'){
                $pdf->Image($firma, 35, $firmaY, 50, 50,'jpeg');
                }elseif($firma_extension === 'image/png'){
                    $pdf->Image($firma, 35, $firmaY, 50, 50,'png');
                }
                else{
                $pdf->Cell(0, 8, utf8_decode("error"), 0, 1, 'L');
                }
                $pdf->Image('img/logo-supertransporte-1.png', 200, 160, 50, 20, 'PNG');

            $response = response($pdf->output('S'), 200);

            $response->header('Content-Type', 'application/pdf');
            $filename = 'mantenimiento_' . strtoupper($revision->vehiculo->placa) . '.pdf';
            $response->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

            return $response;
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
