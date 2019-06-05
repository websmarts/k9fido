<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class K9ImportProductsFromExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'k9:importproductsfromexcel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from a fixed format spreadshett';

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

        $file = storage_path('imports') . '/products_import.xlsx';

        Excel::load($file, function ($reader) {

            $newTypeids = [];// can be used to change typeids [current_typeid => new_type_id]

            foreach ($reader->all() as $row) {

                $category = Category::where('name', $row[0])->get()->first();

                $typeid = (int) $row->typeid;

                if ((int) $row->typeid == 0) {

                    if (isset($newTypeids[$row->typeid])) {
                        $typeid = $newTypeids[$row->typeid];
                    } else {

                        // create a new typeid
                        $tData = [
                            'name' => $row->typeid,
                            'display_format' => 'v',
                            'status' => 'active',
                        ];
                        $type = ProductType::create($tData);

                        $typeid = $type->typeid;

                        $newTypeids[$row->typeid] = $typeid;

                        // Add the type_category entry
                        $cData = [
                            'catid' => $category->id,
                            'typeid' => $typeid,
                            'modified' => date('Y-m-j'),
                        ];
                        $typeCategory = CategoryType::create($cData);
                    }

                }

                if (! (int) $row->id){
                     // add the product
                $pData = [
                    'description' => $row->description,
                    'size' => $row->size,
                    'price' => (int) $row->price,
                    'product_code' => $row->product_code,
                    'typeid' => $typeid,
                    'qty_break' => (int) $row->qty_break,
                    'qty_discount' => (int) $row->qty_discount,
                    'qty_instock' => (int) $row->qty_instock,
                    'qty_ordered' => (int) $row->qty_ordered,
                    'special' => (int) $row->special,
                    'clearance' => (int) $row->clearance,
                    'can_backorder' => $row->can_backorder,
                    'status' => $row->status,
                    'modified' => date('Y-m-j H:i:s'),
                    'cost' => (int) $row->cost,
                    'last_costed_date' => $row->last_costed_date->format('Y-m-j H:i:s'),
                    'width' => (int) $row->width,
                    'height' => (int) $row->height,
                    'length' => (int) $row->length,
                    'shipping_volume' => (int) $row->shipping_volume,
                    'shipping_weight' => (int) $row->shipping_weight,
                    'actual_weight' => (int) $row->actual_weight,
                    'shipping_container' => (int) $row->shipping_container,
                    'display_order' => (int) $row->display_order,
                    'barcode' => substr($row->barcode, 0, 12),
                    'color_name' => $row->color_name,
                    'color_background_color' => $row->color_background_color,
                    'color_background_image' => $row->color_background_image,
                    'notify_when_instock' => $row->notify_when_instock,
                    'source' => $row->source,
                    'new_product' => (int) $row->new_product,
                    'core_product' => (int) $row->core_product,
                    'low_stock_level' => (int) $row->low_stock_level,
                    'rrp' => (int) $row->rrp,

                ];

                $product = Product::create($pData);

                var_dump($product->id);

                } else {
                    // update the product because there is an id
                }

               

            }
        });

        $this->info('Image import and move complete');
    }
}
