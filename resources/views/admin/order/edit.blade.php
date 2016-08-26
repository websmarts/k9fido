@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Order  {{ $order->order_id  }}</div>



                <div class="panel-body">
                <div class="row">


                    <div class="col-xs-2">Customer ID</div>
                    <p class="col-xs-10">{{ $order->client->client_id }}</p>


                    <div class="col-xs-2">Customer</div>
                    <p class="col-xs-10">{{ $order->client->name }}</p>

                    <div class="col-xs-2">Customer parent</div>
                    <p class="col-xs-10">{{ isSet($order->client->clientParent) ? $order->client->clientParent->name : '-' }}</p>

                    <div class="col-xs-2">Order date</div>
                    <p class="col-xs-10">{{ date('j-m-Y',strtotime($order->modified)) }}</p>

                    <div class="col-xs-2">Instructions</div>
                    <p class="col-xs-10">{{ $order->instructions }}</p>

                    <div class="col-xs-2">Total Items Cost</div>
                    <p class="col-xs-10">${{ number_format($order->totalItemsCost(),2) }}</p>
                </div>
                <hr>
                <div>Ordered Items</div>

                <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order qty</th>
                        <th>Product code</th>
                        <th>Price</th>
                        <th>Client price</th>

                    </tr>
                </thead>
                <tbody>
                @foreach($order->items as $i)
                <tr>
                    <td><input type="text" value="{{ $i->qty }}" /></td>
                    <td>{{ $i->product_code }}</td>
                    <td><input type="text" value="{{ $i->price }}" /></td>
                    <td>{{ isSet($clientprices[$i->product_code]) ? $clientprices[$i->product_code]->client_price : '-' }}</td>

                </tr>

                @endforeach
                </tbody>
                </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
