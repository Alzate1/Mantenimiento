<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Vehiculo;
use App\Models\Alistamiento;
use App\Models\Motorista;
use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;

class AlistamientoController extends Controller
{
    public function users()
    {

        $user = Users::whereIn("idtipo_usuario", [1, 2, 3])->where('estado', 1)->get();
        return view('create.createAlist', ['user' => $user]);

    }

    public function busquedaPorInterno($nroInterno)
    {
        $vehiculo = Vehiculo::where('nro_interno', $nroInterno)->with('motorista', 'documento')->first();
        if ($vehiculo) {
            $vehiculo->load('motorista', 'documento');
            $idvehiculo = $vehiculo->idvehiculo;
            $placa = $vehiculo->placa;
            $motorista = $vehiculo->motorista;
            $documentVehi = $vehiculo->documento;
            $response = [
                'idVehi' => $idvehiculo,
                'placa' => $placa,
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
                    'tj_operacion' => $documentVehi->tarjeta_operacion,
                    'km_actual' => $km_Act
                ];
            }
            if ($documentVehi) {
                $vence = [];

                // Verifica el vencimiento de cada tipo de documento y calcula los días restantes
                if ($documentVehi->soat) {
                    $fechaVence = Carbon::createFromFormat('Y-m-d', $documentVehi->soat);
                    if ($fechaVence->isPast()) {
                        $vence['soat'] = "Vencido";
                    } else {
                        $vence['soat'] = $fechaVence->diffInDays(now());
                    }

                }
                if ($documentVehi->revision_tmc) {
                    $fechaVence = Carbon::createFromFormat('Y-m-d', $documentVehi->revision_tmc);
                    if ($fechaVence->isPast()) {
                        $vence['revision_tmc'] = "Vencido";
                    } else {
                        $vence['revision_tmc'] = $fechaVence->diffInDays(now());
                    }
                }
                if ($documentVehi->extra_contra) {
                    $fechaVence = Carbon::createFromFormat('Y-m-d', $documentVehi->extra_contra);
                    if ($fechaVence->isPast()) {
                        $vence['extra_contra'] = "Vencido";

                    } else {
                        $vence['extra_contra'] = $fechaVence->diffInDays(now());
                    }
                }
                if ($documentVehi->tarjeta_operacion) {
                    $fechaVence = Carbon::createFromFormat('Y-m-d', $documentVehi->tarjeta_operacion);
                    if ($fechaVence->isPast()) {
                        $vence['tj_operacion'] = "Vencido";
                    } else {
                        $vence['tj_operacion'] = $fechaVence->diffInDays(now());
                    }
                }
                $response['vence'] = $vence;
            } else {
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
        $request->validate([
            'nro_interno' => 'required',
            'fecha_chequeo' => 'required',
            'id_usuario' => 'required',
        ]);
        $nroInterno = $request->input('nro_interno');
        $documento = $request->input('documento');
        $hora = now()->format('h:i:s A');
        $vehiculo = Vehiculo::where('nro_interno', $nroInterno)->first();
        $motorista = Motorista::where('documento', $documento)->first();
        $fechaChequeo = $request->input('fecha_chequeo');
        $user = Users::find($request->input('id_usuario'));
        $alistExist = Alistamiento::where('id_vehiculo', $vehiculo->idvehiculo)
            ->whereDate('fecha_chequeo', $fechaChequeo)->first();
        if ($alistExist) {
            return response()->json(['success' => false, 'message' => 'Ya se realizó un alistamiento para este vehículo el día de hoy.', 'fechaHoraUltimoAlistamiento' => $alistExist->created_at]);
        }
        if ($vehiculo && $motorista) {
            // $existeAlist = Alistamiento::where('id_vehiculo', $vehiculo->id_vehiculo)->first();
            // if ($existeAlist) {
            //     return response()->json(['succes' => false, 'message' => 'Ya exite un Alistamiento para este vehiculo']);
            // } else {

            $alistamiento = new Alistamiento();
            $alistamiento->id_vehiculo = $vehiculo->idvehiculo;
            $alistamiento->id_motorista = $motorista->idmotorista;
            $alistamiento->fecha_chequeo = $fechaChequeo;
            $alistamiento->hora = $hora;
            $alistamiento->fugasmotor = $request->input('fugasmotor');
            $alistamiento->tensioncorrea = $request->input('tensioncorrea');
            $alistamiento->tapas = $request->input('tapas');
            $alistamiento->aceitemotor = $request->input('aceitemotor');
            $alistamiento->transmision = $request->input('transmision');
            $alistamiento->direccion = $request->input('direccion');
            $alistamiento->frenos = $request->input('frenos');
            $alistamiento->limpia_brisas = $request->input('limpia_brisas');
            $alistamiento->aditivo_radiador = $request->input('aditivo_radiador');
            $alistamiento->filtros = $request->input('filtros');
            $alistamiento->bateria_electrico = $request->input('bateria_electrico');
            $alistamiento->bateria_bornes = $request->input('bateria_bornes');
            $alistamiento->llantas_desgaste = $request->input('llantas_desgaste');
            $alistamiento->llantas_presion = $request->input('llantas_presion');
            $alistamiento->kit_carretera = $request->input('kit_carretera');
            $alistamiento->botiquin = $request->input('botiquin');
            $alistamiento->luces = $request->input('luces');
            $alistamiento->documentacion = $request->input('documentacion');
            $alistamiento->cinturon = $request->input('cinturon');
            $alistamiento->reposacabezas = $request->input('reposacabezas');
            $alistamiento->pito = $request->input('pito');
            $alistamiento->observacion = $request->input('observacion');
            $alistamiento->aprobado = $request->input('aprobado');
            $alistamiento->id_usuario = $request->input('id_usuario');
            $alistamiento->save();
            return response()->json(['success' => true]);
            // }

        } else {
            return response()->json(['success' => false, 'message' => 'Número interno de vehículo no encontrado'], 500);
        }

    }
    public function tableAlist(Request $request)
    {
        $paginations = $request->input('per_page', 25);
        $alistamiento = Alistamiento::orderBy('created_at', 'desc')->with('user', 'vehiculo', 'motorista')->paginate($paginations);
        // $alistamiento = Alistamiento::with('user', 'vehiculo', 'motorista')->whereHas('user',function($query){
        //     $query->where('estado', 1);
        //  })->get();
        $currentPage = $alistamiento->currentPage();
        $itemsPage = $alistamiento->perPage();
        $startPosition = ($currentPage - 1) * $itemsPage + 1;
        foreach ($alistamiento as $alistamientos) {
            $user = $alistamientos->user;
            $vehiculo = $alistamientos->vehiculo;
            $motorista = $alistamientos->motorista;
            $alistamientos->responsable = $user ? $user->nombre_usuario . ' ' . $user->apellido : 'Responsable inexistente';
            $alistamientos->firma = $user->firma;
            $alistamientos->placa = $vehiculo ? $vehiculo->placa : 'placa No encontrada';
            $alistamientos->nroInterno = $vehiculo ? $vehiculo->nro_interno : 'numero de interno No encontrado';
            $alistamientos->numconductor = $motorista ? $motorista->documento : 'numero de conductor No encontrado';
            $alistamientos->nombreConduc = $motorista ? $motorista->nombre : 'Nombre de conductor No encontrado';
            $alistamientos->aprobadoText = $alistamientos->aprobado == 1 ? 'Si' : 'No';
            $alistamientos->position = $startPosition;
            $startPosition++;
        }
        return view('tables.tableAlist', ['alistamiento' => $alistamiento]);
    }
    public function deleteAlist($id)
    {
        try {
            $alistamiento = Alistamiento::find($id);
            $alistamiento->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function pdfDetails($id)
    {
        $alistamiento = Alistamiento::with('user', 'vehiculo', 'motorista', 'documento')->find($id);
        $user = $alistamiento->user;
        $motorista = $alistamiento->motorista;
        $documento = $alistamiento->documento;
        $vehiculo = $alistamiento->vehiculo;
        $alistamiento->responsable = $user ? $user->nombre_usuario . ' ' . $user->apellido : 'Responsable inexistente';
        $alistamiento->placa = $vehiculo ? $vehiculo->placa : 'placa No encontrada';
        $alistamiento->nroInterno = $vehiculo ? $vehiculo->nro_interno : 'numero de interno No encontrado';
        $alistamiento->nombreConduc = $motorista ? $motorista->nombre . ' ' . $motorista->apellido : 'Nombre de conductor No encontrado';

        $alistamiento->soat = $documento->soat;
        $alistamiento->revision_tmc = $documento->revision_tmc;
        $alistamiento->extra_contra = $documento->extra_contra;
        $alistamiento->tarjeta_operacion = $documento->tarjeta_operacion;
        $pdf = new Fpdf();
        $pdf->AddPage('L', 'A4');
        $pdf->SetY(25);
        $pdf->SetFont('Arial', '', 25);
        $pdf->SetTextColor(63, 126, 168);
        $pdf->Cell(0, 10, utf8_decode('Inspección Preoperacional'), 0, 1, 'L');
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
        $pdf->Cell(0, 5, strtoupper($alistamiento->placa), 0, 0, 'L');


        $pdf->SetY(54);
        $pdf->SetX(80);
        $pdf->SetFont('Arial', 'B', 15); // Tamaño 15
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('NÚMERO INTERNO'), 0, 1, 'L');
        $pdf->SetX(97);
        $pdf->SetFont('Arial', '', 13);
        $pdf->SetTextColor(128, 127, 127);
        $pdf->Cell(0, 5, $alistamiento->nroInterno, 0, 0, 'L');

        $pdf->SetY(54);
        $pdf->SetX(140);
        $pdf->SetFont('Arial', 'B', 15); // Tamaño 15
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('CONDUCTOR'), 0, 1, 'L');
        $pdf->SetX(135);
        $pdf->SetFont('Arial', '', 13);
        $pdf->SetTextColor(128, 127, 127);
        $nombre = strtoupper($alistamiento->nombreConduc);

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
        $pdf->Cell(0, 5, $alistamiento->fecha_chequeo, 0, 0, 'L');

        $pdf->Ln(10);
        $pdf->SetDrawColor(128, 127, 127);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(20, $pdf->GetY(), 280, $pdf->GetY());
        $pdf->Ln(5);

        $pdf->SetX(30);
        $pdf->SetFillColor(63, 126, 168); // Color de fondo
        $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
        $pdf->Cell(230, 8, utf8_decode('DOCUMENTOS VEHÍCULO'), 1, 1, 'C', true);
        $pdf->Ln(5);

        $pdf->SetY(95);
        $pdf->SetX(45);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Vencimiento SOAT:'), 0, 1, 'L');

        $pdf->SetY(95);
        $pdf->SetX(95);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode($alistamiento->soat), 0, 1, 'L');

        $pdf->SetY(95);
        $pdf->SetX(135);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Vencimiento Revision TCM:'), 0, 1, 'L');

        $pdf->SetY(95);
        $pdf->SetX(205);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode($alistamiento->revision_tmc), 0, 1, 'L');




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
        $pdf->Cell(0, 8, utf8_decode($alistamiento->extra_contra), 0, 1, 'L');

        $pdf->SetY(110);
        $pdf->SetX(140);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Vencimiento TARJETA OPERACIÓN:'), 0, 1, 'L');

        $pdf->SetY(110);
        $pdf->SetX(225);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode($alistamiento->tarjeta_operacion), 0, 1, 'L');

        $pdf->Ln(5);
        $pdf->SetDrawColor(128, 127, 127);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(20, $pdf->GetY(), 280, $pdf->GetY());
        $pdf->Ln(5);

        $pdf->SetY(125);
        $pdf->SetX(30);
        $pdf->SetFont('Arial', 'B', 11); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Resultado de la verificación:'), 0, 1, 'L');

        $pdf->SetX(30);
        $pdf->SetFillColor(63, 126, 168); // Color de fondo
        $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
        $pdf->Cell(230, 8, 'ESTADO DEL MOTOR ', 1, 1, 'C', true);
        $pdf->Ln(10);

        $pdf->SetY(145);
        $pdf->SetX(35);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Fugas de Motor:'), 0, 1, 'L');

        $pdf->SetY(145);
        $pdf->SetX(100);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->fugasmotor && $alistamiento->fugasmotor == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }


        $pdf->SetY(145);
        $pdf->SetX(150);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Tensión de Correas:'), 0, 1, 'L');

        $pdf->SetY(145);
        $pdf->SetX(210);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->tensioncorrea && $alistamiento->tensioncorrea == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->Ln(4);
        $pdf->SetDrawColor(128, 127, 127);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
        $pdf->Ln(5);


        $pdf->SetY(160);
        $pdf->SetX(35);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Tapas:'), 0, 1, 'L');

        $pdf->SetY(160);
        $pdf->SetX(100);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->tapas && $alistamiento->tapas == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }


        $pdf->SetY(160);
        $pdf->SetX(150);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Nivel de Aceite de Motor:'), 0, 1, 'L');

        $pdf->SetY(160);
        $pdf->SetX(210);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->aceitemotor && $alistamiento->aceitemotor == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->Ln(4);
        $pdf->SetDrawColor(128, 127, 127);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
        $pdf->Ln(5);

        $pdf->SetY(175);
        $pdf->SetX(35);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Transmisión - Fugas:'), 0, 1, 'L');

        $pdf->SetY(175);
        $pdf->SetX(100);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->transmision && $alistamiento->transmision == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->SetY(175);
        $pdf->SetX(150);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Sistema de Dirección:'), 0, 1, 'L');

        $pdf->SetY(175);
        $pdf->SetX(210);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->direccion && $alistamiento->direccion == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->Ln(5);
        $pdf->SetDrawColor(128, 127, 127);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(20, $pdf->GetY(), 280, $pdf->GetY());
        $pdf->Ln(5);

        $pdf->AddPage('L', 'A4');

        $pdf->SetY(10);
        $pdf->SetX(35);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Frenos:'), 0, 1, 'L');

        $pdf->SetY(10);
        $pdf->SetX(100);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->frenos && $alistamiento->frenos == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->SetY(10);
        $pdf->SetX(150);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Estado de Limpia Brisas:'), 0, 1, 'L');

        $pdf->SetY(10);
        $pdf->SetX(210);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->limpia_brisas && $alistamiento->limpia_brisas == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->Ln(4);
        $pdf->SetDrawColor(128, 127, 127);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
        $pdf->Ln(5);

        $pdf->SetY(25);
        $pdf->SetX(35);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Adictivos de Radiador:'), 0, 1, 'L');

        $pdf->SetY(25);
        $pdf->SetX(100);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->aditivo_radiador && $alistamiento->aditivo_radiador == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }


        $pdf->SetY(25);
        $pdf->SetX(150);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Filtros Húmedos y Secos:'), 0, 1, 'L');

        $pdf->SetY(25);
        $pdf->SetX(210);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->filtros && $alistamiento->filtros == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->Ln(4);
        $pdf->SetDrawColor(128, 127, 127);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
        $pdf->Ln(5);

        $pdf->SetY(40);
        $pdf->SetX(35);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Batería: Nivel Eléctrico:'), 0, 1, 'L');

        $pdf->SetY(40);
        $pdf->SetX(100);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->bateria_electrico && $alistamiento->bateria_electrico == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->SetY(40);
        $pdf->SetX(148);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Batería: Ajustes de Bornes:'), 0, 1, 'L');

        $pdf->SetY(40);
        $pdf->SetX(210);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->bateria_bornes && $alistamiento->bateria_bornes == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->Ln(4);
        $pdf->SetDrawColor(128, 127, 127);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
        $pdf->Ln(5);

        $pdf->SetY(55);
        $pdf->SetX(35);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Llantas: Desgaste:'), 0, 1, 'L');

        $pdf->SetY(55);
        $pdf->SetX(100);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->llantas_desgaste && $alistamiento->llantas_desgaste == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->SetY(55);
        $pdf->SetX(150);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Llantas: Presión de Aire:'), 0, 1, 'L');

        $pdf->SetY(55);
        $pdf->SetX(210);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->llantas_presion && $alistamiento->llantas_presion == 1) {
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
        $pdf->Cell(0, 8, utf8_decode('Equipo de Carretera:'), 0, 1, 'L');

        $pdf->SetY(70);
        $pdf->SetX(100);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->kit_carretera && $alistamiento->kit_carretera == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->SetY(70);
        $pdf->SetX(150);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Botiquín:'), 0, 1, 'L');

        $pdf->SetY(70);
        $pdf->SetX(210);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->botiquin && $alistamiento->botiquin == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->Ln(4);
        $pdf->SetDrawColor(128, 127, 127);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
        $pdf->Ln(5);

        $pdf->SetY(85);
        $pdf->SetX(35);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Estado de Luces:'), 0, 1, 'L');

        $pdf->SetY(85);
        $pdf->SetX(80);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->luces && $alistamiento->luces == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->SetY(85);
        $pdf->SetX(115);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Pito:'), 0, 1, 'L');

        $pdf->SetY(85);
        $pdf->SetX(130);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->pito && $alistamiento->pito == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->SetY(85);
        $pdf->SetX(160);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Documentación:'), 0, 1, 'L');

        $pdf->SetY(85);
        $pdf->SetX(210);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->documentacion && $alistamiento->documentacion == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->Ln(4);
        $pdf->SetDrawColor(128, 127, 127);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(258, $pdf->GetY(), 30, $pdf->GetY());
        $pdf->Ln(5);

        $pdf->SetY(100);
        $pdf->SetX(35);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Cinturón de Seguridad:'), 0, 1, 'L');

        $pdf->SetY(100);
        $pdf->SetX(90);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->cinturon && $alistamiento->cinturon == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->SetY(100);
        $pdf->SetX(150);
        $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Reposa Cabezas:'), 0, 1, 'L');

        $pdf->SetY(100);
        $pdf->SetX(210);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        if ($alistamiento->reposacabezas && $alistamiento->reposacabezas == 1) {
            $pdf->Cell(0, 8, utf8_decode('BUENO'), 0, 1, 'L');
        } else {
            $pdf->Cell(0, 8, utf8_decode('MALO'), 0, 1, 'L');
        }

        $pdf->SetX(30);
        $pdf->SetFillColor(63, 126, 168); // Color de fondo
        $pdf->SetTextColor(255, 255, 255); // Color de texto en blanco
        $pdf->Cell(230, 8, 'OBSERVACIONES:', 1, 1, 'C', true);
        $pdf->Ln(10);
        $pdf->SetY(120);
        $pdf->SetX(35);
        $pdf->SetFont('Arial', '', 13); // Tamaño 11
        $pdf->SetTextColor(0, 0, 0);
        $mensajeOb = strtoupper('Sin observacion');
        if ($alistamiento->observacion) {
            $mensajeOb = strtoupper($alistamiento->observacion);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(0, 8, utf8_decode($mensajeOb), 0, 1, 'L');
            $pdf->Ln(12);
            $pdf->SetX(120);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode("Aprobado"), 0, 1, 'L');

            $pdf->SetX(125);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($alistamiento->aprobado && $alistamiento->aprobado == 1) {
                $pdf->Cell(0, 8, utf8_decode('SI'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('NO'), 0, 1, 'L');
            }

            $pdf->SetY(140);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode("Nombre del Responsable"), 0, 1, 'L');
            $pdf->Ln(2);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $respon = mb_strtoupper($user->nombre_usuario . ' ' . $user->apellido);
            $pdf->Cell(0, 8, utf8_decode($respon), 0, 1, 'L');

            $pdf->Ln(2);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode("Firma del Responsable"), 0, 1, 'L');

            $firma = ('storage/public/' . $user->firma);
            $firma_extension = mime_content_type($firma);
            $firmaY = $pdf->GetY();
            if ($firma_extension === 'image/jpeg' || $firma_extension === 'image/jpg') {
                $pdf->Image($firma, 35, $firmaY, 50, 50, 'jpeg');
            } elseif ($firma_extension === 'image/png') {
                $pdf->Image($firma, 35, $firmaY, 50, 50, 'png');
            } else {
                $pdf->Cell(0, 8, utf8_decode("error"), 0, 1, 'L');
            }
        } else {

            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(0, 8, utf8_decode($mensajeOb), 0, 1, 'L');
            $pdf->Ln(12);
            $pdf->SetY(135);
            $pdf->SetX(120);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode("Aprobado"), 0, 1, 'L');

            $pdf->SetX(125);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            if ($alistamiento->aprobado && $alistamiento->aprobado == 1) {
                $pdf->Cell(0, 8, utf8_decode('SI'), 0, 1, 'L');
            } else {
                $pdf->Cell(0, 8, utf8_decode('NO'), 0, 1, 'L');
            }

            $pdf->SetY(135);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode("Nombre del Responsable"), 0, 1, 'L');
            $pdf->Ln(2);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', '', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $respon = mb_strtoupper($user->nombre_usuario . ' ' . $user->apellido);
            $pdf->Cell(0, 8, utf8_decode($respon), 0, 1, 'L');

            $pdf->Ln(2);
            $pdf->SetX(35);
            $pdf->SetFont('Arial', 'B', 13); // Tamaño 11
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, utf8_decode("Firma del Responsable"), 0, 1, 'L');

            $firma = ('storage/public/' . $user->firma);
            $firma_extension = mime_content_type($firma);
            $firmaY = $pdf->GetY();
            if ($firma_extension === 'image/jpeg' || $firma_extension === 'image/jpg') {
                $pdf->Image($firma, 35, $firmaY, 50, 50, 'jpeg');
            } elseif ($firma_extension === 'image/png') {
                $pdf->Image($firma, 35, $firmaY, 50, 50, 'png');
            } else {
                $pdf->Cell(0, 8, utf8_decode("error"), 0, 1, 'L');
            }
        }
        $pdf->Image('img/logo-supertransporte-1.png', 200, 160, 50, 20, 'PNG');
        $response = response($pdf->output('S'), 200);


        $response = response($pdf->output('S'), 200);
        $response->header('Content-Type', 'application/pdf');
        $filename = 'Reporte_Inspeccion_diario' . strtoupper($alistamiento->placa) . '.pdf';
        $response->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
// mecanico = dpto mantenimiento
// despachador = departamento operativo
