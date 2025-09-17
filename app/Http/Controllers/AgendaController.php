<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    
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
        $agenda = new Agenda;

        $request->validate([
            'tipo'          => 'required',
            'descripcion'   => 'required',
            'folio'         => 'required',
            'user_id'       => 'required',
            'municipio'     => 'required',
            'poblacion'     => 'required',
            'fechaInicio'   => 'required',
            'horaInicio'    => 'required',
            'horaFin'       => 'required',
        ]);

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

        return response()->json(['mensaje', 'Registro Creado']) ;
        return $agenda;
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
        $request->validate([
            'tipo'          => 'required',
            'descripcion'   => 'required',
            'folio'         => 'required',
            'user_id'       => 'required',
            'municipio'     => 'required',
            'poblacion'     => 'required',
            'fechaInicio'   => 'required',
            'horaInicio'    => 'required',
            'horaFin'       => 'required',
        ]);

        $agenda = Agenda::findOrFail($id);

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
        
        return response()->json(['mensaje', 'Registro actualizado']) ;
        return $agenda;

    }

    
    public function destroy($id)
    {
        //
        $agenda = Agenda::findOrFail($id);
        $agenda->delete();  
        return response()->json(['message' => 'Registro eliminado'], 200);

    }
}
