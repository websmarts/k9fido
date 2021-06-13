<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Legacy\Product\Product;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;


class ImportProductsController extends Controller
{


    public function index()
    {
        return view('admin.import.products');
    }

    public function import()
    {
        // dd('importing file');

        $inserts = 0;
        $updates = 0;
        $skipped = 0;
        $n = 1; // spreadsheet row counter



        if (Input::hasFile('spreadsheet')) {

            $path = Input::file('spreadsheet')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get();

            // dd($data);
            if (!empty($data) && $data->count()) {

                foreach ($data as $key => $row) {
                    $n++;
                    

                    // Check if this import has an ID field, if yes then update, if no then insert
                    //dd($row);
                    if ($row->has('id') && $row->id > 0) {
                        // its an UPDATE
                        if (!$product = Product::find($row->id)) {
                            // skip this one because id cant be found
                            $skipped++;
                            echo 'Skipping row ' . $n . ', cannot find product with ID = '. $row->id . '<br>';
                            continue;
                        }
                        $updates++;
                        echo 'Updating ID = '.$row->id .' - product code = '.$row->product_code .'<br>';
                    } else if ($product = Product::where('product_code',$row->product_code)->first()) {
                        // no ID but Product code already exists so update existing record
                        $updates++;
                        echo 'Updating ID = null, Product code = '.$row->product_code .'<br>';
                        
                    } else if(! empty($row->product_code)) {
                        // its and INSERT
                        $inserts++;
                        echo 'Inserting product with product_code = '.$row->product_code.'<br>';
                        $product = new Product;
                    } else {
                        echo 'Skipping row ' . $n . ' because of empty product code<br>';
                        continue;
                    }

                    //dd($product);

                    // assign row values to product object
                    foreach ($row->keys() as $key) {
                       // echo $key .'::'.$row->$key . '<br>';
                        if ($product->hasField($key)) {
                            //echo 'haskey ';
                            if (!is_null($row->$key)) {
                                if ($key == 'barcode') {
                                    $product->$key = (int) $row->$key;
                                } else {
                                    $product->$key = $row->$key;
                                }
                            } else {
                                //echo 'isnull ';
                            }
                        }
                    };
                    //dd($product);
                    //echo 'save product with id=' . $product->id . '<br>';

                    if(!$product->typeid){
                        echo '* Warning:: Row ' . $n . ' is missing product typeid <br>';
                    }
                    //$product->save();

                }
            }
            echo '<hr>';
            echo $inserts. ' Records inserted <br>';
            echo $updates. ' Records updated <br>';
            echo $skipped. ' Rows skipped <br>';
            echo 'All done.<hr>';

            // dd('done import');
            //dd(collect($results[1276]));

        }
    }
}
