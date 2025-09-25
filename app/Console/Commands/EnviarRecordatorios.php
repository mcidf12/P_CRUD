<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Agenda;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EnviarRecordatorios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recordatorios:enviar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios pendientes';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() 
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();
        //echo $now->toDateString();
        //echo $now->toTimeString();

        $agendas=Agenda::whereDate('fechaInicio', $today)->get();

        foreach ($agendas as $agenda) {
            $mensaje="Recordatorio: {$agenda->tipo} - {$agenda->descripcion} ({$agenda->horaInicio})";
            $this->info($mensaje);

            Log::info($mensaje, [
                "Tipo"=> $agenda->tipo,
                "Descripcion"=> $agenda->descripcion,
                "Municipio"=> $agenda->municipio,
                "Fecha de Inicio"=> $agenda->fechaInicio,
                "Hora de Inicio"=> $agenda->horaInicio,
            ]);
        }
        //return Command::SUCCESS;
    }
}
