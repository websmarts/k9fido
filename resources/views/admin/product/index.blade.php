@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Products</h3>

                <form method="POST" action="/filter/{{ $filterKey }}" class="form-inline">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <label>Search key</label>
                        <input class="form-control" type="text" name="fkey[or]" value="{{ json_decode( session( env('USER_FILTER_KEY').$filterKey),true)['fkey']['or'] }}" />

                        <label>Status</label>
                        {!! Form::select('fkey[and]', Appdata::get('product.status.filter.options'),json_decode( session( env('USER_FILTER_KEY').$filterKey),true)['fkey']['and'],['class'=>'form-control']) !!}


                        <button type="submit" name="Filter" class="btn btn-default"><i class="fa fa-filter fa-1x"></i> Filter list</button>

                        @if( session( env('USER_FILTER_KEY').$filterKey) )
                        <button type="submit" name="remove_filter" value="1" class="btn btn-default"><i class="fa fa-eraser fa-1x"></i> Remove filter</button>
                        @endif


                    </div>
                </form>
                </div>

                <div class="panel-body">

                    <div class="row">

                        <div class="col-md-12" style="padding-bottom: 4px"><a href="{{ route('product.create') }}"><button class="btn btn-primary pull-right"><i class="fa fa-plus-square-o fa-1x"></i> Create a new product</button></a></div>
                    </div>




                    <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="120">Product code</th>
                            <th>Description</th>
                            <th>Sales($)</th>
                            <th></th>
                            <th>Status</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                    <tr>
                    	<td>{{ $product->product_code }}</td>
                        <td>{!! $product->description !!}</td>
                        <td>{{ $product->recentSales() ? number_format($product->recentSales()/100,0) : '' }}</td>
                        <td>{{ $product->bom->count() ? 'BOM' :'' }}</td>
                        <td>{{ $product->status }}</td>
                    	<td width="20"><a href="{{ route('product.edit', ['id' => $product->id] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
                    </tr>
                    @endforeach

                    </tbody>
                    </table>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
