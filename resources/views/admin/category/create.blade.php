@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Category: create</div>

                <div class="panel-body">


                    {!! Form::model($category, array('route' => array('category.store', $category->id ), 'method' => 'post' )) !!}

                    @include('admin.category.partials.form')

                     <button type="submit"  value="Create" class="btn btn-primary" >Create</button>


                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
