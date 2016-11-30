@extends('layouts.app')

@section('content')
{{-- dump($id) --}}
<h3>The order{{ is_array($id) ? 's' : ''}} you selected will download shortly</h3>


@endsection

@section('script')
<script>
var url = "{{ route('order.download') }}";
//console.log(url)

window.location = url;
</script>

@endsection
