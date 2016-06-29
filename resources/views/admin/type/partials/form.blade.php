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

    <div class="form-group{{ $errors->has('display_format') ? ' has-error' : '' }}">
        {!! Form::label('display_format', 'Display format', array('class' => 'col-md-4 control-label')) !!}
        <div class="col-md-6">
        {!! Form::select('display_format', array('h'=>'Horizontal','v'=>'Vertical'), null,  array('class' => 'form-control')) !!}

        @if ($errors->has('display_format'))
            <span class="help-block">
                <strong>{{ $errors->first('display_format') }}</strong>
            </span>
        @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('type_description') ? ' has-error' : '' }}">
        {!! Form::label('type_description', 'Type description', array('class' => 'col-md-4 control-label')) !!}
        <div class="col-md-6">
        {!! Form::textarea('type_description', null,  array('class' => 'form-control')) !!}

        @if ($errors->has('type_description'))
            <span class="help-block">
                <strong>{{ $errors->first('type_description') }}</strong>
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

</div>
