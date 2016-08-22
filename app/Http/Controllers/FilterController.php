<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilterController extends Controller
{

    /**
     * [$request description]
     * @var [type]
     */
    protected $request;

    /**
     * session key to store user search filters
     *
     **/
    protected $sessionKey = false;

    /**
     * The key used in session to store filter info under
     *
     * @var string
     */
    protected $filterKey = '';

    // public function __construct(Request $request)
    // {
    //     $this->request = $request;
    //     $this->sessionKey = env('USER_FILTER_KEY');
    // }

/**
 * @param  Request
 * @param  [type]
 * @return [type]
 */
    public function index(Request $request, $name)
    {
        $this->request = $request;
        $this->sessionKey = env('USER_FILTER_KEY');

        $this->filterKey = $this->sessionKey . '_' . $name;

        if (method_exists($this, $name)) {
            $this->$name();

            // remove query string to remove ?page from return uri when we change filters
            $referer = preg_replace('/\?.*/', '', $this->request->headers->get('referer'));

            return redirect($referer);

        }
    }

/**
 * @method product
 * @return [type]
 */
    private function product()
    {

        $whereArray = [];

        $filterFields = [
            'or' => [
                'product_code' => [
                    'operater' => 'LIKE',
                    'value_prefix' => '%',
                    'value_postfix' => '%',
                ],
                'description' => [
                    'operater' => 'LIKE',
                    'value_prefix' => '%',
                    'value_postfix' => '%',
                ],
            ],
            'and' => [
                'status' => [
                    'operater' => '=',
                    'value_prefix' => '',
                    'value_postfix' => '',
                ],
            ],
        ];

        $filterKeyGroups = $this->request->has('fkey') ? $this->request->input('fkey') : false;

        if ($this->request->has('remove_filter')) {
            $filterKeyGroups = false;
        }

        // dd($filterKeyGroups);

        if ($filterKeyGroups) {
            foreach ($filterKeyGroups as $fgk => $fgv) {
                if (!empty($fgv)) {
                    // eg fgv = main
                    foreach ($filterFields[$fgk] as $fk => $fv) {
                        $whereArray[$fgk][] = [
                            $fk,

                            $fv['operater'],

                            $fv['value_prefix'] .
                            $fgv .
                            $fv['value_postfix'],
                        ];

                    }
                }

            }

        }

        $data = [
            'filters' => $whereArray,
            'fkey' => $this->request->input('fkey'),
        ];

        if (count($whereArray)) {
            $this->request->session()->put($this->filterKey, json_encode($data));
        } else {
            $this->request->session()->forget($this->filterKey);
        }

        //dd($this->request->session($this->filterKey));

    }

    private function type()
    {

        $whereArray = [];

        $filterFields = [
            'or' => [
                'name' => [
                    'operater' => 'LIKE',
                    'value_prefix' => '%',
                    'value_postfix' => '%',
                ],
            ],
        ];

        $filterKeyGroups = $this->request->has('fkey') ? $this->request->input('fkey') : false;

        if ($this->request->has('remove_filter')) {
            $filterKeyGroups = false;
        }

        // dd($filterKeyGroups);

        if ($filterKeyGroups) {
            foreach ($filterKeyGroups as $fgk => $fgv) {
                if (!empty($fgv)) {
                    // eg fgv = main
                    foreach ($filterFields[$fgk] as $fk => $fv) {
                        $whereArray[$fgk][] = [
                            $fk,

                            $fv['operater'],

                            $fv['value_prefix'] .
                            $fgv .
                            $fv['value_postfix'],
                        ];

                    }
                }

            }

        }

        $data = [
            'filters' => $whereArray,
            'fkey' => $this->request->input('fkey'),
        ];

        if (count($whereArray)) {
            $this->request->session()->put($this->filterKey, json_encode($data));
        } else {
            $this->request->session()->forget($this->filterKey);
        }

        //dd($this->request->session($this->filterKey));

    }

}
