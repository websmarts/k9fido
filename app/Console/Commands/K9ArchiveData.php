<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class K9ArchiveData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'k9:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move stale data to archive tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Move any new records into the archive tables
        // $res = DB::connection('k9homes')->select('select max(id) as max_id from system_orders_archive');
        // $sql = 'INSERT INTO
        //         system_orders_archive
        //         (
        //         SELECT *
        //         FROM system_orders
        //         WHERE system_orders.id > ' .
        // $res[0]->max_id . ')';

        // $res = DB::connection('k9homes')->statement($sql);

        // Remove aged records from the

        //$this->info($sql);

        $this->info('Archive done');
    }
}
