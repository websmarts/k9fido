@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Shopify Utilities</h3>


                   

                    

                </div>

                <div class="panel-body">

                

                    <div class="row">
                        



                        <div class="col-md-12" style="padding-bottom: 4px">
                        <form method="post" action="shopify/import"  enctype="multipart/form-data" id="shopify_import_form">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="row" style="margin-top:20px;">
                                    <div class="col-xs-12">
                                        <label for="spreadsheet" class="control-label">Shopify spreadsheet</label>
                                        <input type="file" class="form-control" id="spreadsheet" name="spreadsheet" value="{{ old('spreadsheet') }}" />
                                        @if ($errors->has('spreadsheet'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('spreadsheet') }}</strong>
                                            </span>
                                        @endif
                                        <button type="submit" name="b" class="btn btn-default"> Import Shopify Spreadsheet into eCatalog</button>
                                    </div>
                                </div>
                            </div>

                        

                        </form>

                        


                           <a href="{{ route('shopify.export') }}"><button style="margin-right:20px" class="btn btn-secondary pull-right"><i class="fa fa-plus-square-o fa-1x"></i> Download Shopify Export Spreadsheet</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection