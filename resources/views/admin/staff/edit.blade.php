@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Staff member</h3></div>

                <div class="panel-body">


                    {!! Form::model($user, array('route' => array('staff.update', $user->id ), 'method' => 'patch' )) !!}


                    <div class="col-md-12">
                    <button type="submit"  value="Save" class="btn btn-primary pull-right" >Save</button>
                    </div>


                    @include('admin.staff.form')

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
