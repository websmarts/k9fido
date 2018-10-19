<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    
    // User needs to set the period using the first four params below
    
    public $startMonth = 7;

    public $startYear = 2017;

    public $endMonth = 6;

    public $endYear = 2018;



    public $start;

    public $end;

    public $period; // number of days in analysis period

    public $users;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');


        $this->start = new \DateTime($this->startYear . '-' . $this->startMonth .-'01');
        $this->end = new \DateTime($this->endYear . '-' . $this->endMonth .'-30');

        $this->period = $this->end->diff($this->start)->format("%a");

        $this->users = \App\Legacy\Staff\User::all();
        

        
    }

    public function index()
    {
        
       $objects = $this->getObjects();
        // dd($objects);
        $period = $this->period;

        

        // generate CSV file
        $this->exportCSV($objects);
        
        // return view('admin.analytics.customers',compact('objects','period','users'));
    }

    

    protected function getObjects()
    {
        
        
        $orders = \App\Legacy\Order\Order::with(['client', 'items.product'])
        ->whereBetween('modified',[$this->start->format('Y-m-d'),$this->end->format('Y-m-d')])->get();

        $sales=[];
        
        foreach ($orders as $o){
            
            
            foreach($o->items as $i){

                if(! array_key_exists($i->product_code, $sales) ){
                    $sales[$i->product_code] = [];
                }

                if(array_key_exists($o->client->level, $sales[$i->product_code]) ){
                    $sales[$i->product_code][$o->client->level] += $i->qty * $i->price /100;
                } else {
                    $sales[$i->product_code][$o->client->level] = $i->qty * $i->price /100;
                }
                
            }

            
        }

        $top = .95; // Top 80%

        // sort sales for aaa in desc
        $sales = collect($sales);
        $levels = ['AAA','AA','A','B','C','D','E','X'];

    
        $levelSales =[];

        foreach($levels as $level){
            $levelSales[$level] = $sales->map(function($item,$key) use ($level) {
            return isSet($item[$level])?$item[$level]: 0;
        });
        }

        // Sort all level arrays by sales value desc
        $sorted = [];
        foreach($levels as $level){
            $sorted[$level] = $levelSales[$level]->sort()->reverse();
        }

        // get totals sales for each level
        $totals = [];
        foreach($levels as $level){
            $totals[$level] = $sorted[$level]->sum();
        }

        // Total sales
        $totalSales = collect($totals)->sum();

        
        // Trim each array to only include parito values
        foreach($levels as $level){
            $sum = 0;
            $cutoff = $top * $totals[$level];
            $activeProducts[$level] = $sorted[$level]->filter(function ($item,$key) use (&$sum, $cutoff){
                $sum += $item;
                if($sum > $cutoff ){
                    
                    return false;
                } else {
                    
                    return $item;
                }
                
            });
            
        }

        // Get list of active products by combining all the keys
        $useful =[];
        foreach($levels as $level){
            
            $activeProducts[$level]->each(function($item,$key) use(&$useful){
                if(isSet($keys[$key])){
                    $useful[$key]++;
                } else {
                    $useful[$key]=1;
                }
                
            });
        }

        
        $activeProductCodes = \DB::connection('k9homes')->select('select product_code from products where `status`="active"');
        $useless =[];
        foreach($activeProductCodes as $pc){
            
            if(!isSet($useful[$pc->product_code])){
                $useless[$pc->product_code] = 1;
            }
        }

        dd($useless);

        // Value of useless stock

        $products = \DB::connection('k9homes')->select('select sum(cost * qty_instock) from products where product_code IN ("'.implode('","',array_keys($useless)).'")');
        dd($products);
        

        // return $this->exportSalesCSV($sales);


    }

    protected function contacts($client)
    {
        $sql = '
        select * 

        FROM contact_history
        
        WHERE updated_at >= FROM_UNIXTIME("' . $this->start->getTimestamp() . '") 

        AND updated_at <= FROM_UNIXTIME("' . $this->end->getTimestamp() . '") 
        
        AND client_id = '.$client->client_id;
        

        return \DB::connection('k9homes')->select($sql);
       
    }

    protected function orders($client)
    {
        $sql = 'select * from system_orders 
        WHERE system_orders.modified >= FROM_UNIXTIME("' . $this->start->getTimestamp() . '") 
        AND system_orders.modified <= FROM_UNIXTIME("' . $this->end->getTimestamp() . '") ';
        $sql .= ' AND system_orders.client_id =' . $client->client_id . ' ';

        return \DB::connection('k9homes')->select($sql);

    }

    protected function sales($client)
    {

        $sql = 'select 
            
        clients.name,
        clients.client_id,
        clients.call_frequency,
        sum(products.cost * system_order_items.qty ) as cost,
        sum(system_order_items.qty * system_order_items.price) as total,
        (sum(system_order_items.qty * system_order_items.price)-sum(products.cost * system_order_items.qty )) as gp 

        from system_orders 

        join clients on clients.client_id = system_orders.client_id

        join system_order_items on system_orders.order_id = system_order_items.order_id

        join products on system_order_items.product_code = products.product_code 

        join type on products.typeid = type.typeid 

        join type_category on type_category.typeid=products.typeid 

        join category on category.id = type_category.catid 


        WHERE system_orders.modified >= FROM_UNIXTIME("' . $this->start->getTimestamp() . '") 

        AND system_orders.modified <= FROM_UNIXTIME("' . $this->end->getTimestamp() . '") ';
        

        $sql .= ' AND clients.client_id =' . $client->client_id . ' ';
                

        $result =  \DB::connection('k9homes')->select($sql);
        return $result[0];

    }
    protected function exportCSV($objects)
    {
        
        
        $filename = "analyser_export";
        $output = fopen('php://output', 'w');

        ob_start();

        $header = array(
            'ID',
            'Name',
            'Postcode',
            'Call Frequency',
            'Visits',
            'Phone Calls',
            'Sales',
            'COS',
            'GP',
            'Orders',
            'REP',
            'REP ID',
            'Period'
            
        );

        fputcsv($output, $header);

        foreach ($objects as $o) {

            $entry = [];

            
            $entry['ID'] = $o->client_id;
            $entry['Name'] = $o->name;
            $entry['Postcode'] = $o->postcode;
            $entry['Call Frequency'] = $o->sales->call_frequency;
            $entry['Visits'] = $o->contacts->where('call_type_id',1)->count();
            $entry['Phones'] = $o->contacts->where('call_type_id',2)->count();
            $entry['Sales']=$o->sales->total;
            $entry['COS']=$o->sales->cost;
            $entry['GP']=$o->sales->gp;
            $entry['Orders']=$o->orders->count();
            $entry['REP']= $o->salesrep ? $this->users->where('id',$o->salesrep)->first()->name :'TBD';
            $entry['REP ID']= $o->salesrep;
            $entry['Period'] = $this->period;


            fputcsv($output, $entry);
        }

        $string = ob_get_clean();

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv";');
        header('Content-Transfer-Encoding: binary');

        exit($string);
        
    }

    protected function exportSalesCSV($objects)
    {
        
        
        $filename = "analyser_sales_export";
        $output = fopen('php://output', 'w');

        ob_start();

        $header = array(
            'Product code',
            'AAA',
            'AA',
            'A',
            'B',
            'C',
            'D',
            'E',
            'X'
            
            
        );

        fputcsv($output, $header);

        foreach ($objects as $productCode => $o) {

            $entry = [];

            
            $entry['Product code'] = $productCode;
            $entry['AAA'] = isSet($o['AAA']) ? $o['AAA']:0;
            $entry['AA'] = isSet($o['AA']) ? $o['AA']:0;
            $entry['A'] = isSet($o['A']) ? $o['A']:0;;
            $entry['B'] = isSet($o['B']) ? $o['B']:0;
            $entry['C'] = isSet($o['C']) ? $o['C']:0;
            $entry['D']= isSet($o['D']) ? $o['D']:0;
            $entry['E']= isSet($o['E']) ? $o['E']:0;
            $entry['X']= isSet($o['X']) ? $o['X']:0;
            


            fputcsv($output, $entry);
        }

        $string = ob_get_clean();

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv";');
        header('Content-Transfer-Encoding: binary');

        exit($string);
        
    }

}



