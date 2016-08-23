@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Clients</div>

                <div class="panel-body">
                    <h3>Client list</h3>

                    <table class="table table-striped">
                    <tbody>
                    @foreach($clients as $client)
                    <tr>
                    	<td>{{ $client->name }}</td>
                    	<td width="20"><a href="#"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
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