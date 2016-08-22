<div class="form-horizontal">

    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', 'Name', array('class' => 'col-md-4 control-label')) !!}

        <div class="col-md-6">
        {!! Form::text('name', null,  array('class' => 'form-control')) !!}

        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        {!! Form::label('description', 'Description', array('class' => 'col-md-4 control-label')) !!}
        <div class="col-md-6">
        {!! Form::text('description', null,  array('class' => 'form-control')) !!}

        @if ($errors->has('description'))
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
        @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('display_order') ? ' has-error' : '' }}">
        {!! Form::label('display_order', 'Display order', array('class' => 'col-md-4 control-label')) !!}
        <div class="col-md-6">
        {!! Form::text('display_order', null,  array('class' => 'form-control')) !!}

        @if ($errors->has('display_order'))
            <span class="help-block">
                <strong>{{ $errors->first('display_order') }}</strong>
            </span>
        @endif
        </div>
    </div>

    @if($category->parent_id > 0)
    <div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
        {!! Form::label('parent_id', 'Parent id', array('class' => 'col-md-4 control-label')) !!}
        <div class="col-md-6">
        {!! Form::select('parent_id', $parentOptions, null,  array('placeholder' => 'Select a parent ...', 'class' => 'form-control')) !!}

        @if ($errors->has('parent_id'))
            <span class="help-block">
                <strong>{{ $errors->first('parent_id') }}</strong>
            </span>
        @endif
        </div>
    </div>
    @endif








</div>
