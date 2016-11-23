@extends('layouts.app')

@section('content')
<div class="container order_container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading" style="overflow: hidden">Purchase Order:  {{ $order->order_id  }}
                 @if($order->status == 'printed')
                <a class="btn btn-warning pull-right hidden-print" style="margin-left: 40px" href="{{ route('order.pick', ['id' => $order->id] ) }}">Pick Order</a>

                @endif


                <a class="hidden-print" href="{{ route('order.edit', ['id' => $order->id] ) }}"><button class="btn btn-primary pull-right">Edit Order</button></a>
                </div>



                <div class="panel-body">
{{-- dump($freight) --}}
                <div class="row hidden-print">
                    <div class="col-xs-3">Customer ID</div>
                    <p class="col-xs-9">{{ $order->client->client_id }}</p>
                </div>
                <div class="row">
                    <div class="col-xs-3">Freight</div>
                    <p class="col-xs-9">{{ $freight->code }}
                    @if(isSet($freight->notes))
                    <br /> {{ $freight->notes }}
                    @endif
                    </p>
                </div>
                {{-- dump($order->client) --}}
                <div class="row">

                    <div class="col-xs-3">Customer</div>
                    <p class="col-xs-9">{{ $order->client->name }}<br />
                    {{ $order->client->address1 }}, {{ $order->client->city }}, {{ $order->client->postcode }} Ph {{ $order->client->phone }}</p>
                </div>
                <div class="row">
                    <div class="col-xs-3">Customer parent</div>
                    <p class="col-xs-9">{{ isSet($order->client->parentGroup) ? $order->client->parentGroup->name : '-' }}</p>
                </div>
                <div class="row">
                    <div class="col-xs-3">Order date</div>
                    <p class="col-xs-9">{{ date('g:h a, l jS F Y',strtotime($order->modified)) }}
                    @if($order->salesRep != null)
                     &nbsp;( Refer: {{ $order->salesRep->firstname .', '.$order->salesRep->lastname }} )
                    @endif


                     </p>
                </div>

                <hr class="hidden-print" style="clear:both">




                @if($order->exported !='yes')
                <a class="hidden-print" href="{{ route('order.export', ['id' => $order->id] ) }}"><button class="btn btn-success pull-right">Export Order</button></a>
                @endif

                <a class="hidden-print" href="{{ route('order.delete', ['id' => $order->id] ) }}"><button class="btn btn-warning pull-left">Delete Order</button></a>


                </div>
            </div>
        </div>
    </div>
     <div>Ordered Items</div>
{{-- dump($items) --}}

                <table class="table table-striped table-condensed order_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product code</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>P/O qty</th>
                        <th>UnitPrice</th>
                        <th>Ext price</th>
                        <th>Markup</th>
                        <th>Custom %</th>
                    </tr>
                </thead>
                <tbody>
<?php
$n = 1;

?>

                @foreach($items as $i)
                <?php $customDiscount = '';?>
                <tr>
                    <td>{{ $n++ }}</td>
                    <td>{{ $i->product_code }} - {!! $i->product->description !!}</td>
                    <td>{{ $i->product->color_name }}</td>
                    <td>{{ $i->product->size }}</td>
                    <td>{{ $i->qty_supplied }}/{{ $i->qty }}</td>
                    <td>{{ number_format($i->price/100,2) }}

                    </td>
                    <td>{{ number_format($i->ext_price/100,2) }}


                    <td>{{ $i->markup != 0 ? number_format($i->markup * 100,1).'%' : '' }}</td>
                    <td>{{ $i->custom_discount }}</td>

                </tr>
                @endforeach
                <tr>
                <td colspan="5"  align="left" ><br>Order Contact: <span style="color: #00f">{{ $order->order_contact }}</span><br>Order instructions:<br><span class="orderinstructions">{!! nl2br($order->instructions) !!}</span></td><td aligne="right"><span style="font-weight: bold" >Order total :</span></td>
                <td colspan="2"><p style="font-weight: bold" align="left">${{ number_format($totalItemsPrice/100,2) }}</p></td>
                <td colspan="2">&nbsp;</td>
                </tr>

                <tr><td align="right" colspan="7">{{ number_format(($totalItemsPrice-$totalItemsCost)*100/$totalItemsPrice,1) }}%<br /><i>note:<br /> #q = qty discount<br /> #s = Special client price <br /> #c Custom price entered </i> </td><td colspan="2">&nbsp;</td></tr>
                </tbody>
                </table>

                <img class="freight-docket visible-print" style="page-break-before: always" src="{{ asset('images/freight_docket.jpg') }}" />
</div>

@endsection
