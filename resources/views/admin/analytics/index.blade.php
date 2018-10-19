@extends('layouts.app')

@section('content')
<div class="container">
     <div class="row">
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Business Analytics</div>

                <div class="panel-body">
                    <ul>
                        <li><a href="{{ route('analytics.customers')}}">Customers</a></li>
                        <li><a href="{{ route('analytics.orders')}}">Orders</a></li>
                    </ul>


                </div>
            </div>
        </div>
    </div>
</div>

@endsection