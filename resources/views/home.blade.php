@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                     <div class="panel-body">
                    <a href="/stockadjust" class="btn">Stock adjuster</a>
                     <!-- <a href="/freight" class="btn">Freight calculator</a> -->
                    @if(isSet($basketOrders) )
                    @include('admin.lists.orders',['title'=>'Order baskets', 'data'=>$basketOrders])
                    @endif

                    @if(isSet($newOrders) )
                        @include('admin.lists.orders',['title'=>'New orders', 'data'=>$newOrders])
                    @endif

                    @if(isSet($pickOrders) )
                        @include('admin.lists.pickorders',['title'=>'Orders ready to be picked now', 'data'=>$pickOrders])
                    @endif
                    @if(isSet($parkOrders) )
                        @include('admin.lists.pickparkedorders',['title'=>'Parked orders - to be picked later', 'data'=>$parkOrders])
                    @endif

                    @if(isSet($exportOrders) )
                        @include('admin.lists.exportorders',['title'=>'Orders ready to be exported to MYOB', 'data'=>$exportOrders])
                    @endif





                </div>
            </div>
        </div>
    </div>
</div>
@endsection
