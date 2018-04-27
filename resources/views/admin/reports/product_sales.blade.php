@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Product Sales Report </h2>
    <p>This report highlights what rate products sell and by how many resellers</p>
<table>
    <tr><th>Product_code</th><th>Resellers</th></tr>
    @foreach($results as $pc => $r)

    <tr><td>{{ $pc }}</td><td>{{ count($r) }}</td></tr>


    @endforeach
</table>
</div>
@endsection
