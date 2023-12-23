<?php

namespace App\Console;

use App\Console\Commands\CheckUnverifiedDomains;
use App\Console\Commands\CheckVerifiedDomains;
use App\Console\Commands\GatherDNSRecords;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command(CheckVerifiedDomains::class)->dailyAt("1:00");
        $schedule->command(CheckUnverifiedDomains::class)->dailyAt("2:00");
        $schedule->command(GatherDNSRecords::class)->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
