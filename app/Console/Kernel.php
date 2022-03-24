<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        Commands\WalmartGetOrders::class,
        Commands\walmartItems::class,
        Commands\WalmartOrders::class,
        Commands\WalmartAnOrder::class,
        Commands\walmartDelivery::class,
        Commands\WalmartShipping::class,
        Commands\WalmartCarrierPerformance::class,
        Commands\RegionalPerformance::class,
        Commands\WalmartRatingReview::class,
        Commands\CleanLogFile::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('command:walmartItems')->everyMinute();
        // $schedule->command('command:walmartOrder')->everyMinute();
        // $schedule->command('command:WalmartAnOrder')->everyMinute();
        // $schedule->command('command:walmartDelivery')->everyMinute();
        // $schedule->command('command:WalmartShipping')->everyMinute();
        // $schedule->command('command:WalmartCarrierPerformance')->everyMinute();
        // $schedule->command('command:RegionalPerformance')->everyMinute();
        $schedule->command('command:WalmartRatingReview')->everyMinute();
    //    $schedule->command('clean:log')->everyMinute();
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
