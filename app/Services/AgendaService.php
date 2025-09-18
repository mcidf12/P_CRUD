<?php

namespace App\Services;
use Illuminate\Validation\ValidationException;
use App\Models\Agenda;
use Illuminate\Database\Eloquent\Collection;

class AgendaService
{
    //verificar que el folio sea unico
    public function folioUnique(int $folio, ?int $Id = null): ?Agenda
    {
        return Agenda::when($Id, fn($q) => $q->where('id', '!=', $Id))
        ->where('folio', $folio)
        ->first();
    }

    public function agendaOcupada(int $userId, string $fecha, string $horaInicio, string $horaFin, ?int $ignoreId = null): Collection{
         return Agenda::where('user_id', $userId)
            ->where('fechaInicio', $fecha)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('horaInicio', '<', $horaFin)
            ->where('horaFin', '>', $horaInicio)
            ->orderBy('horaInicio')
            ->get();
            
    }    
    
}


