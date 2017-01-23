@extends('layouts.app');


@section('content')
<div class="container">

	<h1>Type Categories Edit Form</h1>

	<h3>{{ $type->name }}</h3>



	<div class="row">
		<div class="col-md-6 col-xs-12">
			{!! Form::open(['route' => ['typecategory.update',$type->typeid], 'method' => 'PATCH']) !!}

			<div class="form-group">
				<label for="categories[]" >Display Categories</label>
				<select class="form-control" id="categories" name="categories[]" multiple="multiple" size="20">
				<option value=0 >----None----</option>
				@foreach($categories as $category)

				<option {{ $type->categories->contains($category->id) ? ' selected ' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
					@foreach($category->children as $child)
						<option {{ $type->categories->contains($child->id) ? ' selected ' : '' }} value="{{ $child->id }}">-- {{ $child->name }}</option>
					@endforeach

				@endforeach

				</select>
			</div>



			<button type="submit" name="b" class="btn btn-primary">Update</button>

			{!! Form::close() !!}
		</div>
	</div>

</div>


@endsection
