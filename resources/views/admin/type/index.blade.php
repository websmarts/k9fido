@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Products</div>

                <div class="panel-body">
                    <h3>Product Type list</h3>

                    <p><a href="{{ route('type.create') }}">Create a new product type</a></p>

                    <table class="table table-striped">
                    <tbody>
                    @foreach($types as $type)
                    <tr>
                    	<td>{{ $type->name }}</td>
                    	<td width="20"><a href="{{ route('type.show', ['id' => $type->typeid] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
                    </tr>
                    @endforeach

                    </tbody>
                    </table>
                    {{ $types->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
