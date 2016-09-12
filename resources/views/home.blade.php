@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                     <div class="panel-body">


                    @if(isSet($newOrders) )
                        @include('admin.lists.orders',['title'=>'New orders', 'data'=>$newOrders])
                    @endif

                    @if(isSet($pickOrders) )
                        @include('admin.lists.pickorders',['title'=>'Pick orders', 'data'=>$pickOrders])
                    @endif

                    @if(isSet($basketOrders) )
                        @include('admin.lists.orders',['title'=>'Basket orders', 'data'=>$basketOrders])
                    @endif



                </div>
            </div>
        </div>
    </div>
</div>
@endsection
