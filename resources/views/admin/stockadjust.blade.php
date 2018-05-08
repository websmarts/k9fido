@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 280px;">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div id="app">
              <stockadjuster></stockadjuster>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
  var pageVar = {
    url: "{{ url('stockadjust/') }}",
  }
</script>

<script src="{{ elixir('js/stockadjuster.js') }}"></script>

@endsection
