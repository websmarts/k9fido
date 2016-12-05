@extends('layouts.app')

@section('content')
<div class="container">

	@include('admin.prospector.nav')

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Prospector Accounts</h3>


                </div>

                <div class="panel-body">


                    <table class="table table-striped">
                    <thead>
                    <tr>
                    <th>Acct #</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>&nbsp;</th>

                    </tr>
                    </thead>
                    <tbody>


                    </tbody>
                    </table>
                    <div> </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
