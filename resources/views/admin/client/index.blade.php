@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Clients</h3>
                <form method="POST" action="filter/clients" class="form-inline">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <label>Search key</label>
                        <input class="form-control" type="text" name="fkey[or]" value="{{ json_decode( session( env('USER_FILTER_KEY').'clients'),true)['fkey']['or'] }}" />

                        <label>Status</label>
                        {!! Form::select('fkey[and]', [''=>'Any', 'active'=>'Active','inactive'=>'Inactive'],json_decode( session( env('USER_FILTER_KEY').'_client'),true)['fkey']['and'],['class'=>'form-control']) !!}


                        <button type="submit" name="Filter" class="btn btn-default"><i class="fa fa-filter fa-1x"></i> Filter list</button>

                        @if( session( env('USER_FILTER_KEY').'_client') )
                        <button type="submit" name="remove_filter" value="1" class="btn btn-default"><i class="fa fa-eraser fa-1x"></i> Remove filter</button>
                        @endif


                    </div>
                </form>
                </div>

                <div class="panel-body">

                     <div class="row">
                        <div class="col-md-12" style="padding-bottom: 4px"><a href="{{ route('client.create') }}"><button class="btn btn-primary pull-right"><i class="fa fa-plus-square-o fa-1x"></i> Create a new client</button></a></div>
                    </div>

                    <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client name</th>
                        <th>City</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->client_id }}</td>
                    	<td>{{ $client->name }}</td>
                        <td>{{ $client->city }}</td>
                    	<td width="80">

                        <a href="{{ route('client.pricing', ['id' => $client->client_id] ) }}"><i class="fa fa-usd" aria-hidden="true"></i></a>&nbsp;
                        <a href="{{ route('client.edit', ['id' => $client->client_id] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a>

                        </td>
                    </tr>
                    @endforeach

                    </tbody>
                    </table>
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
