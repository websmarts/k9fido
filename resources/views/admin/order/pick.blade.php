@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px;">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" >Pick Order  {{ $order->order_id  }} for {{ $order->client->name }}</div>



                <div class="panel-body">
                {{-- dump($order) --}}

                <div id="accordion" ></div>
				        <div style="padding:15px; text-align:center">

                    <a class="btn btn-success pull-right" id="park-button">Park Order</a>
                    <a class="btn btn-primary pull-left" id="save-button">Save Order</a>
  	            </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
//var items = {!! json_encode($order->items->toArray()) !!};
var items=[];
var orderId = {{ $order->id }};
var csrf_token = $("meta[name=csrf-token]").attr('content');
var url = "{{ url('ajax/pickorder/'.$order->id) }}";

  $( function() {

     // onChange event handler for barcode input
    $('#accordion').on('keyup',function(e){
      getInput(e.target);
    });

    // onClick event handler for save form
    $('#save-button').on('click',function(e){
      saveOrder();
    });
    $('#park-button').on('click',function(e){
      parkOrder();
    });

    function getPickItem(id){
      return items.filter(function(e){return e.id == id;}).pop();
    }

    function updatePickItem(id,obj){
      $.each(items,function(idx,val){
        if(val.id == id){
          items[idx] = obj;
        }
      });
    }
    function getInputId(e){
      var name = $(e).attr('name');
      var regExpForId = /\[(\d+)?\]/;
      var match = regExpForId.exec(name)
      return match[1];
    }
    function getInputName(e){
      var name = $(e).attr('name');
      var regExpForName = /^([^\]]+)?\[/;
      var match = regExpForName.exec(name)
      return match[1];
    }

    function getInput(input){
      var inputId = getInputId(input);
      var inputName = getInputName(input);
      var item = getPickItem(inputId);

      //console.log(item);

      if (inputName =='barcode'){
        item.picked_barcode = parseInt(input.value);
        item.picked_qty =$('input[name="supplied['+inputId+']"]').val();
        updatePickItem(inputId,item);
        updateForm();
      }

      if (inputName =='supplied'){
        item.picked_qty = parseInt(input.value);
        if( item.picked_barcode == parseInt(item.barcode)){

          updatePickItem(inputId,item);
          updateForm();
        }
      }
    }
    /**
     * updates the form based on the state
     * of the current item infomration
     * @method updateForm
     * @return {[type]}   [description]
     */
    function updateForm(){
      $('h3').addClass('item-error');
      $('.barcode-icon').show();
      $('.qty-icon').show();
      checkItems();
    }

    function checkItems(){
        $.each(items, function(idx,item){
            //console.log('chk item',item);
            if(item.picked_qty == item.qty){
              hideQtyIcon(item.id);
            }

            if(item.picked_barcode == item.barcode){
             hideBarcodeIcon(item.id)
            }
            if((item.picked_barcode == item.barcode) && (item.picked_qty == item.qty )){
              $('#header-' + item.id).removeClass('item-error');
            }
            //$('#itemqty-'+item.id).text((item.qty - item.qty_supplied) +' / '+item.qty);
        });
    }
    function hideBarcodeIcon(id){
      $('#header-' + id).find('.barcode-icon').hide();
    }
    function hideQtyIcon(id){
      $('#header-' + id).find('.qty-icon').hide();
    }

    function renderForm (){

      $('#accordion').html('');
      $.each(items, function(idx,item){
          // Add accordion row
          var row = '<h3 class="item" id="header-'+item.id+'">'+item.product_code +'<i class="barcode-icon pull-right fa fa-barcode fa-1x" aria-hidden="true"></i><i class="qty-icon pull-right fa fa-cubes fa-1x" aria-hidden="true"></i></h3>';
          row += '<div id="item-' + item.id + '">';
          row += '<div class="col-lg-5">Barcode</div>';
          row += '<div class="col-lg-7"><input type="number" value="' +item.barcode+ '0" class="barcode_input" name="barcode['+item.id+']"/></div>';
          row += '<div class="col-lg-5">Pick (<span id="itemqty-'+item.id+'">'+ (item.qty - item.qty_supplied) +' / '+item.qty+'</span> )</div>';
          row += '<div class="col-lg-2"><input type="number" value="'+ item.qty_supplied +'" class="supplied_input" name="supplied['+ item.id+']"/></div>';
          row += '</div>';
          $('#accordion').append(row);

      });

      $( "#accordion" ).accordion({
        collapsible: true,
        heightStyle: "content"
      });

      updateForm();

    };

    function loadItems(){

      $.ajax({
        method: "GET",
        url: url,
      })
        .done(function( data ) {
          //console.log(data);
          items = data;
          renderForm();
        });
    }

    function saveOrder(){
      saveItems('picked');
    }

    function parkOrder(){
      saveItems('parked');
    }

    function saveItems(status){
      $.ajax({
        method: "POST",
        url: url,
        data: { _token: csrf_token, status: status, order_id: orderId, items: items }
      })
        .done(function( result ) {
          console.log(result);
          // redirect to location returned
          window.location = result.location;

        });
    }

    function clearForm(){
       //$("#accordion").find("input[type=text]").val("");
       updateForm();
    }

    loadItems();

  } );
</script>
@endsection
