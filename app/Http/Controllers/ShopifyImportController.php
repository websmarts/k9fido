<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Legacy\Product\Product;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;




class ShopifyImportController extends Controller
{
    

    protected $fieldKeys = [
        'ID',
        'Handle',
        'Title',
        'Body HTML',
        'Vendor',
        'Type',
        'Tags',
        'Published',
        'Option1 Name',
        'Option1 Value',
        'Option2 Name',
        'Option2 Value',
        'Option3 Name',
        'Option3 Value',
        'Variant ID',
        'Variant SKU',
        'Variant Weight',
        'Variant Weight Unit',
        'Variant Inventory Qty',
        'Variant Inventory Tracker',
        'Variant Inventory Policy',
        'Variant Fulfillment Service',
        'Variant Price',
        'Variant Compare at Price',
        'Variant Requires Shipping',
        'Variant Taxable',
        'Variant Barcode',
        'Variant Cost',
        'Image Src',
        'Image Position',
        'Image Alt Text'
        
    ];

    


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        
    }

    public function index()
    {
        return view('admin.shopify.index');
    }

    
    
    public function import(Request $request)
    {
        //dd($request);

        if (Input::hasFile('spreadsheet')) {

            $path = Input::file('spreadsheet')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get();

            // dd($data);
            if (!empty($data) && $data->count()) {

                foreach ($data as $key => $value) {

                    //dd($value);

                    if( $product = Product::where('product_code',$value->variant_sku)->first() ) {
                        //dd($product);

                        // Update product info and save
                        $product->shopify_id = $value->id;
                        //$product->shopify_published = $value->shopify == 'N' ? 0 :1; // only use initially - dont update down track
                        $product->shopify_published = $value->published;
                        $product->shopify_handle = $value->handle;
                        $product->shopify_type = $value->type;
                        $product->shopify_tags = $value->tags;
                        $product->shopify_option1_name = $value->option1_name;
                        $product->shopify_option1_value = $value->option1_value;
                        $product->shopify_option2_name = $value->option2_name;
                        $product->shopify_option2_value = $value->option2_value;
                        $product->shopify_option3_name = $value->option3_name;
                        $product->shopify_option3_value = $value->option3_value;
                        $product->shopify_variant_id = $value->variant_id; 
                        $product->shopify_variant_weight = $value->variant_weight;
                        $product->shopify_variant_weight_unit = $value->variant_weight_unit;
                        $product->shopify_image_alt_text = $value->image_alt_text;
                        //$product->supplier = $value->vendor; // I think this one is wrong - should just be k9homes???

                        // dd($product);
                        $product->save();




                    }

                  

                }
                dd('done import');
                 //dd(collect($results[1276]));
            }
        }
    }


    
}
