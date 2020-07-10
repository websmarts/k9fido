<div class="form-horizontal">


    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', 'eCat login name', array('class' => 'col-md-4 control-label')) !!}

        <div class="col-md-6">
            {!! Form::text('name', null,  array('class' => 'form-control')) !!}

            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        {!! Form::label('email', 'Email/fido login', array('class' => 'col-md-4 control-label')) !!}

        <div class="col-md-6">
            {!! Form::text('email', null,  array('class' => 'form-control')) !!}

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('pass') ? ' has-error' : '' }}">
        {!! Form::label('pass', 'Set password', array('class' => 'col-md-4 control-label')) !!}

        <div class="col-md-6">
            <input class="form-control" name="set_password" placeholder="Enter new password to change">
        </div>
    </div>

    <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
        {!! Form::label('firstname', 'First name', array('class' => 'col-md-4 control-label')) !!}

        <div class="col-md-6">
            {!! Form::text('firstname', null,  array('class' => 'form-control')) !!}

            @if ($errors->has('firstname'))
                <span class="help-block">
                    <strong>{{ $errors->first('firstname') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
        {!! Form::label('lastname', 'Last name', array('class' => 'col-md-4 control-label')) !!}

        <div class="col-md-6">
            {!! Form::text('lastname', null,  array('class' => 'form-control')) !!}

            @if ($errors->has('lastname'))
                <span class="help-block">
                    <strong>{{ $errors->first('lastname') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
        {!! Form::label('role', 'Role', array('class' => 'col-md-4 control-label')) !!}
        <div class="col-md-6">
        {!! Form::select('role', array('rep'=>'Rep','manager'=>'Manager'), null,  array('class' => 'form-control')) !!}

        @if ($errors->has('role'))
            <span class="help-block">
                <strong>{{ $errors->first('role') }}</strong>
            </span>
        @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('record_mileage') ? ' has-error' : '' }}">
        {!! Form::label('record_mileage', 'Record mileage', array('class' => 'col-md-4 control-label')) !!}
        <div class="col-md-6">
        {!! Form::select('record_mileage', array('0'=>'No','1'=>'Yes'), null,  array('class' => 'form-control')) !!}

        @if ($errors->has('record_mileage'))
            <span class="help-block">
                <strong>{{ $errors->first('record_mileage') }}</strong>
            </span>
        @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('privileges') ? ' has-error' : '' }}">
        {!! Form::label('privileges', 'Privileges', array('class' => 'col-md-4 control-label')) !!}

        <div class="col-md-6">
            {!! Form::text('privileges', null,  array('class' => 'form-control')) !!}

            @if ($errors->has('privileges'))
                <span class="help-block">
                    <strong>{{ $errors->first('privileges') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">

        <div class="col-md-6">
            {!! Form::hidden('user_id', null,  array('class' => 'form-control')) !!}      
        </div>
    </div>







</div>

