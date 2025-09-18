<?php

namespace App\Services;
use Illuminate\Validation\ValidationException;
use App\Models\Agenda;
use Illuminate\Database\Eloquent\Collection;

class AgendaService
{
    //verificar que el folio sea unico
    public function folioUnique(int $folio, ?int $ignoreId = null): ?Agenda
    {
        /*$exists = Agenda::when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('folio', $folio)
            ->exists();


            if ($exists) {
                throw ValidationException::withMessages([
                    'folio'=> ['El folio ya existe'],
                    ]);
            }*/
        return Agenda::when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
        ->where('folio', $folio)
        ->first();
    }


    public function agendaOcupada(int $userId, string $fecha, string $horaInicio, string $horaFin, ?int $ignoreId = null): void
    {
        $overlap = Agenda::where('user_id', $userId)
        ->where('fechaInicio', $fecha)
        ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
        ->where('horaInicio', '<', $horaFin)
        ->where('horaFin', '>', $horaInicio)
        ->exists();

        if ($overlap) {
            throw ValidationException::withMessages([
                'use_id'=> ['El usuario ya tiene asigando en ese horario'],
                ]);
            }
    }
    /*public function agendaOcupada(int $user_id, string $fecha, string $horaInicio, string $horaFin, ?int $ignoreId = null): Collection{
        return Agenda::when('user_id', $user_id)
        ->where('fechaInicio', $fecha)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('horaInicio', '<', $horaFin)
            ->where('horaFin', '>', $horaInicio)
            ->orderBy('horaInicio')
            ->get();
    }*/
}
