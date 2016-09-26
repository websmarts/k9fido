@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
            {{ Form::open( ['route' => ['client.pricing', $client->client_id], 'method'=>'post'] ) }}
                <div class="panel-heading"><h3>{{ $client->name }} - Special Pricing</h3>
                <button class="btn btn-primary" type="submit">Update</button>
                </div>

                <div class="panel-body">

                <div id="app">
                    <client-prices :prices="{{ json_encode($prices->toArray()) }}"></client-prices>
                </div>


                    <table class="table table-striped">
                    <thead>
                    <tr>

                        <th>Product code</th>
                        <th>Client price</th>
                        <th>Standard price</th>

                    </tr>
                    </thead>
                    <tbody>
                     <tr>
                        <td><input type="text" name="new_product_code" /></td>
                        <td><input type="text" name="new_product_price" /></td>
                        <td>&nbsp;</td>
                    </tr>
                    @foreach($prices as $price)
                    <tr>
                    	<td>{{ $price->product_code }}</td>
                        <td><input type="text" name="{{ $price->product_code }}" value="{{ $price->client_price }}" /></td>
                        <td>{{ $price->product->price }}</td>
                    </tr>
                    @endforeach

                    </tbody>
                    </table>

                </div>
            {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="/js/main.js"></script>


@endsection
