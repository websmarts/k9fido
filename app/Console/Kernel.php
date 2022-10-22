<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        // Commands\K9MergeUsers::class,
        Commands\K9MergeRescueData::class,
        // Commands\K9ArchiveData::class,
        // Commands\K9ImportProductImages::class,
        Commands\K9UpdateProductImages::class,
        Commands\K9UpdateProductsFromExcel::class,
        Commands\K9ImportProductsFromExcel::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
