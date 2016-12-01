@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Order  {{ $order->order_id  }}</div>



                <div class="panel-body">

                {{ Form::open( ['route' => ['order.update', $order->id], 'method'=>'put'] ) }}



                <div class="row">


                    <div class="col-xs-2"><label>Customer ID</label></div>
                    <p class="col-xs-10">{{ $order->client->client_id }}</p>


                    <div class="col-xs-2"><label>Customer</label></div>
                    <p class="col-xs-10">{{ $order->client->name }}</p>

                    <div class="col-xs-2"><label>Customer parent</label></div>
                    <p class="col-xs-10">{{ isSet($order->client->clientParent) ? $order->client->clientParent->name : '-' }}</p>

                    <div class="col-xs-2"><label>Order date</label></div>
                    <p class="col-xs-10">{{ date('j-m-Y',strtotime($order->modified)) }}</p>

                    <div class="col-xs-2"><label>Order contact</label></div>
                    <p class="col-xs-10">{{ $order->order_contact }}&nbsp;</p>

                    <div class="col-xs-2"><label>Instructions</label></div>
                    <p class="col-xs-10">{{ $order->instructions }}&nbsp;</p>

                    <div class="col-xs-2"><label>Total Items Cost</label></div>
                    <p class="col-xs-10">${{ number_format($totalItemsCost,2) }} </p>
                </div>

                <div class="row" style="margin-top:15px;">
                    <div class="col-xs-2"><label>Order status</label></div>
                    <div class="col-md-3 col-xs-10">
                    {{ Form::select('status',Appdata::get('order.status.options'),$order->status, ['class'=>'form-control']) }}
                    </div>
                </div>

                 <div class="row" style="margin-top:15px;">
                    <div class="col-xs-2"><label>Exported status</label></div>
                    <div class="col-md-3 col-xs-10">
                    {{ Form::select('exported',Appdata::get('order.exported.options'),$order->exported, ['class'=>'form-control']) }}
                    </div>
                </div>

                <div class="row" style="margin-top:15px;">
                    <div class="col-xs-2"><label>Freight charge (00.00 dollars)</label></div>
                    <div class="col-md-3 col-xs-10">
                    {{ Form::text('freight_charge',$order->freight_charge, ['class'=>'form-control']) }}
                    </div>
                </div>

                <hr>

                <div>Ordered Items</div>

                <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order qty</th>
                        <th>Supplied qty</th>
                        <th>Product code</th>
                        <th>Price</th>
                        <th>Client price</th>

                    </tr>
                </thead>
                <tbody>
                @foreach($order->items as $i)
                <tr>
                    <td><input type="text" name="items[{{$i->id}}][qty]" value="{{$i->qty}}"/></td>
                    <td><input type="text" name="items[{{$i->id}}][qty_supplied]" value="{{$i->qty_supplied}}"/></td>
                    <td><input type="text" name="items[{{$i->id}}][product_code]" value="{{$i->product_code}}"/></td>
                    <td><input type="text" name="items[{{$i->id}}][price]" value="{{$i->price}}"/></td>
                    <td>{{ isSet($clientprices[$i->product_code]) ? $clientprices[$i->product_code]->client_price : '-' }}</td>

                </tr>

                @endforeach
                <tr><td colspan="4">New order line:</td></tr>
                <tr>
                    <td><input type="text" name="items[-1][qty]" value=""/></td>
                    <td><input type="text" name="items[-1][qty_supplied]" value=""/></td>
                    <td><input type="text" name="items[-1][product_code]" value=""/></td>
                    <td><input type="text" name="items[-1][price]" value=""/></td>
                    <td>&nbsp;</td>

                </tr>

                </tbody>
                </table>



                <button type="submit" value="Update" class="btn btn-primary">Update</button>
                {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
