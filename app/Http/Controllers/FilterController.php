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

        $this->registerFilter($name);

        return $this->redirect();

    }

    /**
     * filterFields for Client index
     * @method product
     * @return [type]  [description]
     */
    private function clients()
    {

        $filterFields = [
            'or' => [
                'clients.name' => [
                    'operater' => 'LIKE',
                    'value_prefix' => '%',
                    'value_postfix' => '%',
                ],
            ],
            'and' => [
                'clients.status' => [
                    'operater' => '=',
                    'value_prefix' => '',
                    'value_postfix' => '',
                    'default_value' => 'active',
                ],
            ],
        ];

        return $filterFields;

    }

    /**
     * filterFields for Product index
     * @method product
     * @return [type]  [description]
     */
    private function products()
    {

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

        return $filterFields;

    }

    /**
     * FilterFileds for Order Index
     * @method order
     * @return [type] [description]
     */
    private function system_orders()
    {

        $filterFields = [
            'or' => [
                'clients.name' => [
                    'operater' => 'LIKE',
                    'value_prefix' => '%',
                    'value_postfix' => '%',
                ],
                'system_orders.order_id' => [
                    'operater' => 'LIKE',
                    'value_prefix' => 'T0_',
                    'value_postfix' => '%',
                ],
            ],
            'and' => [
                'system_orders.status' => [
                    'operater' => '=',
                    'value_prefix' => '',
                    'value_postfix' => '',
                    'default_value' => 'active',
                ],
            ],
            'and2' => [
                'system_orders.exported' => [
                    'operater' => '=',
                    'value_prefix' => '',
                    'value_postfix' => '',
                    'default_value' => 'no',
                ],
            ],
        ];

        return $filterFields;

    }

    /**
     * filterFields for productType
     * @method type
     * @return [type] [description]
     */
    private function type()
    {

        $filterFields = [
            'or' => [
                'name' => [
                    'operater' => 'LIKE',
                    'value_prefix' => '%',
                    'value_postfix' => '%',
                ],

            ],
            'and' => [
                'type.status' => [
                    'operater' => '=',
                    'value_prefix' => '',
                    'value_postfix' => '',
                    'default_value' => 'active',
                ],
            ],
        ];

        return $filterFields;

    }

    private function registerFilter($name)
    {

        $filterKey = env('USER_FILTER_KEY') . $name;

        $filterFields = $this->$name();

        $whereArray = [];

        $filterKeyGroups = $this->request->has('fkey') ? $this->request->input('fkey') : false;
        // eg ['or'=>'or key string']

        if ($this->request->has('remove_filter')) {
            $filterKeyGroups = false;
        }

        // dd($filterKeyGroups);

        if ($filterKeyGroups) {
            foreach ($filterKeyGroups as $fgk => $fgv) {

                if (!empty($fgv)) {
                    // eg fgv = main
                    foreach ($filterFields[$fgk] as $fk => $fv) {
                        // Hack to remove numeric postfix on fgk eg make and2 = and
                        $fgk = preg_replace('/\d/', '', $fgk);

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

        if (count($whereArray)) {
            $data = [
                'filters' => $whereArray,
                'fkey' => $this->request->input('fkey'),
            ];

            //dd($data);

            $this->request->session()->put($filterKey, json_encode($data));
        } else {
            $this->request->session()->forget($filterKey);
        }

        //dd($this->request->session($filterKey));
    }

    private function redirect()
    {
        // remove query string to remove ?page from return uri when we change filters
        $referer = preg_replace('/\?.*/', '', $this->request->headers->get('referer'));

        // redo page which will honor any registered filters
        return redirect($referer);
    }

}
