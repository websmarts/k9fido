<?php

namespace App\Http\Controllers;

use App\Legacy\Client\Client;
use App\Legacy\Staff\User;
use App\Legacy\Staff\UserClient;
use App\Queries\ClientListQuery;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $clients = ClientListQuery::perform(12); // set rows_per_page

        return view('admin.client.index')->with('clients', $clients);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client = new Client;

        $salesreps = $this->getSalesReps();

        $clients = $this->clientsList();

        return view('admin.client.create', compact('client', 'salesreps', 'clients', 'client'));
    }

    protected function clientsList(){
        return  Client::lists('name', 'client_id')->sort()->toArray();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::find($id);

        $salesreps = $this->getSalesReps();

        $clients =$this->clientsList();

        return view('admin.client.edit', compact('client', 'salesreps', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        /**
         * Check for the Delete button
         */
        if (strtolower($request->delete_key) == 'fido' && strtolower($request->b) == 'delete') {
            $mergeCompanyId = (int)$request->merge_company;

            if ($mergeCompanyId) {
                return $this->mergeCompanies($id, $mergeCompanyId);
            }
            return $this->deleteCompany($id);
        }



        $this->validate($request, [
            'name' => 'required',
            'state' => 'required',
            'postcode' => 'required'
        ]);

        $data = $request->except('_method', '_token');
        $data['modified'] = date("Y-m-d H:i:s");



        $client = Client::find($id); //->update($data);

        $client = $client->update($data);

        // Update user clients table
        // Not sure why we do this because the concept of a 
        // Client having more than one sales rep has never
        // been implmented anywhere I can think of in FIDO or eCat
        $this->updateRepClientsList($id, $data['salesrep']);

        flash('Client updated', 'success');
        return redirect()->route('client.edit', $id);
    }

    private function updateRepClientsList($clientId, $salesRepId)
    {

        // if $salesrepId is zero then remove any current entry
        if ($salesRepId == 0) {
            UserClient::where('client_id', $clientId)->delete();
            return;
        }

        // if client_id / salesrep_id pair exists do nothing
        $res = UserClient::where('client_id', $clientId)->first();


        if (!is_null($res)) {
            if ($res->salesrep_id == $salesRepId) {
                return;
            }
            // if client_id / salesrep_id pair is incorrect then update
            if ($res->salesrep_id != $salesRepId) {
                $res->salesrep_id = $salesRepId;
                $res->save();
                return;
            }
        }

        // if client_id does not have an entry then create one
        if (is_null($res)) {
            $userClient = new UserClient;
            $userClient->client_id = $clientId;
            $userClient->salesrep_id = $salesRepId;
            $userClient->save();
        }
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'state' => 'required',
            'postcode' => 'required'
        ]);
        //dd($request->all());
        $client = Client::updateOrCreate($request->except('_token'));

        // Update user clients table
        $this->updateRepClientsList($client->client_id, $request->salesrep);

        flash('Client created', 'success');
        return redirect()->route('client.edit', $client->client_id);
    }

    private function getSalesReps()
    {
        $users = User::where('role', 'rep')->select('id', 'firstname', 'lastname')->get();

        $salesreps = [];
        $users->each(function ($item, $key) use (&$salesreps) {
            if (!empty($item->firstname)) {
                $salesreps[$item->id] = $item->firstname . ' ' . $item->lastname;
            }
        });
        return $salesreps;
    }

    private function deleteCompany($id)
    {
        // Delete system orders and system order items
        $sql = '    DELETE system_orders,system_order_items
                    FROM system_orders
                    INNER JOIN system_order_items ON system_orders.order_id=system_order_items.order_id
                    WHERE system_orders.client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        $sql = '    DELETE FROM system_orders where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        // Delete from client_prices table
        $sql = '    DELETE FROM client_prices where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        // Delete from clientstock table
        $sql = '    DELETE FROM clientstock where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        // Delete form contact_history table
        $sql = '    DELETE FROM contact_history where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        // Delete from notify_me table
        $sql = '    DELETE FROM notify_me where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        // Delete from user_clients table
        $sql = '    DELETE FROM user_clients where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        // Delete Client
        $sql = '    DELETE FROM clients where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        flash('Client deleted', 'success');
        return redirect()->route('client.index');
    }

    private function mergeCompanies($id, $mergeToId)
    {

        $client1 = Client::find($id);
        $client2 = Client::find($mergeToId);

        // Update system orders
        $sql = '   UPDATE system_orders SET client_id=? WHERE client_id=?';
        \DB::connection('k9homes')->update($sql, [$mergeToId, $id]);

        // Update from client_prices table
        $sql = '    DELETE FROM client_prices where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        // Delete from clientstock table
        $sql = '    DELETE FROM clientstock where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        // Delete form contact_history table
        $sql = '    DELETE FROM contact_history where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        // Delete from notify_me table
        $sql = '    DELETE FROM notify_me where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        // Delete from user_clients table
        $sql = '    DELETE FROM user_clients where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        // Delete Client
        $sql = '    DELETE FROM clients where client_id=' . $id;
        \DB::connection('k9homes')->delete($sql);

        flash('Success - merged ' . $client1->name . ' with ' . $client2->name, 'success');
        return redirect()->route('client.index');
    }
}
