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

        $clients = Client::lists('name', 'client_id')->toArray();

        return view('admin.client.create', compact('client', 'salesreps', 'clients', 'client'));
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

        $clients = Client::lists('name', 'client_id')->toArray();

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

        $this->validate($request, [
            'name' => 'required',
            'state' => 'required',
        ]);

        $data = $request->except('_method', '_token');
        $data['modified'] = date("Y-m-d H:i:s");

        $client = Client::find($id); //->update($data);

        $client->update($data);

        // Update user clients table
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
        $this->validate($request, ['name' => 'required']);
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

}
