@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Data utilities</div>

                <div class="panel-body">
                    <div>If spreadsheet row has an ID then the row with that ID will be updated in the database. If the row does not have an ID, but has a product_code,
                        then the row will be inserted into the database. Note the rows missing a TYPEID will be skipped and not inserted into the database.</div>
                    <div> <a href="{{ url('import/products') }}"><button class="btn btn-secondary" style="margin-right:20px"><i class="fa fa-plus-square-o fa-1x"></i> Import products from spreadsheet</button></a></div>


                    <div style="margin-top:20px;">Export option exports every row in the database</div>
                    <div>
                        <a href="{{ url('export/products') }}"><button class="btn btn-secondary" style="margin-right:20px"><i class="fa fa-plus-square-o fa-1x"></i> Export all products to spreadsheet</button></a>
                    </div>




                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Sales and Marketing Utilities</div>

                <div class="panel-body">
                    <ul>
                        <li><a target="_utils" href="{{ OFFICE_BASE_URL }}client/notifies">Client notifies</a></li>
                        <li><a target="_utils" href="{{ ECAT_BASE_URL }}sales_analyser.php">Sales Analyser</a></li>
                        <li><a target="_utils" href="{{ OFFICE_BASE_URL }}product/catalog">Print Catalog </a></li>
                    </ul>


                </div>
            </div>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Business Analytics</div>

                <div class="panel-body">
                    <ul>
                        <li><a href="{{ route('analytics.customers')}}">Customers</a></li>
                        <li><a href="{{ route('analytics.orders')}}">Orders</a></li>
                        <li><a href="{{ route('analytics.calls')}}">Call Planner</a></li>
                    </ul>


                </div>
            </div>
        </div>
    </div> -->
</div>

@endsection