@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Product Types</div>

                <div class="panel-body">
                    <h3>Create Product Type</h3>

                    {!! Form::model($type, array('route' => array('type.store', $type->typeid ), 'method' => 'post' )) !!}

                    @include('admin.type.partials.form')

                     <button type="submit"  value="Create" class="btn btn-primary" >Create</button>


                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
