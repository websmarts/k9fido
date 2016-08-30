@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Product Types</div>

                <div class="panel-body">

					<h3>Edit Product Option</h3>

					<form method="post" action='/typeoption/{{ $typeOption->typeid }}/{{ $typeOption->opt_code }}' >
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />


					<div class="form-group{{ $errors->has('opt_code') ? ' has-error' : '' }}">
					    {!! Form::label('opt_code', 'Opt code', array('class' => 'col-md-4 control-label')) !!}
					    <div class="col-md-6">
					    {!! Form::text('opt_code', $typeOption->opt_code,  array('class' => 'form-control')) !!}

					    @if ($errors->has('opt_code'))
					        <span class="help-block">
					            <strong>{{ $errors->first('opt_code') }}</strong>
					        </span>
					    @endif
					    </div>
					</div>

					<div class="form-group{{ $errors->has('opt_desc') ? ' has-error' : '' }}">
					    {!! Form::label('opt_desc', 'Opt desc', array('class' => 'col-md-4 control-label')) !!}
					    <div class="col-md-6">
					    {!! Form::text('opt_desc', $typeOption->opt_desc,  array('class' => 'form-control')) !!}

					    @if ($errors->has('opt_desc'))
					        <span class="help-block">
					            <strong>{{ $errors->first('opt_desc') }}</strong>
					        </span>
					    @endif
					    </div>
					</div>

					<div class="form-group{{ $errors->has('opt_class') ? ' has-error' : '' }}">
					    {!! Form::label('opt_class', 'Opt class', array('class' => 'col-md-4 control-label')) !!}
					    <div class="col-md-6">
					    {!! Form::text('opt_class', $typeOption->opt_class,  array('class' => 'form-control')) !!}

					    @if ($errors->has('opt_class'))
					        <span class="help-block">
					            <strong>{{ $errors->first('opt_class') }}</strong>
					        </span>
					    @endif
					    </div>
					</div>


					<div class="form-group">
					    {!! Form::label('_delete', 'Delete option', array('class' => 'col-md-4 control-label')) !!}
					    <div class="col-md-6">
					    {!! Form::checkbox('_delete', $typeOption->typeid ) !!}

					    </div>
					</div>

					 <button type="submit"  value="Update" class="btn btn-primary" >Update</button>

					{!! Form::close() !!}




                </div>
            </div>
        </div>
    </div>
</div>
@endsection
