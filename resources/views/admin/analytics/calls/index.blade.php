@extends('layouts.app')

@section('content')
<div class="container">
     <div class="row">
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Call Planner </div>

                <div class="panel-body">
                    <ul>
                        <li><a href="{{ route('analytics.call',['id'=>6])}}">Darren</a></li>
                        <li><a href="{{ route('analytics.call',['id'=>10])}}">Kerry</a></li>
                        <li><a href="{{ route('analytics.call',['id'=>13])}}">Trudy</a></li>
                    </ul>


                </div>
            </div>
        </div>
    </div>
</div>

@endsection