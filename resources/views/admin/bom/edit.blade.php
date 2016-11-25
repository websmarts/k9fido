@extends('layouts.app')

@section('content')
<div class="container">



     <div class="row">
        <div class="col-md-6 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Bill of Material for: {{ $id }} {{-- dump($bom)  --}}</div>

                <div class="panel-body">
                   {{ Form::open( ['route' => ['bom.update', $id], 'method'=>'put'] ) }}
                   <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                        </head>
                        <tbody>

                        @foreach($bom as $i => $item)
                        <tr>
                        <td><input type="text" name="item[{{$item->id}}][item_product_code]" value="{{ $item->item_product_code }}" /></td>
                        <td><input type="text" name="item[{{$item->id}}][item_qty]" value="{{ $item->item_qty }}" /></td>
                        <td><input type="text" name="item[{{$item->id}}][item_price]" value="{{ $item->item_price }}" /></td>
                        </tr>
                        @endforeach
                        <tr>
                        <td><input type="text" name="item[-1][item_product_code]" value="" /></td>
                        <td><input type="text" name="item[-1][item_qty]" value="" /></td>
                        <td><input type="text" name="item[-1][item_price]" value="" /></td>
                        </tr>
                        </tbody>
                    </table>
                    <button type="submit" value="Update" class="btn btn-primary">Update</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

    </div>


</div>

@endsection
