@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Client: {{ $client->name  }}</div>

                <div class="panel-body">
                {{-- dump($client) --}}


                    {!! Form::model($client, array('route' => array('client.update', $client->client_id ), 'method' => 'patch' )) !!}

                    <div class="col-md-12">
                         <button type="submit"  value="Save" class="btn btn-primary pull-right" >Save</button>
                         </div>

                    @include('admin.client.partials.form')



                    <div class="col-md-12">
                        <input type="submit"  name ="b" value="Save" class="btn btn-primary pull-right" />xx

                        <input type="submit"  name ="b" value="Delete" class="btn btn-warning pull-left" />
                        &nbsp;Delete key: <input type="text" name="delete_key" value="" />
                    </div>



                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
