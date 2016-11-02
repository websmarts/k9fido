@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px;">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div id="app">

              <picker-form order-id="{{ $order->id }}"></picker-form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
  var pageVar = {
    orderId: "{{ $order->id }}",
    url: "{{ url('ajax/pickorder/'.$order->id) }}",
    startTime: new Date().getTime()
  }
</script>
<script src="{{ elixir('js/orderpicker.js') }}"></script>

@endsection
