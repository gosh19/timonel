<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\Cliente;
use App\Notification;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      $schedule->call(function () {
        date_default_timezone_set("America/Argentina/Jujuy");
        $hora=date("h:i");
        $fecha=date("Y-m-d");

        $result=Notification::where('fecha', $fecha)
                 ->where('hora', $hora)->first();
        if($result){
           $empresa_id=$result->empresa_id;
          $clientes=Cliente::where('empresa_id',$empresa_id)->get();

          foreach ($clientes as $cliente) {
            foreach ($cliente->correos as $correo) {
                   $name=$cliente->nombre;
                   $title=$result->titulo;
                   $messagepro=$result->mensaje;
                    Mail::to($correo->correo)->send(new SendMailable($name,$title,$messagepro));
            }
          }
       }else{
         echo 'cron success';
       }
      })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
