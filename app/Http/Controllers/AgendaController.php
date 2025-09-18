<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Services\AgendaService;

class AgendaController extends Controller
{

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
        

        $request->validate([
            'tipo'          => 'required|string',
            'descripcion'   => 'required|string',
            'folio'         => 'required|integer',
            'user_id'       => 'required|integer',
            'municipio'     => 'required|string',
            'poblacion'     => 'required|string',
            'fechaInicio'   => 'required|date',
            'horaInicio'    => 'required|date_format:H:i:s',
            'horaFin'       => 'required|date_format:H:i:s|after:horaInicio',
        ]);

        //reglas de validacion (Service)
        //$this->agendaService->folioUnique((int)$request->folio);
        if ($conflict = $this->agendaService->folioUnique((int)$request->folio)) {
            return response()->json([
                'message'  => 'El folio ya existe.',
                'conflict' => $conflict,   // 👈 devolvemos la info asignada a ese folio
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $this->agendaService->agendaOcupada(
            (int)$request->user_id,
            $request->fechaInicio,
            $request->horaInicio,
            $request->horaFin
        );
        /* $overlaps = $this->agendaService->agendaOcupada(
            (int)$request->user_id,
            $request->fechaInicio,
            $request->horaInicio,
            $request->horaFin
        );

        if ($overlaps->isNotEmpty()) {
            return response()->json([
                'message'   => 'El usuario ya tiene agenda en ese horario.',
                'conflicts' => $overlaps,  // devolvemos lo que ya tiene agendado
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }*/



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

        $request->validate([
            'tipo'          => 'required|string',
            'descripcion'   => 'required|string',
            'folio'         => 'required|integer',
            'user_id'       => 'required|integer',
            'municipio'     => 'required|string',
            'poblacion'     => 'required|string',
            'fechaInicio'   => 'required|date',
            'horaInicio'    => 'required|date_format:H:i:s',
            'horaFin'       => 'required|date_format:H:i:s|after:horaInicio',
        ]);

        //reglas de validacion (Service)
        $this->agendaService->folioUnique((int)$request->folio);
        $this->agendaService->agendaOcupada(
            (int)$request->user_id,
            $request->fechaInicio,
            $request->horaInicio,
            $request->horaFin,
            $agenda->id
        );

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

        $agenda->update();        
        return $agenda;
        });

        return response()->json([
            'mensaje'   => 'Registro Creado',
            'data'      => $agenda,
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
