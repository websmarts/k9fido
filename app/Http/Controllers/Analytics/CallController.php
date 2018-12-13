<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;

class CallController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.analytics.calls.index');
    }

    public function plan($rep)
    {
        // get the list of clients for the rep
        $report = $this->callReport($rep);

        //return $report;
        return view('admin.analytics.calls.report');
        
    }

    public function callReport($salesRepId)
    {
        


        
        
    

        $clients = \DB::connection('k9homes')->table('clients')
            ->join('contact_history','clients.client_id','=','contact_history.client_id')
            ->join('users','salesrep','=','users.id')
            ->join('system_orders','clients.client_id','=','system_orders.client_id')
            ->where('salesrep',$salesRepId)
            ->select('clients.client_id','clients.name','postcode','phone_area_code','phone','contacts','call_frequency','level','call_planning_note')
            ->addSelect(\DB::raw('max(contact_history.last_contacted_datetime) as lastcall'))
            ->addSelect(\DB::raw('max(system_orders.modified) as lastorderdate'))
            ->groupBy('clients.client_id')
            ->orderBy('call_frequency','desc')
            ->get();

        

        $C = collect($clients);

    

        // Add the duein days to the client
        $C->transform(function($item,$key){
            if($item->lastcall){
                $item->duein = round($item->call_frequency - (time() - strtotime($item->lastcall)) / (86400)); 
            } else {
                $item->duein = $item->call_frequency;
            }  
            return $item;         
        });

        
        // Sort the collection by call_frequency asc and duein asc
        $sorted = $C->groupBy('level');
        
        return $sorted;
        dd($sorted);

        $result = array();
        if ($query = $this->db->query($sql)) {
            if ($query == -1) {
                pr($sql);
                return [];
            }
            while ($row = mysqli_fetch_assoc($query)) {
                if ($row['lastcall']) {
                    $row['duein'] = round($row['call_frequency'] - (time() - strtotime($row['lastcall'])) / (86400));
                } elseif ($row['call_frequency']) {
                    $row['duein'] = $row['call_frequency'];
                }
                $res[] = $row;

            }
            // sort by call frequency asc and duein asc
            foreach ($res as $key => $row) {
                $frequency[$key] = $row['call_frequency'];
                $duein[$key] = $row['duein'];
            }
            //array_multisort($frequency, SORT_ASC,$duein, SORT_ASC,$res);
            array_multisort($duein, SORT_ASC, $res);
            $result['data'] = $res;
        }
        return $result;

    }
}
