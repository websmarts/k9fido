@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Product: {{ $product->product_code  }}</div>

                <div class="panel-body">


                    {!! Form::model($product, array('route' => array('product.update', $product->id ), 'method' => 'patch' )) !!}


                    <div class="col-md-12">
                         <button type="submit"  value="Save" class="btn btn-primary pull-right" >Save</button>
                         </div>


                    @include('admin.product.partials.form');

                    <div class="col-md-12">
                         <button type="submit"  value="Save" class="btn btn-primary pull-right" >Save</button>
                         </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>




     <div class="row">
        <div class="col-md-6 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Bill of Material: </div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                        </head>

                        <tbody>
                        @if($product->bom->count())
                        @foreach($product->bom as $item)
                        <tr>
                        <td>{{ $item->item_product_code }}</td>
                        <td>{{ $item->item_qty }}</td>
                        <td>{{ is_null($item->item_price) ? '-' :  $item->item_price }}</td>
                        </tr>
                        @endforeach
                        @endif
                        </tbody>

                    </table>
                    <a class="btn btn-primary pull-right" href="{{ route('bom.edit',$product->id) }}">Edit BoM</a>

                </div>
            </div>
        </div>

    </div>


</div>

@endsection

@section('script')
<script>
  $( function() {
    $( ".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd'
    });
  } );
  </script>


@endsection
