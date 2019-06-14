<?php

namespace App\Console\Commands;

use App\Legacy\Product\Product;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class K9UpdateProductsFromExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'k9:updateproductsfromexcel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update products from a fixed format spreadshett';

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
        ini_set('memory_limit', '-1');

        // NOTE THIS IS NOT WORKING _ JUST SOME CODE I PLAYED AROUND WITH TO IMPORT DATE FROM A SPREADSHEET
        // NEEDS WORK TO }BE USEFUL and MAY NOT BE NEEDED ANYWAY

        $file = storage_path('imports') . '/product_updates.xlsx';

        Excel::load($file, function ($reader) {


            foreach ($reader->all() as $row) {



                $id = (int)$row->id;

                if (!isset($row->typeid)) {
                    continue; // skip blank lines
                }


                // update the product
                $pData = [

                    'description' => $row->description,
                    'size' => $row->size,
                    'price' => (int)$row->price,
                    'product_code' => $row->product_code,
                    'typeid' => (int)$row->typeid,
                    'qty_break' => (int)$row->qty_break,
                    'qty_discount' => (int)$row->qty_discount,
                    'qty_instock' => (int)$row->qty_instock,
                    'qty_ordered' => (int)$row->qty_ordered,
                    'special' => (int)$row->special,
                    'clearance' => (int)$row->clearance,
                    'can_backorder' => $row->can_backorder,
                    'status' => $row->status,
                    'modified' => date('Y-m-j H:i:s'),
                    'cost' => (int)$row->cost,
                    'last_costed_date' => $row->last_costed_date->format('Y-m-j H:i:s'),
                    'width' => (float)$row->width,
                    'height' => (float)$row->height,
                    'length' => (float)$row->length,
                    'shipping_volume' => (float)$row->shipping_volume,
                    'shipping_weight' => (float)$row->shipping_weight,
                    'actual_weight' => (float)$row->actual_weight,
                    'actual_weight' => (float)$row->actual_weight,
                    'shipping_container' => (int)$row->shipping_container,
                    'display_order' => (int)$row->display_order,
                    'barcode' => substr($row->barcode, 0, 12),
                    'color_name' => $row->color_name,
                    'color_background_color' => $row->color_background_color,
                    'color_background_image' => $row->color_background_image,
                    'notify_when_instock' => $row->notify_when_instock,
                    'source' => $row->source,
                    'new_product' => (int)$row->new_product,
                    'core_product' => (int)$row->core_product,
                    'low_stock_level' => (int)$row->low_stock_level,
                    'rrp' => (int)$row->rrp,
                    'product_note' => $row->product_note

                ];

                // dd($pData);

                echo $id . ', ';

                if ($id > 0) {
                    // Update product

                    $product = Product::find($id);

                    // dd($product->toArray());

                    $product->update($pData);

                    echo 'Updating product ID = ' . $product->id . "\r\n";
                } else {
                    // Insert product
                    $product = Product::create($pData);
                    echo 'Inserting product ID = ' . $product->id . "\r\n";
                }
            }
        });

        $this->info('Update complete');
    }
}
