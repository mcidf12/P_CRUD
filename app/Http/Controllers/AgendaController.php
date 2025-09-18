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
    }

    
    public function create()
    {
       
    }

        
    public function store(Request $request)
    {
        //
        $request->validate(self::$rules);

        //reglas de validacion (Service)
        if ($conflicto = $this->agendaService->folioUnique((int)$request->folio)) {
            return response()->json([
                'message'  => 'El folio ya existe.',
                'conflict' => $conflicto,  //devolvemos info del folio
            ],422);
        }

        $overlaps = $this->agendaService->agendaOcupada(
            (int)$request->user_id,
            $request->fechaInicio,
            $request->horaInicio,
            $request->horaFin
        );

        if ($overlaps->isNotEmpty()) {
            return response()->json([
                'message'   => 'El usuario ya tiene agenda en ese horario.',
                'conflicts' => $overlaps,  // devolver agenda
            ], 422);
        } 

        $agenda = DB::transaction(function () use ($request) {  

        $agenda = new Agenda;
        $agenda->tipo           = $request->tipo;
        $agenda->descripcion    = $request->descripcion;
        $agenda->folio          = $request->folio;
        $agenda->user_id        = $request->user_id;
        $agenda->municipio      = $request->municipio;
        $agenda->poblacion      = $request->poblacion;
        $agenda->fechaInicio    = $request->fechaInicio;
        $agenda->horaInicio     = $request->horaInicio;
        $agenda->horaFin        = $request->horaFin;

        $agenda->save();        
        //return $agenda;
        });

        return response()->json([
            'mensaje'   => 'Registro Creado',
            'data'      => $agenda->fresh(),
        ]);
    }

    
    public function show($id)
    {
        //
        $agenda = Agenda::findOrFail($id);
        return $agenda;
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
         $agenda = Agenda::findOrFail($id);

        $request->validate(self::$rules);

        // Normalizar/castear lo que comparamos
    $newFolio   = $request->folio;
    $userId     = $request->user_id;
    $fecha      = $request->fechaInicio;
    $horaInicio = $request->horaInicio; 
    $horaFin    = $request->horaFin;    

    
    if ($newFolio !== (int)$agenda->folio) {
        if ($conflicto = $this->agendaService->folioUnique($newFolio, $agenda->id)) {
            return response()->json([
                'message'   => 'El folio ya existe.',
                'conflicto' => $conflicto, // devolvemos info del folio existente
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // valida si cambian los registros
    $relevantesCambiaron = (
        $userId           !== (int)$agenda->user_id ||
        $fecha            !== $agenda->fechaInicio  ||
        $horaInicio       !== $agenda->horaInicio   ||
        $horaFin          !== $agenda->horaFin
    );

    if ($relevantesCambiaron) {
        $overlaps = $this->agendaService->agendaOcupada(
            $userId,
            $fecha,
            $horaInicio,
            $horaFin,
            $agenda->id 
        );

        if ($overlaps->isNotEmpty()) {
            return response()->json([
                'message'   => 'El usuario ya tiene agenda en ese horario.',
                'conflicts' => $overlaps, // devolvemos las agendas que chocan
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    
    DB::transaction(function () use ($agenda, $request, $newFolio, $userId, $fecha, $horaInicio, $horaFin) {
        $agenda->tipo         = $request->tipo;
        $agenda->descripcion  = $request->descripcion;
        $agenda->folio        = $newFolio;
        $agenda->user_id      = $userId;
        $agenda->municipio    = $request->municipio;
        $agenda->poblacion    = $request->poblacion;
        $agenda->fechaInicio  = $fecha;
        $agenda->horaInicio   = $horaInicio;
        $agenda->horaFin      = $horaFin;
        $agenda->save();
    });

    
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
