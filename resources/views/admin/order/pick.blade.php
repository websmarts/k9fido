@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px;">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" >Pick Order  {{ $order->order_id  }}</div>



                <div class="panel-body">
                {{ dump($order->items) }}
                <div class="row">
                    <div class="col-xs-2">For:</div>
                    <p class="col-xs-10">{{ $order->client->name }}</p>
                 </div>


                <div>Ordered Items</div>
                <div id="accordion" >

	                @foreach($order->items as $i)
	                <h3 id="header-{{ $i->id }}">{{ $i->product_code }}<span><i class="pull-right fa fa-cart-plus fa-1x" aria-hidden="true"></i></span></h3>
	                <div id="item-{{ $i->id }}">
		                <div class="col-lg-5">Barcode</div>
		                <div class="col-lg-7"><input type="text" class="barcode_input" name="barcode[{{ $i->id }}]"/></div>
		                <div class="col-lg-5">Qty Supplied</div>
		                <div class="col-lg-2"><input type="text" class="supplied_input" name="supplied[{{ $i->id }}]"/></div>
	                </div>
	                @endforeach


                </div>
				<div style="padding:15px; text-align:center">
	                <a class="btn btn-primary">Update Picking List</a>
                  <a class="btn btn-primary">Save Order</a>
	            </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
var items = [];
@foreach($order->items as $i)
var item = {
              id: {{ $i->id }},
              barcode: {{ $i->product->barcode }},
              qty: {{$i->qty}}
            };
items[{{ $i->id }}] = item;
@endforeach;
  $( function() {

    console.log(items);

    var regExpForKey = /\[(\d+)?\]/;

    $( "#accordion" ).accordion({
      collapsible: true,
      heightStyle: "content"
    });

    // onChange event handlers for inputs
    $('.barcode_input').on('change',function(e){
      var name = $(this).attr('name');
      var match = regExpForKey.exec(name)
      var id = match[1];
      var data = {
        required_barcode: items[id]['barcode'],
        supplied_barcode: this.value
      }
      console.log(data);
    });


  } );
</script>
@endsection
