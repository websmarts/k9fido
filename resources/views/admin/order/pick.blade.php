@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px;">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" >Pick Order  {{ $order->order_id  }}</div>



                <div class="panel-body">
                {{-- dump($order->items) --}}
                <div class="row">
                    <div class="col-xs-2">For:</div>
                    <p class="col-xs-10">{{ $order->client->name }}</p>
                 </div>


                <div>Ordered Items</div>
                <div id="accordion" >

	                @foreach($order->items as $i)
	                <h3>{{ $i->product_code }}</h3>
	                <div id="item-{{ $i->id }}" >
		                <div class="col-lg-5">Barcode</div>
		                <div class="col-lg-7"><input type="text" name="barcode"/></div>
		                <div class="col-lg-5">Qty Supplied</div>
		                <div class="col-lg-2"><input type="text" name="supplied"/></div>
	                </div>
	                @endforeach


                </div>
				<div style="padding:15px; text-align:center">
	                <a class="btn btn-primary">Save Picking List</a>
	            </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
  $( function() {
    $( "#accordion" ).accordion({
      collapsible: true,
      heightStyle: "content"
    });
  } );
</script>
@endsection
