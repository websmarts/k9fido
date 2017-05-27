<?php

namespace App\Http\Controllers;

class FreightCalculatorController extends Controller
{
    public function index()
    {
        // return the Freight Calc Enquiry Form
        return view('admin.freight.index');
    }

    /**
     * Receives a post ajax request with package info
     * and returns a list of quotes
     * @method calc
     * @return [type] [description]
     */
    public function quote()
    {
        $to['postcode'] = request('postcode');
        $to['suburb'] = request('suburb');
        $to['state'] = request('statecode');

        $packages = request('packages');
        $quotes = [];

        $companies = ['hunter' => 'Hunter', 'auspost' => 'AusPost', 'ipec' => 'IPEC/Toll']; //'hunter', 'auspost','ipec'];
        foreach ($companies as $company => $companyName) {

            foreach ($packages as $package) {

                switch ($company) {
                    case 'hunter':
                        if ($this->validPackage($company, $package)) {
                            $quoteOptions = $this->hunter($to, $package);
                            foreach ($quoteOptions as $quote) {
                                $quotes[] = $quote;
                            }
                        }
                        break;
                    case 'auspost':
                        if ($this->validPackage($company, $package)) {
                            $quoteOptions = $this->auspost($to, $package);
                            foreach ($quoteOptions as $quote) {
                                $quotes[] = $quote;
                            }
                        }
                        break;
                    case 'ipec':
                        if ($this->validPackage($company, $package)) {
                            $quoteOptions = $this->ipec($to, $package);
                            foreach ($quoteOptions as $quote) {
                                $quotes[] = $quote;
                            }
                        }
                        break;

                } // end switch

            } // end foreach package

        } // end foreach company

        // check each company could handle all packages in shipment

        $data['quotes'] = $this->formatQuotes($quotes);
        $data['companies'] = $companies;
        $data['response'] = 200;

        return $data;
    }
    private function formatQuotes($quotes)
    {
        // create an structure with
        // -company
        // ---method
        // -----packages
        $r = [];
        foreach ($quotes as $q) {
            $r[$q['company']][$q['method']][$q['package_id']] = $q;
        }
        return $r;
    }
    private function validPackage($company, $package)
    {
        $method = 'valid' . ucfirst(strtolower($company)) . 'Package';
        if (!method_exists(__CLASS__, $method)) {

            return false; // no package validator for company
        }

        return $this->$method($package);
    }

    private function validHunterPackage($package)
    {
        return true;
    }
    private function validAuspostPackage($package)
    {
        return true;
    }
    private function validIpecPackage($package)
    {
        return false;
    }

    public function ipec($to, $package)
    {
        return [];
    }

    public function hunter($to, $package)
    {
        $url = 'https://sandbox.hunterexpress.com.au/sandbox/rest/hxws/quote/get-quote';
        $url = 'https://sandbox.hunterexpress.com.au:443/sandbox/rest/hxws/quote/get-quote';
        $username = 'hxws';
        $password = 'hxws';
        $data = '{
						"customerCode": "DUMMY",
						"fromLocation": {
						 "suburbName": "HEIDELBERG HEIGHTS",
						 "postCode": "3081",
						 "state": "VIC"
						},
						"toLocation": {
						 "suburbName": "' . $to['suburb'] . '",
						 "postCode": "' . $to['postcode'] . '",
						 "state": "' . $to['state'] . '"
						},
						"goods": [
						 {
						   "pieces": "1",
						   "weight": "' . $package['weight'] . '",
						   "width": "' . $package['width'] . '",
						   "height": "' . $package['height'] . '",
						   "depth": "' . $package['length'] . '",
						   "typeCode": "ENV"
						 }
						]
					}';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: Basic ' . base64_encode($username . ':' . $password)));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        list($header, $body) = explode("\r\n\r\n", $response, 2);

        // extract any quote options
        $options = json_decode($body);

        //dd($options);

        $quotes = [];
        foreach ($options as $o) {
            $quote['package_id'] = $package['id'];
            $quote['company'] = 'hunter';
            //$quote['serviceCode'] = $o->serviceCode;
            $quote['method'] = $o->serviceName;
            $quote['cost'] = $o->fee;
            // $quote['etd'] = $o->etd;
            $quotes[] = $quote;

        }

        return $quotes;
    }

    public function auspost($to, $package)
    {
        // Set your API key: remember to change this to your live API key in production
        // $apiKey = 'feb23189-2402-4b4c-a1ed-d4e6f80b195f'; ians k9homes version
        $apiKey = '5027ff81-f78d-4b09-b796-0e91d8b42510'; // Darrens account key

        // Check for sme max allowances eg max weight for auspost=22kg

        // Define the service input parameters
        $fromPostcode = '3081';
        $toPostcode = $to['postcode'];
        $parcelLengthInCMs = $package['length'];
        $parcelWidthInCMs = $package['width'];
        $parcelHeighthInCMs = $package['height'];
        $parcelWeightInKGs = $package['weight'];

        // Set the query params
        $queryParams = array(
            "from_postcode" => $fromPostcode,
            "to_postcode" => $toPostcode,
            "length" => $parcelLengthInCMs,
            "width" => $parcelWidthInCMs,
            "height" => $parcelHeighthInCMs,
            "weight" => $parcelWeightInKGs,
        );

        // Set the URL for the Domestic Parcel Size service
        $urlPrefix = 'digitalapi.auspost.com.au';
        $postageTypesURL = 'https://' . $urlPrefix . '/postage/parcel/domestic/service.json?' .
        http_build_query($queryParams);

        $ch = curl_init();
        // Lookup available domestic parcel delivery service types
        curl_setopt($ch, CURLOPT_URL, $postageTypesURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('AUTH-KEY: ' . $apiKey));
        $response = curl_exec($ch);
        // Check the response; if the body is empty then an error has occurred
        if (!$response) {
            die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        }

        // extract any quote options
        $options = json_decode($response);

        $quotes = [];

        if (isset($options->error)) {
            return $quotes;
        }

        foreach ($options->services->service as $o) {
            $quote['package_id'] = $package['id'];
            $quote['company'] = 'auspost';
            //$quote['serviceCode'] = $o->serviceCode;
            $quote['method'] = $o->name;
            $quote['cost'] = (float) $o->price;
            // $quote['etd'] = $o->etd;
            $quotes[] = $quote;

        }

        return $quotes;
    }

    public function searchForLocation()
    {
        $postcode = urlencode(request('postcode'));
        //your Auspost API
        $apiKey = 'd1e6aecc-8485-4e62-9227-7c2dd3f78600'; // K9 registered key
        // Set the URL for the Postcode search
        $urlPrefix = 'auspost.com.au';
        $parcelTypesURL = 'http://' . $urlPrefix . '/api/postcode/search.json?q=' . $postcode . '&excludePostBoxFlag=true';
        // Lookup postcode
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $parcelTypesURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('AUTH-KEY: ' . $apiKey));
        $rawBody = curl_exec($ch);
        // Check the response: if the body is empty then an error occurred
        if (!$rawBody) {
            die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        } else {
            $results = json_decode($rawBody);
            //dd($results);
            if (isset($results->localities) && isset($results->localities->locality)) {
                $res = [];
                if (is_array($results->localities->locality)) {
                    foreach ($results->localities->locality as $location) {
                        $res[] = ['value' => $location->postcode . ', ' . $location->location . ', ' . $location->state];
                    }
                } else {
                    $res[] = ['value' => $results->localities->locality->postcode . ', ' . $results->localities->locality->location . ', ' . $results->localities->locality->state];
                }

                return $res;
            } else {
                return [['value' => 'no match found...']];
            }

        }
    }

}
