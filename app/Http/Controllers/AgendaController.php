<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Services\AgendaService;

class AgendaController extends Controller
{

    public static $rules = [
            'tipo'          => 'required|string',
            'descripcion'   => 'required|string',
            'folio'         => 'required|integer',
            'user_id'       => 'required|integer',
            'municipio'     => 'required|string',
            'poblacion'     => 'required|string',
            'fechaInicio'   => 'required|date',
            'horaInicio'    => 'required|date_format:H:i:s',
            'horaFin'       => 'required|date_format:H:i:s|after:horaInicio',
        ];

    public function __construct(private AgendaService $agendaService) {}
    
    public function index()
    {
        return Agenda::all();
       /*return Agenda::orderBy('fechaInicio', 'desc')
            ->orderBy('horaInicio')
            ->paginate(20);*/
    }

    
    public function create()
    {
       
    }

        
    public function store(Request $request)
    {
        //
        $data=$request->validate(self::$rules);

        if ($conflicto=$this->agendaService->folioUnique((int)$data['folio'])) {
            return response()->json([
                'message' => 'El folio ya existe.',
                'conflict' => $conflicto,
            ], 422);
        }

        $overlaps = $this->agendaService->agendaOcupada(
            (int)$data['user_id'],
            $data['fechaInicio'],
            $data['horaInicio'],
            $data['horaFin']
            );
            
        if ($overlaps->isNotEmpty()) {
            return response()->json([
                'message' => 'El usuario ya tiene agenda en ese horario.',
                'conflict'=> $overlaps,
            ], 422);
        }
        

        $agenda = DB::transaction(fn() => Agenda::create($data));

         return response()->json([
            'mensaje' => 'Registro Creado',
            'data'    => $agenda,
        ], 201);
    
    }

    
    public function show($id)
    {
        //
        return $agenda = Agenda::findOrFail($id);
        
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
        $agenda = Agenda::findOrFail($id);
        $data=$request->validate(self::$rules);

        if ((int)$data['folio'] !== (int)$agenda->folio && $conflicto=$this->agendaService->folioUnique((int)$data['folio'])) {
            return response()->json([
                'message' => 'El folio ya existe.',
                'conflict' => $conflicto,
            ], 422);
        }    

        $cambio = (
            (int)$data['user_id'] !== (int)$agenda->user_id ||
            $data['fechaInicio'] !== $agenda->fechaInicio ||
            $data['horaInicio'] !== $agenda->horaInicio ||
            $data['horaFin'] !== $agenda->horaFin
        );

        if ($cambio) {
            $conflicto=$this->agendaService->agendaOcupada(
                (int)$data['user_id'],
                $data['fechaInicio'],
                $data['horaInicio'],
                $data['horaFin'],
                $agenda->id 
            );

            if ($conflicto->isNotEmpty()) {
                return response()->json([
                    'message'   => 'El usuario ya tiene agenda en ese horario.',
                    'conflicts' => $conflicto, // devolvemos las agendas que chocan
                ], 422);
            }
        }
    
        DB::transaction(fn() => $agenda->update($data));
    
        return response()->json([
            'mensaje' => 'Registro Actualizado',
            'data'    => $agenda->fresh(), 
        ]);
    }

    
    public function destroy($id)
    {
        //
        $agenda = Agenda::findOrFail($id);
        $agenda->delete();  
        return response()->json(['message' => 'Registro eliminado'], 200);

    }
}
