<?php
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
class Kernel extends ConsoleKernel {
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<int, class-string<\Illuminate\Console\Command>>
     */

   protected function schedule(Schedule $schedule) {
       // Cada minuto (puedes ajustar)
       $schedule->command('coins:collect')->everyMinute()->withoutOverlapping();
   }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
