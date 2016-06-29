
 <div class="col-md-12" >
 <hr>
 <h3>Product Type Options</h3>

@unless( $hide_add_link)
 <p><a href="{{ route('typeoption.create') }}?typeid={{ $type->typeid }}">Add a product option</a></p>
@endunless

 @if ($type->options->count())
    <table class="table table-striped">
    <thead>
    <tr>
        <th>Option Code</th>
        <th>Option Description</th>
        <th>Option Class</th>

        @unless($hide_edit_link)
        <th>&nbsp;</th>
        @endunless

    </tr>
    </thead>
    <tbody>
     @foreach ($type->options as $opt)
     <tr>
        <td>{{ $opt->opt_code }}</td>
        <td>{{ $opt->opt_desc }}</td>
        <td>{{ $opt->opt_class }}</td>

        @unless($hide_edit_link)
        <td><a href="{{ route('typeoption.edit', ['id' => $opt->id] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
        @endunless

    </tr>
     @endforeach
     </tbody>
     </table>

 @endif

</div>
