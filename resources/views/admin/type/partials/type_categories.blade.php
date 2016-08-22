
 <div class="col-md-12" >
 <hr>
 <h3>Type Categories</h3>
 <p><a href="{{ route('typecategory.edit',$type->typeid) }}">Edit Type Categories</a></p>

 @if ($type->categories->count())
    <table class="table table-striped">
    <thead>
    <tr>
        <th>Type Categories</th>

    </tr>
    </thead>
    <tbody>
     @foreach ($type->categories as $category)
     <tr>
        <td>{{ $category->name }}</td>
    </tr>
     @endforeach
     </tbody>
     </table>

 @endif

</div>
