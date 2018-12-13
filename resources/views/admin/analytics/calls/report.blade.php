@extends('layouts.app')

@section('content')
<h1>Report content</h1>
<div id="table" class="col-xs-12 table-responsive">
    <datatable :columns="columns" :data="rows"></datatable>
</div>

@endsection

@section('script')

<script src="{{ elixir('js/call_planner.js') }}"></script>

@endsection