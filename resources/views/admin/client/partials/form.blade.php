                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {!! Form::label('name', 'Trading name', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('name', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                     <div class="form-group{{ $errors->has('parent') ? ' has-error' : '' }}">
                        {!! Form::label('parent', 'Parent company', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('parent',  [''=>'Select if has parent company ....'] + $clients,null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('parent'))
                            <span class="help-block">
                                <strong>{{ $errors->first('parent') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('address1') ? ' has-error' : '' }}">
                        {!! Form::label('address1', 'Address1', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('address1', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('address1'))
                            <span class="help-block">
                                <strong>{{ $errors->first('address1') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('address2') ? ' has-error' : '' }}">
                        {!! Form::label('address2', 'Address2', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('address2', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('address2'))
                            <span class="help-block">
                                <strong>{{ $errors->first('address2') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('address3') ? ' has-error' : '' }}">
                        {!! Form::label('address3', 'Address3', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('address3', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('address3'))
                            <span class="help-block">
                                <strong>{{ $errors->first('address3') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                        {!! Form::label('city', 'Suburb/City', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('city', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('city'))
                            <span class="help-block">
                                <strong>{{ $errors->first('city') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('postcode') ? ' has-error' : '' }}">
                        {!! Form::label('postcode', 'Postcode', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('postcode', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('postcode'))
                            <span class="help-block">
                                <strong>{{ $errors->first('postcode') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
<?php
$states = [
    '' => 'Select State ....',

    'VIC' => 'VIC',
    'NSW' => 'NSW',
    'QLD' => 'QLD',
    'NT' => 'NT',
    'WA' => 'WA',
    'SA' => 'SA',
    'TAS' => 'TAS',
    'ACT' => 'ACT',
    'OTHER' => 'OTHER',
];
?>
                    <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                        {!! Form::label('state', 'State', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('state', $states, null,  array('class' => 'form-control')) !!}


                        @if ($errors->has('state'))
                            <span class="help-block">
                                <strong>{{ $errors->first('state') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <!-- <div class="form-group{{ $errors->has('phone_area_code') ? ' has-error' : '' }}">
                        {!! Form::label('phone_area_code', 'Phone area code', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('phone_area_code', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('phone_area_code'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone_area_code') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div> -->

                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        {!! Form::label('phone', 'Phone', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('phone', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('phone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('phone2') ? ' has-error' : '' }}">
                        {!! Form::label('phone2', 'Phone2', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('phone2', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('phone2'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone2') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                        {!! Form::label('mobile', 'Mobile', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('mobile', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('mobile'))
                            <span class="help-block">
                                <strong>{{ $errors->first('mobile') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('fax') ? ' has-error' : '' }}">
                        {!! Form::label('fax', 'Fax', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('fax', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('fax'))
                            <span class="help-block">
                                <strong>{{ $errors->first('fax') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('contacts') ? ' has-error' : '' }}">
                        {!! Form::label('contacts', 'Contacts - owner', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('contacts', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('contacts'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contacts') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('contacts_2') ? ' has-error' : '' }}">
                        {!! Form::label('contacts_2', 'Contacts - instore', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('contacts_2', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('contacts_2'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contacts_2') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('contacts_3') ? ' has-error' : '' }}">
                        {!! Form::label('contacts_3', 'Contacts - ordering', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('contacts_3', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('contacts_3'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contacts_3') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <!-- <div class="form-group{{ $errors->has('call_interval') ? ' has-error' : '' }}">
                        {!! Form::label('call_interval', 'Call interval', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('call_interval', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('call_interval'))
                            <span class="help-block">
                                <strong>{{ $errors->first('call_interval') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div> -->

                    <!-- <div class="form-group{{ $errors->has('alert') ? ' has-error' : '' }}">
                        {!! Form::label('alert', 'Alert', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('alert', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('alert'))
                            <span class="help-block">
                                <strong>{{ $errors->first('alert') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div> -->

                    <div class="form-group{{ $errors->has('salesrep') ? ' has-error' : '' }}">
                        {!! Form::label('salesrep', 'Salesrep', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('salesrep', [0=>'Select rep...'] + $salesreps, null,  array('class' => 'form-control')) !!}


                        @if ($errors->has('salesrep'))
                            <span class="help-block">
                                <strong>{{ $errors->first('salesrep') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('myob_card_id') ? ' has-error' : '' }}">
                        {!! Form::label('myob_card_id', 'Myob card id', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('myob_card_id', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('myob_card_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('myob_card_id') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('myob_record_id') ? ' has-error' : '' }}">
                        {!! Form::label('myob_record_id', 'Myob record id', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('myob_record_id', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('myob_record_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('myob_record_id') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <!-- <div class="form-group{{ $errors->has('longitude') ? ' has-error' : '' }}">
                        {!! Form::label('longitude', 'Longitude', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('longitude', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('longitude'))
                            <span class="help-block">
                                <strong>{{ $errors->first('longitude') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div> -->

                    <!-- <div class="form-group{{ $errors->has('latitude') ? ' has-error' : '' }}">
                        {!! Form::label('latitude', 'Latitude', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('latitude', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('latitude'))
                            <span class="help-block">
                                <strong>{{ $errors->first('latitude') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div> -->

                    <!-- <div class="form-group{{ $errors->has('sales_rating') ? ' has-error' : '' }}">
                        {!! Form::label('sales_rating', 'Sales rating', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('sales_rating', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('sales_rating'))
                            <span class="help-block">
                                <strong>{{ $errors->first('sales_rating') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div> -->

                   <!--  <div class="form-group{{ $errors->has('client_type') ? ' has-error' : '' }}">
                        {!! Form::label('client_type', 'Client type', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('client_type', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('client_type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('client_type') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div> -->

                    <div class="form-group{{ $errors->has('call_frequency') ? ' has-error' : '' }}">
                        {!! Form::label('call_frequency', 'Call frequency/cycle (days)', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('call_frequency', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('call_frequency'))
                            <span class="help-block">
                                <strong>{{ $errors->first('call_frequency') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <!-- <div class="form-group{{ $errors->has('call_planning_note') ? ' has-error' : '' }}">
                        {!! Form::label('call_planning_note', 'Call planning note', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('call_planning_note', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('call_planning_note'))
                            <span class="help-block">
                                <strong>{{ $errors->first('call_planning_note') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div> -->

                    <div class="form-group{{ $errors->has('login_user') ? ' has-error' : '' }}">
                        {!! Form::label('login_user', 'Login user (web)', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('login_user', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('login_user'))
                            <span class="help-block">
                                <strong>{{ $errors->first('login_user') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('login_pass') ? ' has-error' : '' }}">
                        {!! Form::label('login_pass', 'Login pass (web)', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('login_pass', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('login_pass'))
                            <span class="help-block">
                                <strong>{{ $errors->first('login_pass') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>


                    <?php
$onlineStatusOptions = [
    '' => 'Select option ...',
    'active' => 'Active',
    'pendibg_activation' => 'Pending activation',
]
?>
                    <div class="form-group{{ $errors->has('online_status') ? ' has-error' : '' }}">
                        {!! Form::label('online_status', 'Online status (web)', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('online_status', $onlineStatusOptions, null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('online_status'))
                            <span class="help-block">
                                <strong>{{ $errors->first('online_status') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('online_validation_key') ? ' has-error' : '' }}">
                        {!! Form::label('online_validation_key', 'Online validation key (web)', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('online_validation_key', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('online_validation_key'))
                            <span class="help-block">
                                <strong>{{ $errors->first('online_validation_key') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('online_contact') ? ' has-error' : '' }}">
                        {!! Form::label('online_contact', 'Online contact (web)', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('online_contact', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('online_contact'))
                            <span class="help-block">
                                <strong>{{ $errors->first('online_contact') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email_1') ? ' has-error' : '' }}">
                        {!! Form::label('email_1', 'Email (1) contact', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('email_1', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('email_1'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email_1') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('email_2') ? ' has-error' : '' }}">
                        {!! Form::label('email_2', 'Email (2) contact', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('email_2', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('email_2'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email_2') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('email_3') ? ' has-error' : '' }}">
                        {!! Form::label('email_3', 'Email (3) contact', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('email_3', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('email_3'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email_3') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

<?php
$levelOptions = [
    'AAA' => 'AAA',
    'AA' => 'AA',
    'A' => 'A',
    'B' => 'B',
    'C' => 'C',
    'D' => 'D',
    'E' => 'E',
    'F' => 'F',

];?>

                    <div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                        {!! Form::label('level', 'Level', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('level', [''=>'Select level ...'] + $levelOptions, null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('level'))
                            <span class="help-block">
                                <strong>{{ $errors->first('level') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

<?php
$customFreightOptions = [
    '0' => 'No',
    '1' => 'Yes',
];
?>

                    <div class="form-group{{ $errors->has('custom_freight') ? ' has-error' : '' }}">

                        {!! Form::label('custom_freight', 'Custom freight', array('class' => 'col-md-4 control-label', 'method' => 'patch')) !!}
                        <div class="col-md-6">
                        {!! Form::select('custom_freight',$customFreightOptions,  null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('custom_freight'))
                            <span class="help-block">
                                <strong>{{ $errors->first('custom_freight') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('freight_notes') ? ' has-error' : '' }}">
                        {!! Form::label('freight_notes', 'Freight notes', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('freight_notes', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('freight_notes'))
                            <span class="help-block">
                                <strong>{{ $errors->first('freight_notes') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                        {!! Form::label('status', 'Status', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('status', array('active'=>'Active','inactive'=>'Inactive'), null ,  array('class' => 'form-control')) !!}

                        @if ($errors->has('status'))
                            <span class="help-block">
                                <strong>{{ $errors->first('status') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('client_note') ? ' has-error' : '' }}">
                        {!! Form::label('client_note', 'Client note', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::textarea('client_note', null ,  array('class' => 'form-control')) !!}

                        @if ($errors->has('client_note'))
                            <span class="help-block">
                                <strong>{{ $errors->first('client_note') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>