@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Product Types</h3>
                <form method="POST" action="/filter/type" class="form-inline">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        <label>Search key</label>
                        <input class="form-control" type="text" name="fkey[or]" value="{{ json_decode( session( env('USER_FILTER_KEY').'_type'),true)['fkey']['or'] }}" />

                        <label>Status</label>
                        {!! Form::select('fkey[and]', [''=>'Any', 'active'=>'Active','inactive'=>'Inactive'],json_decode( session( env('USER_FILTER_KEY').'_product'),true)['fkey']['and'],['class'=>'form-control']) !!}

                        <button type="submit" name="Filter" class="btn btn-default"><i class="fa fa-filter fa-1x"></i> Filter list</button>

                        @if( session( env('USER_FILTER_KEY').'_type') )
                        <button type="submit" name="remove_filter" value="1" class="btn btn-default"><i class="fa fa-eraser fa-1x"></i> Remove filter</button>
                        @endif

                    </div>
                </form>
                </div>

                <div class="panel-body">

                    <div class="row">

                        <div class="col-md-12"><a href="{{ route('type.create') }}"><button class="btn btn-primary pull-right"><i class="fa fa-plus-square-o fa-1x"></i> Create a new product type</button></a></div>
                    </div>




                    <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product type</th>
                            <th>Status</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($types as $type)
                    <tr>
                    	<td>{{ $type->name }}</td>
                        <td>{{ $type->status }}</td>
                    	<td width="20"><a href="{{ route('type.edit', ['id' => $type->typeid] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
                    </tr>
                    @endforeach

                    </tbody>
                    </table>
                    {{ $types->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
