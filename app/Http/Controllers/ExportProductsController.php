<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Legacy\Product\Product;
use App\Legacy\Category\Category;
use Illuminate\Support\Facades\DB;
use App\Legacy\Product\ProductType;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ExportProductsController extends Controller
{
    public function export()
    {
        $product = new Product;
        $dbFields = array_merge(['id'],$product->getFillable(),['updated_at','created_at']);

        $rows = DB::connection('k9homes')->table('products')->get();
        // dd($rows);

        // dd(count($rows));

        $data =[];
        $records = 0;

        foreach($rows as $row) {
            $records++;

            foreach($dbFields as $field){
                if(property_exists($row,$field)){
                    $data[$records][$field] = $row->$field;
                } else {
                    // do not know how this could happen??
                    //dd($row->product_code.'  row did not have key='.$field);
                    continue;
                }
                
            }

        }

        //dd($data);
        
        $type='xls';

        return Excel::create('product_table_export_'.date('dmY_His'), function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
}
