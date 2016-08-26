@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">View Order Item -  order {{ $item->order_id  }}</div>



                <div class="panel-body">

                <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order qty</th>
                        <th>Product code</th>
                        <th>Price</th>
                        <th>Client price</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>

                <tr>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->product_code }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $clientPrice->price }}</td>
                    <td width="20"><a href="{{ route('order.showitem', ['order_id' => $item->order_id,'product_code'=>$item->product_code] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
                </tr>


                </tbody>
                </table>



                </div>
            </div>
        </div>
    </div>
</div>

@endsection
