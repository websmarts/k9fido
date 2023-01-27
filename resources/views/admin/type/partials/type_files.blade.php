<div class="col-md-12">

     <hr>

     <h3>Type Files</h3>
     <p><a href="{{ route('producttypefile.index',['id'=>$type->typeid]) }}">Manage files</a></p>

     @if ($files && $files->count())
        <table class="table">
          <thead>
               <tr>
                    <th>Filename</th>
                    <th>Title</th>
                    <th>Description</th>
               </tr>
          </thead>
          <tbody>
          @foreach ($files as $i)
               <tr>
                    <td><a href="{{ url($i->filepath.$i->filename) }}" target="_blank" >{{$i->filename}}</a></td>
                    <td>{{ $i->title }}</td>
                    <td>{{ $i->description }}</td>
               </tr>
          @endforeach
          </tbody>
          

        </table>
         

     @endif


</div>
