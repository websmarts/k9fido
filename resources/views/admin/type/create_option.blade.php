@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Product Types</div>

                <div class="panel-body">

					<h3>Create a Product Option</h3>

					{!! Form::open( array('route' => array('typeoption.store'), 'method' => 'post' )) !!}
					<input type="hidden" name="typeid" value="{{ $type->typeid }}" />

					<div class="form-group{{ $errors->has('opt_code') ? ' has-error' : '' }}">
					    {!! Form::label('opt_code', 'Opt code', array('class' => 'col-md-4 control-label')) !!}
					    <div class="col-md-6">
					    {!! Form::text('opt_code', null,  array('class' => 'form-control')) !!}

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
					    {!! Form::text('opt_desc', null,  array('class' => 'form-control')) !!}

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
					    {!! Form::text('opt_class', null,  array('class' => 'form-control')) !!}

					    @if ($errors->has('opt_class'))
					        <span class="help-block">
					            <strong>{{ $errors->first('opt_class') }}</strong>
					        </span>
					    @endif
					    </div>
					</div>

					 <button type="submit"  value="Create" class="btn btn-primary" >Create</button>

					{!! Form::close() !!}

		@include('admin.type.partials.type_options', ['hide_add_link' => true, 'hide_edit_link' => true ])


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
