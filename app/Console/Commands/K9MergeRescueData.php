<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class K9MergeRescueData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'k9:mergerescuedata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge rescue data with production';

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
        $this->mergeContacts();
        $this->mergeOrders();

        $this->info('Job done');
    }

    public function mergeOrders()
    {
        $records = DB::connection('cd_rescue_k9')->select('select * from system_orders where id >46047');

        $bumpBy = 700; // Bump exiosting order numbers by this amount
        
        $this->renumberTransitionOrderNumbers($bumpBy); // bump transition order_numbers to stop duplicate order #s

        // Now insert rescue order records and their order items
        foreach ($records as  $order) {
            
            echo "Merging order#".$order->order_id ."\r\n";
            $orderArry = (array) $order;
            // Insert the order
            DB::connection('k9homes')->table('system_orders')->insert($orderArry);


            //Insert the order items
            $items = DB::connection('cd_rescue_k9')->table('system_order_items')->where('order_id', '=', $order->order_id)->get();

            foreach ($items as $item) {

                $item->id = null;
                $itemArry = (array) $item;

                DB::connection('k9homes')->table('system_order_items')->insert($itemArry);
            }

            
        }
    }

    public function mergeContacts()
    {
       echo 'Merging contact history records....';
        if ($records = DB::connection('cd_rescue_k9')->select('select  client_id,call_datetime,contacted,note,followup,status,call_type,call_type_id,call_by,last_contacted_datetime,created,updated_at from contact_history where id > 57744')) {
            foreach ($records as $r) {
                $data = (array) $r;
                DB::connection('k9homes')->table('contact_history')->insert($data);
            }
        };
        echo 'contact records merged'."\r\n";
    }

    // Helpers
    public function renumberTransitionOrderNumbers($bumpBy)
    {

        echo 'Bumping transition Order id by ' . $bumpBy . "\r\n";

        $transitionOrders = DB::connection('k9homes')->select('select * from system_orders where id > 46047');


        foreach ($transitionOrders as $order) {


            $newOrderId = $order->id + $bumpBy;
            $newOrderNumber = 'T0_' . $newOrderId;

            // update order items order_id field
            DB::connection('k9homes')->table('system_order_items')
                ->where('order_id', '=', $order->order_id)
                ->update(['order_id' => $newOrderNumber]);

            // Now update the orders id and order_id
            DB::connection('k9homes')->table('system_orders')
                ->where('id', '=', $order->id)
                ->update(['id' => $newOrderId, 'order_id' => $newOrderNumber]);

            echo 'Updated order# ' . $order->id . ' to ' . $newOrderId . "\r\n";


        }
    }

    public function makeNewOrderId($orderId, $bumpBy)
    {
        $newId = $orderId + $bumpBy;
    }
}
