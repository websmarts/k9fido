<?php

namespace App\Http\Controllers;

use App\Legacy\Client\Client;
use App\Legacy\Product\ClientPrice;
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

        $clients = ClientListQuery::perform(12);

        return view('admin.client.index')->with('clients', $clients);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        return view('admin.client.edit')->with('client', $client);
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

        $this->validate($request, ['name' => 'required']);

        $data = $request->except('_method', '_token');
        $data['modified'] = date("Y-m-d H:i:s");

        $client = Client::find($id); //->update($data);

        $client->update($data);

        flash('Client updated', 'success');
        return redirect()->route('client.edit', $id);

    }

    public function pricing($clientId)
    {
        $prices = ClientPrice::with('product')
            ->where('client_id', $clientId)
            ->get();
        $client = Client::find($clientId);
        return view('admin.client.pricing', compact('prices', 'client'));
    }

    public function storePricing(Request $request)
    {
        dd($request->all());
    }

}
