<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Legacy\Order\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ExportPickOrderItemsController extends Controller
{
    public function export()
    {
       
         
        $orders = Order::with('items')
            ->whereIn('status', ['saved', 'printed', 'parked', 'picked', 'basket'])
            ->whereDate('modified', '>=', Carbon::today()->subDays(90)->toDateString())
            ->where('exported', '!=', 'yes')
            ->orderBy('id', 'desc')
            ->get();

        $pickOrders = $orders->where('status', 'printed');


        $data = [];
        $descriptions =[];

        foreach($pickOrders as $order){
            foreach($order->items as $item){
                
                if( array_key_exists($item->product_code,$data) ){
                    $data[$item->product_code] += $item->qty;
                } else {
                    $data[$item->product_code] = $item->qty;
                }
                $descriptions[$item->product_code] = $item->product->description;
            }

        }
        //dd($data);

        $exportData[]= ['Product code','Description','Pick quantity'];
        foreach($data as $code=>$qty){
            $exportData[] = [$code,$descriptions[$code],$qty];
        }
        

       //dd($exportData);
       

       
        
        $type='xls';

        return Excel::create('pick_items_export_'.date('dmY_His'), function($excel) use ($exportData) {
            $excel->sheet('mySheet', function($sheet) use ($exportData)
            {
                $sheet->fromArray($exportData,null, 'A1', false, false);
            });
        })->download($type);
    }
    
}
