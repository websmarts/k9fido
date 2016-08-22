@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Categories

                </div>

                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-6"><h3>Category list</h3></div>
                        <div class="col-md-6"><a href="{{ route('category.create') }}"><button class="btn btn-primary pull-right"><i class="fa fa-plus-square-o fa-1x"></i> Create a new category</button></a></div>
                    </div>




                    <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Category </th>
                        <th>Description</th>
                        <th>Display Order</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                    <tr>
                    	<td>{{ $category->name }} </td>
                        <td>{{ $category->description }}</td>
                        <td>{{ $category->display_order }}</td>
                    	<td width="20"><a href="{{ route('category.edit', ['id' => $category->id] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
                    </tr>
                    @if( count($category->children))
                        @foreach ($category->children->sortByDesc('display_order') as $child)
                        <tr>
                        <td style="padding-left: 50px;">{{ $child->name }}</td>
                        <td>{{ $child->description }}</td>
                        <td style="padding-left: 50px;">{{ $child->display_order }}</td>
                        <td width="20"><a href="{{ route('category.edit', ['id' => $child->id] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
                    </tr>
                        @endforeach
                    @endif


                    @endforeach

                    </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
