<?php
namespace App\Services;

class FreightService
{

    public static function create()
    {
        return new static;
    }

    public function getFreightRates($postcode)
    {
        // $postcode = "3038"; //testing value
        $services = ['toll', 'eparcel', 'auspost', 'if', 'tmcc'];
        foreach ($services as $service) {
            $data[$service] = $this->getRates($service, $postcode);
        }
        return $data;
    }

    protected function getRates($service, $postcode)
    {
        $table = "freight_rates_" . $service;
        $results = \DB::connection('mysql')->select('select * from ' . $table . ' where postcode=?', [$postcode]);

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
