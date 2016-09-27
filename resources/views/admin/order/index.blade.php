@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Orders</h3>

                <form method="POST" action="/filter/{{ $filterKey }}" class="form-inline">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input class="form-control" type="text" name="fkey[or]" value="{{ json_decode( session( env('USER_FILTER_KEY').$filterKey),true)['fkey']['or'] }}" />

                        <label>Status</label>
                        {!! Form::select('fkey[and]', array_merge([''=>'Any'] ,Appdata::get('order.status.options')),json_decode( session( env('USER_FILTER_KEY').$filterKey),true)['fkey']['and'],['class'=>'form-control']) !!}

                         <label>Exported</label>
                        {!! Form::select('fkey[and2]', array_merge([''=>'Any'] ,Appdata::get('order.exported.options')),json_decode( session( env('USER_FILTER_KEY').$filterKey),true)['fkey']['and2'],['class'=>'form-control']) !!}


                        <button type="submit" name="Filter" class="btn btn-default"><i class="fa fa-filter fa-1x"></i> Filter list</button>

                        @if( session( env('USER_FILTER_KEY').$filterKey) )
                        <button type="submit" name="remove_filter" value="1" class="btn btn-default"><i class="fa fa-eraser fa-1x"></i> Remove filter</button>
                        @endif

                    </div>
                </form>
                </div>

                <div class="panel-body">


                    <table class="table table-striped">
                    <thead>
                    <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Exported</th>
                    <th>&nbsp;</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                    <tr>
                    	<td>{{ $order->id}}</td>
                        <td>{{ date('j-m-Y',strtotime($order->modified)) }}</td>

                        <td>{{ $order->name }}</td>
                        <td>{{ $order->exported }}</td>
                    	<td width="20"><a href="{{ route('order.show', ['id' => $order->id] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
                    </tr>
                    @endforeach

                    </tbody>
                    </table>
                    <div>{{ $orders->links() }} </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
