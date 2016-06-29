
 <div class="col-md-12" >
 <hr>
 <h3>Type Categories</h3>
 <p><a href="{{ route('category.create') }}?typeid={{ $type->typeid }}">Add a Category</a></p>

 @if ($type->categories->count())
    <table class="table table-striped">
    <thead>
    <tr>
        <th>Category</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
     @foreach ($type->categories as $category)
     <tr>
        <td>{{ $category->name }}</td>

        <td><a href="{{ route('category.edit', ['id' => $category->id] ) }}?typeid={{ $type->typeid }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
    </tr>
     @endforeach
     </tbody>
     </table>

 @endif

</div>
