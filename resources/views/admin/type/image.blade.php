@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Product Images</h3>

                </div>

                <div class="panel-body">
                     <div class="row" style="padding-top:10px;">
                        <div class="col-xs-2">
                          <button id="uploadBtn" class="btn btn-large btn-primary">Choose File</button>
                        </div>
                        <div class="col-xs-10">
                      <div id="progressOuter" class="progress progress-striped active" style="display:none;">
                        <div id="progressBar" class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        </div>
                      </div>
                        </div>
                    </div>

                      <div class="row" style="padding-top:10px;">
                        <div class="col-xs-10">
                          <div id="msgBox">
                          </div>
                        </div>
                      </div>

                      @if ($images->count())
                        <ul id="sortable" class="sortable">
                         @foreach ($images as $i)
                         <li id="item-{{ $i->id }}"><img src="{{ url('/source/tn/'.$i->filename.'?'.$i->updated_at->timestamp) }}" /> <a title="delete image" class="pull-right" style="padding: 10px"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a> </li>

                         @endforeach
                         </ul>

                     @endif





                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script> var typeid = {{ $typeid }};</script>
<script src="{{ url(elixir('js/imageuploader.js')) }}"></script>
@endsection
