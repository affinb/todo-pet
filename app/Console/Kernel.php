<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Pet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
	    // $schedule->command('inspire')->hourly();
	    // 15分毎にHPを1ずつ減らす、25時間で100減る
        $schedule->call(function () {
            $pet = Pet::find(1);
            $pet->hp -= 1;
            $pet->save();
        })->everyFifteenMinutes();
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
