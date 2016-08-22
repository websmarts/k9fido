@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Products

                <form method="GET" action="/product" class="form-inline">
                    <div class="form-group">
                        <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}" /> -->
                        <input class="form-control" type="text" name="wildcard" value="" />

                         <!-- Status: <input class="form-control" type="text" name="fkey[and]" value="{{ json_decode( session( env('USER_FILTER_KEY').'_product'),true)['fkey']['and'] }}" /> -->


                        <button type="submit" name="Filter" class="btn btn-default"><i class="fa fa-filter fa-1x"></i> Filter list</button>

                        @if(session(env('USER_FILTER_KEY').'_product') )
                        <button type="submit" name="remove_filter" value="1" class="btn btn-default"><i class="fa fa-eraser fa-1x"></i> Remove filter</button>
                        @endif

                    </div>
                </form>
                </div>

                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-6"><h3>Product list</h3></div>
                        <div class="col-md-6"><a href="{{ route('product.create') }}"><button class="btn btn-primary pull-right"><i class="fa fa-plus-square-o fa-1x"></i> Create a new product</button></a></div>
                    </div>




                    <table class="table table-striped">
                    <tr>
                        <th>Product code</th>
                        <th>Description</th>
                        <th>&nbsp;</th>
                    </tr>
                    <tbody>
                    @foreach($products as $product)
                    <tr>
                    	<td>{{ $product->product_code }}</td>
                        <td>{{ $product->description }}</td>
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
