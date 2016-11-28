@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Client: {{ $client->name  }}</div>

                <div class="panel-body">


                    {!! Form::model($client, array('route' => array('client.update', $client->client_id ), 'method' => 'patch' )) !!}

                    <div class="col-md-12">
                         <button type="submit"  value="Save" class="btn btn-primary pull-right" >Save</button>
                         </div>

                    @include('admin.client.partials.form')



                    <div class="col-md-12">
                         <button type="submit"  value="Save" class="btn btn-primary pull-right" >Save</button>
                         </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
