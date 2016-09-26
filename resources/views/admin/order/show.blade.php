@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading" style="overflow: hidden">View Order  {{ $order->order_id  }}
                 @if($order->status == 'printed')
                <a class="btn btn-warning pull-right" style="margin-left: 40px" href="{{ route('order.pick', ['id' => $order->id] ) }}">Pick Order</a>

                @endif


                <a href="{{ route('order.edit', ['id' => $order->id] ) }}"><button class="btn btn-primary pull-right">Edit Order</button></a>
                </div>



                <div class="panel-body">
                <div class="row">
                    <div class="col-xs-3">Customer ID</div>
                    <p class="col-xs-9">{{ $order->client->client_id }}</p>
                </div>
                <div class="row">

                    <div class="col-xs-3">Customer</div>
                    <p class="col-xs-9">{{ $order->client->name }}</p>
                </div>
                <div class="row">
                    <div class="col-xs-3">Customer parent</div>
                    <p class="col-xs-9">{{ isSet($order->client->clientParent) ? $order->client->clientParent->name : '-' }}</p>
                </div>
                <div class="row">
                    <div class="col-xs-3">Order date</div>
                    <p class="col-xs-9">{{ date('j-m-Y',strtotime($order->modified)) }}</p>
                </div>
                <div class="row">
                    <div class="col-xs-3">Instructions</div>
                    <p class="col-xs-9">{{ $order->instructions }}</p>
                </div>
                <div class="row">
                    <div class="col-xs-3">Total Items Cost</div>
                    <p class="col-xs-9">${{ number_format($totalItemsCost,2) }}</p>
                </div>
                <hr style="clear:both">


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
                    <td>{{ $i->qty }}</td>
                    <td>{{ $i->product_code }}</td>
                    <td>{{ $i->price }}</td>
                    <td>{{ isSet($clientprices[$i->product_code]) ? $clientprices[$i->product_code]->client_price : '-' }}</td>

                </tr>

                @endforeach
                </tbody>
                </table>
                @if($order->exported !='yes')
                <a href="{{ route('order.export', ['id' => $order->id] ) }}"><button class="btn btn-success pull-right">Export Order</button></a>
                @endif

                <a href="{{ route('order.delete', ['id' => $order->id] ) }}"><button class="btn btn-warning pull-left">Delete Order</button></a>


                </div>
            </div>
        </div>
    </div>
</div>

@endsection
