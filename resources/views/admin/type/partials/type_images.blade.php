<div class="col-md-12">

     <hr>

     <h3>Type Images</h3>
     <p><a href="{{ route('producttypeimage.index',['id'=>$type->typeid]) }}">Manage images</a></p>

     @if ($images->count())
        <ul id="sortable" class="sortable">
         @foreach ($images as $i)
         <li id="item-{{ $i->id }}"><img src="{{ url('/source/tn/'.$i->filename.'?'.$i->updated_at->timestamp) }}" /> {{ $i->filename }} </li>

         @endforeach
         </ul>

     @endif


</div>
