@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Category: edit</div>

                <div class="panel-body">


                    {!! Form::model($category, array('route' => array('category.update', $category->id ), 'method' => 'patch' )) !!}

                    @include('admin.category.partials.form')

                    <div class="form-horizontal">
                        <div class="form-group{{ $errors->has('') ? ' has-error' : '' }}">
                            {!! Form::label('_delete', 'Delete', array('class' => 'col-md-4 control-label')) !!}
                            <div class="col-md-6">
                            {!! Form::checkbox('_delete', $category->id ) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-horizontal">
                    <button type="submit"  value="Save" class="btn btn-primary pull-right" >Save</button>
                    </div>


                    {!! Form::close() !!}



                    @if($category->parent_id == 0)
                        @if ($category->children->count() > 0)
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Category </th>
                                <th>Display Order</th>
                            </tr>
                        </thead>
                        <tbody>



                        @foreach($category->children as $child)
                            <tr>
                                <td style="padding-left: 50px;">{{ $child->name }}</td>
                                <td style="padding-left: 50px;">{{ $child->display_order }}</td>
                                <td width="20"><a href="{{ route('category.edit', ['id' => $child->id] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
                            </tr>
                        @endforeach

                        </tbody>
                        </table>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
