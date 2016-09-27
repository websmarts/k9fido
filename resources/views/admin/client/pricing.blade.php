@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">


                <div id="app">
                <pre>@{{ $data.newprices | json }}</pre>
                <div class="row">
                    <div class="col-sm-3"><input v-model="product_code"></div>
                    <div class="col-sm-3"><input v-model="client_price"></div>
                    <div class="col-sm-6"><button @click="addItem()">Add Item</button></div>
                <div>
                <div id="list">


                  <div v-for="price in newprices" track-by="id" class="row">
                    <div class="col-sm-3">@{{ price.product_code }}</div>

                    <div class="col-sm-3"><input v-model="price.client_price" @blur="doneEdit(price)" ></div>

                  </div>

              </div>
                </div>




        </div>
    </div>
</div>
@endsection

@section('script')
<script>
var client_id = {{ $client->client_id }};
var prices = {!! json_encode($prices->toArray()) !!};
var url = "{{ route('client.price')}}";
</script>
<script src="/js/main.js"></script>


@endsection
