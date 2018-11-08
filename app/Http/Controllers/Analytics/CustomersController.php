<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;

class CustomersController extends Controller
{
    
    // User needs to set the period using the first four params below
    
    public $startMonth = 7;

    public $startYear = 2018;

    public $endMonth = 11;

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

        $users = $this->users;

        // generate CSV file
        $this->exportCSV($objects);
        
        // return view('admin.analytics.customers',compact('objects','period','users'));
    }

    

    protected function getObjects()
    {
        $clients = \DB::connection('k9homes')->table('clients')->select('name', 'client_id','salesrep','postcode','level','call_frequency')->get();

        foreach ($clients as &$C){
            $C->sales = $this->sales($C);
            $C->contacts = collect($this->contacts($C));
            $C->orders = collect($this->orders($C));
        }
        return $clients;

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
            
        
        sum(products.cost * system_order_items.qty /100) as cost,
        sum(system_order_items.qty * system_order_items.price/100) as total,
        (sum(system_order_items.qty * system_order_items.price)-sum(products.cost * system_order_items.qty )/100) as gp 

        from system_orders 

        

        join system_order_items on system_orders.order_id = system_order_items.order_id

        join products on system_order_items.product_code = products.product_code 

        join type on products.typeid = type.typeid 

        join type_category on type_category.typeid=products.typeid 

        join category on category.id = type_category.catid 


        WHERE system_orders.modified >= FROM_UNIXTIME("' . $this->start->getTimestamp() . '") 

        AND system_orders.modified <= FROM_UNIXTIME("' . $this->end->getTimestamp() . '") ';
        

        $sql .= ' AND system_orders.client_id =' . $client->client_id . ' ';
                

        $result =  \DB::connection('k9homes')->select($sql);

        
        return $result[0];

    }
    protected function exportCSV($objects)
    {
        
        
        $filename = "analyser_export_customers";
        $output = fopen('php://output', 'w');

        ob_start();

        $header = array(
            'ID',
            'Name',
            'Postcode',
            'Level',
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
            $entry['level'] = $o->level;
            $entry['Call Frequency'] = $o->call_frequency;
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

}



