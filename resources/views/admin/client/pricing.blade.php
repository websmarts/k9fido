@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

                <h3>Edit Client Prices</h3>
                <div id="app">
                    <client-prices
                        json_client='{{ $client->toJson() }}'
                        url='{{ route("client.price.ajax")}}'></client-prices>
                </div>

        </div>
    </div>
</div>
@endsection

@section('script')

<script src="{{ elixir('js/clientprices.js') }}"></script>


@endsection
