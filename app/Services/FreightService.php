<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class FreightService
{

    public static function create()
    {
        return new static;
    }

    public function getFreightRates($postcode,$city=false)
    {
        // $postcode = "3038"; //testing value
        $services = ['toll', 'eparcel', 'auspost', 'if', 'hunter'];
        foreach ($services as $service) {
            $data[$service] = $this->getRates($service, $postcode,$city);
        }
        return $data;
    }

    protected function getRates($service, $postcode, $city=false)
    {
        $table = "freight_rates_" . $service;
        if($city){
            $city = strtolower($city);
        }
        
    
        // TODO Frieght rate needs to look for optional city
        $results = \DB::connection('mysql')->select('select * from ' . $table . ' where postcode=?', [$postcode]);

        // TODO return result with city match, ifno match return first result
        if($results && $service == 'if'){
            // check for postcode/city combo
            //echo 'IF Rates for '.$city;
            // search for city match in postcode results
            $options = array();
            foreach($results as $result){
                $options[$result->city] = $result;
            }
            if(array_key_exists($city,$options)){
                return $options[$city];
            } // else fall through to select the first result to return
            
        }

        if ($results && isset($results[0])) {
            return $results[0];
        }
    }

    // Legacy system for Toll only code
    public function getFreightCode($postcode)
    {
        $zones = \DB::connection('k9homes')
            ->select("select `zone` from freight_zones where `pcode`='{$postcode}' limit 1");
        //dd($zones);
        // echo dumper ($res);

        //$local = do_query('select * from freight_localzones where `zone` ="'.$res[0]['zone'].'"');
        if (isset($zones[0]) && isset($zones[0]->zone)) {
            $local = \DB::connection('k9homes')
                ->select("select * from freight_localzones where `zone` ='{$zones[0]->zone}'");

            return [$zones[0]->zone, !empty($local) ? 'local' : 'not local'];
        }

    }

}
