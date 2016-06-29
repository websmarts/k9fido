@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Product Types</div>

                <div class="panel-body">
                    <h3>Edit Product Type ({{ $type->typeid  }})</h3>

                    {!! Form::model($type, array('route' => array('type.update', $type->typeid ), 'method' => 'patch' )) !!}

                     @include('admin.type.partials.form')

                     <div class="col-md-12">
                     <button type="submit"  value="Save" class="btn btn-primary" >Save</button>
                     </div>

                     {!! Form::close() !!}

                     @include('admin.type.partials.type_options',['hide_add_link'=>false,'hide_edit_link'=>false])

                     @include('admin.type.partials.type_products')

                     @include('admin.type.partials.type_categories')


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
