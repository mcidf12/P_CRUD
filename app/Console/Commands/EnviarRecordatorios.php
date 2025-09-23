<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Agenda;
use Illuminate\Console\Command;

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
    public function handle() :int
    {
        $today = Carbon::today()->toDateString();

        $agendas=Agenda::whereDate('fechaInicio', $today)->get();

        foreach ($agendas as $agenda) {
            $this->info("Recordatorio: {$agenda->tipo} - {$agenda->descripcion} ({$agenda->horaInicio})");
        }
        return Command::SUCCESS;
    }
}
