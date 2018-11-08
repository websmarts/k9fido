<?php

namespace App\Http\Controllers;

use App\Legacy\Client\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DBUpdateController extends Controller
{
    public function index()
    {
        // import the import file
        $importFilePath = storage_path("imports/salesrep_updates.xlsx");
        $importdata = Excel::load($importFilePath, function ($reader) {
        })->get();
        // dd($importdata);

        if (!empty($importdata) && $importdata->count()) {
            foreach ($importdata as $key => $value) {

                //dd($value);

                $clientId = (int) $value->client_id;
                $salesrep = (int) $value->salesrep;
                if($salesrep ==9){
                    $salesrep = 13;
                }
                $data = [
            
                    'level' => $value->level,
                    'salesrep' => $salesrep,
                    'call_frequency' => (int) $value->call_frequency
                ];
                
                $client = Client::find($clientId);

                $client->update($data);
             
            }
        }

    }

}