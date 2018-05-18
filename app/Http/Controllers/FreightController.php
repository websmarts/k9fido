<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;

class FreightController extends Controller
{
    public $postcodes = [];
    public function __construct()
    {
        $postcodeResults = \DB::select('select postcode from postcodes');

        foreach ($postcodeResults as $p) {
            $this->postcodes[(int) $p->postcode] = true;
        }

    }

    public function index($postcode = "3000")
    {
        $this->loadTollRates();
        $this->loadEparcelRates();
        $this->loadAuspostRates();
        $this->loadIFRates();
        $this->loadTmccRates();
        dd('done');
    }

    protected function loadTmccRates()
    {

        //dd($postcodes);
        // find the zone and rates for every postcode
        //Load the zones table
        $ratesFilePath = storage_path("imports/freight/tmcc/rates.xlsx");
        $ratesdata = Excel::load($ratesFilePath, function ($reader) {
        })->get();
        // dd($ratesdata);

        $table = "freight_rates_tmcc";
        \DB::delete('delete from ' . $table);

        if (!empty($ratesdata) && $ratesdata->count()) {
            foreach ($ratesdata as $key => $value) {
                $postcode = (string) (int) $value->postcode; // strip trailing .0  ??
                if (!isset($this->postcodes[$postcode])) {
                    //\Log::info('Skipping:' . $postcode);
                    continue;
                }

                \DB::insert('insert into ' . $table . ' (postcode,rate1,rate2,rate3) values (?,?,?,?)',
                    [
                        $postcode,
                        $value->rate1 * 100,
                        $value->rate2 * 100,
                        $value->rate3 * 100,
                    ]);
            }
        }
    }
    protected function loadIfRates()
    {

        //dd($postcodes);
        // find the zone and rates for every postcode
        //Load the zones table
        $ratesFilePath = storage_path("imports/freight/if/rates.xlsx");
        $ratesdata = Excel::load($ratesFilePath, function ($reader) {
        })->get();
        // dd($ratesdata);

        $table = "freight_rates_if";
        \DB::delete('delete from ' . $table);

        if (!empty($ratesdata) && $ratesdata->count()) {
            foreach ($ratesdata as $key => $value) {
                $postcode = (string) (int) $value->postcode; // strip trailing .0  ??
                if (!isset($this->postcodes[$postcode])) {
                    //\Log::info('Skipping:' . $postcode);
                    continue;
                }

                \DB::insert('insert into ' . $table . ' (postcode,rate1,rate2,rate3) values (?,?,?,?)',
                    [
                        $postcode,
                        $value->rate1 * 100,
                        $value->rate2 * 100,
                        $value->rate3 * 100,
                    ]);
            }
        }
    }

    protected function loadAuspostRates()
    {

        //dd($postcodes);
        // find the zone and rates for every postcode
        //Load the zones table
        $ratesFilePath = storage_path("imports/freight/ap/rates.xlsx");
        $ratesdata = Excel::load($ratesFilePath, function ($reader) {
        })->get();
        //dd($ratesdata);

        $table = "freight_rates_auspost";
        \DB::delete('delete from ' . $table);

        if (!empty($ratesdata) && $ratesdata->count()) {
            foreach ($ratesdata as $key => $value) {

                $postcodeRanges = explode(',', str_replace("\n", '', $value->postcodes));
                //dd($postcodeRanges);
                foreach ($postcodeRanges as $range) {
                    //\Log::info('Processing Range:' . $range);
                    $rangeValues = explode('-', $range);

                    // left trim any leading 0 from postcodes
                    array_walk($rangeValues, function (&$item) {
                        $item = ltrim($item, "0");
                    });
                    //dd($rangeValues);

                    if (count($rangeValues) == 2) {
                        // its a range of postcodes
                        $rangeStart = $rangeValues[0];
                        $rangeEnd = $rangeValues[1];

                        //\Log::info('Start of Range:' . $rangeStart . ' End of range:' . $rangeEnd);
                        for ($i = (int) $rangeStart; $i <= (int) $rangeEnd; $i++) {

                            if (!isset($this->postcodes[$i])) {
                                //\Log::info('Skipping:' . $i);
                                continue;
                            }
                            // \Log::info('Inserting:' . $i);
                            \DB::insert('insert into ' . $table . ' (postcode,base_rate,per_kg) values (?,?,?)', [(string) $i, $value->basic_charge * 100, $value->per_kg * 100]);

                        }
                    } else {
                        //its a single postcode
                        //Add a single entry to freight_rates_eparcel table
                        // \Log::info('Inserting (single):' . $rangeValues[0]);
                        $postcode = $rangeValues[0]; // strip leading 0 ??

                        \DB::insert('insert into ' . $table . ' (postcode,base_rate,per_kg) values (?, ?,?)', [$postcode, $value->basic_charge * 100, $value->per_kg * 100]);

                    }
                }
            }
        }
    }

    protected function loadEparcelRates()
    {
        // path to rates input file
        $ratesFilePath = storage_path("imports/freight/eparcel/rates.xlsx");
        //dd($ratesFilePath);
        $data = Excel::load($ratesFilePath, function ($reader) {
        })->get();

        //dd($data);
        $rates = [];
        if (!empty($data) && $data->count()) {
            foreach ($data as $key => $value) {
                $rates[substr($value->destination_zone, 0, strpos($value->destination_zone, ' '))] = [
                    'base' => $value->base * 100,
                    'per_kg' => $value->per_kg * 100,
                ];
            }
        }
        // dd($rates); //

        //\Log::info('postcode array created');
        //dd($postcodes);
        // find the zone and rates for every postcode
        //Load the zones table
        $zonesFilePath = storage_path("imports/freight/eparcel/zones.xlsx");
        $zonedata = Excel::load($zonesFilePath, function ($reader) {
        })->get();
        //dd($zonedata);
        $table = "freight_rates_eparcel";
        \DB::delete('delete from ' . $table);
        $done = [];
        if (!empty($zonedata) && $zonedata->count()) {
            foreach ($zonedata as $key => $value) {
                $zone = $value->zone;
                $postcodeRanges = explode(',', str_replace("\n", '', $value->postcodes));
                //dd($postcodeRanges);
                foreach ($postcodeRanges as $range) {
                    // \Log::info('Processing Range:' . $range);
                    $rangeValues = explode('-', $range);
                    //dd($rangeValues);

                    if (count($rangeValues) == 2) {
                        // its a range of postcodes
                        // \Log::info('Start of Range:' . $rangeValues[0] . ' End of range:' . $rangeValues[1]);
                        for ($i = (int) $rangeValues[0]; $i <= (int) $rangeValues[1]; $i++) {

                            if (!isset($this->postcodes[$i])) {
                                //\Log::info('Skipping:' . $i);
                                continue;
                            }
                            // \Log::info('Inserting:' . $i);
                            \DB::insert('insert into ' . $table . ' (postcode,base_rate,per_kg) values (?,?,?)', [(string) $i, (int) $rates[$zone]['base'], (int) $rates[$zone]['per_kg']]);

                        }
                    } else {
                        //its a single postcode
                        //Add a single entry to freight_rates_eparcel table
                        // \Log::info('Inserting (single):' . $rangeValues[0]);
                        \DB::insert('insert into ' . $table . ' (postcode,base_rate,per_kg) values (?, ?,?)', [$rangeValues[0], $rates[$zone]['base'], $rates[$zone]['per_kg']]);

                    }

                }

            }

        }

    }

    protected function loadTollRates()
    {
        // Load Toll_rates table
        // The figures below are manually populated from Toll spreadsheet

        // Basic
        $MEL1[0] = [591, 1181, 1769, 2358];
        $MEL2[0] = [791, 1573, 2358, 3139];
        $IVIC[0] = [983, 1966, 2942, 3923];
        $OVIC[0] = [1376, 2748, 4118, 5488];
        // With Fuel levy
        $MEL1[1] = [665, 1329, 1990, 2653];
        $MEL2[1] = [890, 1770, 2653, 3531];
        $IVIC[1] = [1106, 2212, 3310, 4413];
        $OVIC[1] = [1548, 3092, 4633, 6174];
        // With Fuel and GST
        $MEL1[2] = [731, 1461, 2189, 2918];
        $MEL2[2] = [979, 1947, 2918, 3885];
        $IVIC[2] = [1216, 2433, 3641, 4855];
        $OVIC[2] = [1703, 3401, 5096, 6791];

        $zones = ['MEL1', 'MEL2', 'IVIC', 'OVIC'];
        $table = "freight_rates_toll";
        \DB::delete('delete from ' . $table);
        foreach ($zones as $zone) {
            $postcodes = \DB::connection('k9homes')->select('select pcode from freight_zones where zone=? limit 10', [$zone]);

            foreach ($postcodes as $postcode) {
                \DB::insert('insert into freight_rates_toll
                    (postcode,rate1_1,rate1_2,rate1_3,rate1_4,rate2_1,rate2_2,rate2_3,rate2_4,rate3_1,rate3_2,rate3_3,rate3_4) values (?, ?,?,?,?, ?,?,?,?, ?,?,?,?)',
                    [(string) $postcode->pcode,
                        ${$zone}[0][0],
                        ${$zone}[0][1],
                        ${$zone}[0][2],
                        ${$zone}[0][3],

                        ${$zone}[1][0],
                        ${$zone}[1][1],
                        ${$zone}[1][2],
                        ${$zone}[1][3],

                        ${$zone}[2][0],
                        ${$zone}[2][1],
                        ${$zone}[2][2],
                        ${$zone}[2][3],

                    ]);
            }
        }

    }

}
