@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Staff</div>

                <div class="panel-body">
                    <h3>Staff list</h3>

                    <table class="table table-striped">
                    <tbody>
                    @foreach($staff as $person)
                    <tr>
                    	<td>{{ $person->name }}</td>
                    	<td width="20"><a href="#"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
                    </tr>
                    @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
