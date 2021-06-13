@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Import Products from Excel</h3>


                   

                    

                </div>

                <div class="panel-body">

                

                    <div class="row">
                        



                        <div class="col-md-12" style="padding-bottom: 4px">
                        <form method="post" action="{{ url('import/products') }}"  enctype="multipart/form-data" id="product_import_form">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="row" style="margin-top:20px;">
                                    <div class="col-xs-12">
                                        <label for="spreadsheet" class="control-label">Products spreadsheet</label>
                                        <input type="file" class="form-control" id="spreadsheet" name="spreadsheet" value="{{ old('spreadsheet') }}" />
                                        @if ($errors->has('spreadsheet'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('spreadsheet') }}</strong>
                                            </span>
                                        @endif
                                        <div style="margin-top:20px">
                                        <button type="submit" name="b" class="btn btn-secondary"> Import Products Spreadsheet into eCatalog</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        

                        </form>

                        


                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection