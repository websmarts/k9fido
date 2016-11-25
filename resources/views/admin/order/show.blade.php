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
                    <div class="col-xs-2">Customer ID</div>
                    <p class="col-xs-9">{{ $order->client->client_id }}</p>
                </div>

                {{-- dump($order->client) --}}
                <div class="row">

                    <div class="col-xs-2">Customer</div>
                    <p class="col-xs-9"><strong>{{ $order->client->name }}</strong><br />
                    {{ $order->client->address1 }}, {{ $order->client->city }}, {{ $order->client->postcode }} Ph {{ $order->client->phone }}</p>
                </div>
                <div class="row">
                    <div class="col-xs-2">Customer parent</div>
                    <p class="col-xs-9">{{ isSet($order->client->parentGroup) ? $order->client->parentGroup->name : '-' }}</p>
                </div>

                <div class="row">
                    <div class="col-xs-2">Freight</div>
                    <p class="col-xs-9">{{ empty($freight->code) ? ' - ' : $freight->code }}
                    @if(isSet($freight->notes))
                    <br /> {{ $freight->notes }}
                    @endif
                    </p>
                </div>

                <div class="row">
                    <div class="col-xs-2">Order date</div>
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
                        <th class="c1">#</th>
                        <th class="c2">Product code</th>
                        <th class="c3">Color</th>
                        <th class="c4">Size</th>
                        <th class="c5">P/O qty</th>
                        <th class="c6">Unit Price</th>
                        <th class="c7">Ext price</th>
                        <th class="c8" >M%</th>
                        <th class="c9">C%</th>
                    </tr>
                </thead>
                <tbody>
<?php
$n = 1;
usort($items, function ($a, $b) {
    return strcmp($b->product_code, $a->product_code);
});

?>

                @foreach($items as $i)
                <?php $customDiscount = '';?>
                <tr>
                    <td class="c1">{{ $n++ }}</td>
                    <td class="c2">{{ $i->product_code }} - {!! $i->product->description !!}</td>
                    <td class="c3">{{ $i->product->color_name }}</td>
                    <td class="c4">{{ $i->product->size }}</td>
                    <td class="c5">{{ $i->qty_supplied }}/{{ $i->qty }}</td>
                    <td class="c6">{{ number_format($i->price/100,2) }}

                    </td>
                    <td class="c7">{{ number_format($i->ext_price/100,2) }}


                    <td class="c8">{{ $i->markup != 0 ? number_format($i->markup * 100,1).'%' : '' }}</td>
                    <td class="c9">{{ $i->custom_discount }}</td>

                </tr>
                @endforeach
                <tr>
                <td colspan="5"  align="left" ><br>Order Contact: <span style="color: #00f">{{ $order->order_contact }}</span><br>Order instructions:<br><span class="orderinstructions">{!! nl2br($order->instructions) !!}</span></td><td aligne="right"><span style="font-weight: bold" >Order total :</span></td>
                <td colspan="2"><p style="font-weight: bold" align="left">${{ number_format($totalItemsPrice/100,2) }}</p></td>
                <td colspan="2">&nbsp;</td>
                </tr>

                <tr><td align="right" colspan="8">{{ number_format(($totalItemsPrice-$totalItemsCost)*100/$totalItemsPrice,1) }}%<br /><i>note:<br /> #q = qty discount<br /> #s = Special client price <br /> #c Custom price entered </i> </td></tr>
                </tbody>
                </table>

                <img class="freight-docket visible-print" src="{{ asset('images/freight_docket.jpg') }}" />
</div>

@endsection
