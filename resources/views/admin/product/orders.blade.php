@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Product:  {{ $product->product_code }} - open orders</h3>



                </div>

                <div class="panel-body">


{{-- dump($orders) --}}


                    <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="120">Order#</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                    <tr>
                    	<td> {{ $order->order_id }} &nbsp; 
                            <a href="{{ route('order.show',['order' => str_replace('T0_','',$order->order_id)]) }}">view order</a>
                        </td>

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
