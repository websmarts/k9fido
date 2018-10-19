@extends('layouts.app')

@section('content')
<div class="container">
     <div class="row">
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Customers Report</div>

                <div class="panel-body">
                
{{dump($users)}}
                <table class="table table-striped">
                <tr>
                    <th>Client</th>
                    <th>Call Freq</th>
                    <th>Visits</th>
                    <th>Phones</th>
                    <th>Sales</th>
                    <th>COS</th>
                    <th>GP</th>
                    <th>Orders</th>
                    <th>REP</th>

                    <th>Period</th>
                </tr>
                    @foreach($objects as $o)

                    <tr>
                    <td>{{$o->name}}</td>
                    <td>{{$o->sales->call_frequency}}</td>
                    <td>{{$o->contacts->where('call_type_id',1)->count()}}</td>
                    <td>{{$o->contacts->where('call_type_id',2)->count()}}</td>
                    <td>{{$o->sales->total}}</td>
                    <td>{{$o->sales->cost}}</td>
                    <td>{{$o->sales->gp}}</td>
                    <td>{{$o->orders->count()}}</td>
                    <td>{{$users->where('id',$o->salesrep)->first()->name }} ({{$o->salesrep}})</td>
                    <td>{{$period}}</td>
                    

                    </tr>


                    @endforeach
                </table>


                </div>
            </div>
        </div>
    </div>
</div>

@endsection