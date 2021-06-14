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

        //dd(count($rows));

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
            // add checkdigit to barcode to create ean_barcode
            $data[$records]['ean_barcode'] = '';
            if(!empty($row->barcode)){
                $data[$records]['ean_barcode'] = $row->barcode.$this->checkdigit($row->barcode);
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
    function checkdigit( $digits)
	{
		if(!$digits){
			return '';
		}	
		
		$digits = (string) $digits;
		
		if(strlen($digits) != 12){
			return '';
		}
		
		// 1. Add the values of the digits in the even-numbered positions: 2, 4, 6, etc.
		$even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
		// 2. Multiply this result by 3.
		$even_sum_three = $even_sum * 3;
		// 3. Add the values of the digits in the odd-numbered positions: 1, 3, 5, etc.
		$odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
		// 4. Sum the results of steps 2 and 3.
		$total_sum = $even_sum_three + $odd_sum;
		// 5. The check character is the smallest number which, when added to the result in step 4,  produces a multiple of 10.
		$next_ten = (ceil($total_sum / 10)) * 10;
		$check_digit = $next_ten - $total_sum;

		return $check_digit;
	}
}
