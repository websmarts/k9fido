<div class="col-md-12">

     <hr>
     <h3>Type Products</h3>
     <p><a href="{{ route('product.create') }}?typeid={{ $type->typeid }}">Add a product</a></p>

     @if ($type->products->count())
        <table class="table table-striped">
        <thead>
        <tr>
            <th>Product Code</th>
            <th>Description</th>
            <th>Size</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
         @foreach ($type->products as $p)
         <tr>
            <td>{{ $p->product_code }}</td>
            <td>{{ $p->description }}</td>
            <td>{{ $p->size}}</td>
            <td><a href="{{ route('product.edit', ['id' => $p->id] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
        </tr>
         @endforeach
         </tbody>
         </table>

     @endif


</div>
