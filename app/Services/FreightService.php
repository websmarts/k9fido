<?php
namespace App\Services;

class FreightService
{

    public static function create()
    {
        return new static;
    }

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
