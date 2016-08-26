@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Staff</h3></div>

                <div class="panel-body">


                    <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Login</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Role</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($staff as $person)
                    <tr>
                    	<td>{{ $person->name }}</td>
                        <td>{{ $person->firstname }}</td>
                        <td>{{ $person->lastname }}</td>
                        <td>{{ $person->role }}</td>
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
