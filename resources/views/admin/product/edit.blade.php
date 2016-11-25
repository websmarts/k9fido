@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Product: {{ $product->product_code  }}</div>

                <div class="panel-body">


                    {!! Form::model($product, array('route' => array('product.update', $product->id ), 'method' => 'patch' )) !!}


                    <div class="col-md-12">
                         <button type="submit"  value="Save" class="btn btn-primary pull-right" >Save</button>
                         </div>

                    <div class="form-group{{ $errors->has('product_code') ? ' has-error' : '' }}">
                        {!! Form::label('product_code', 'Product code', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('product_code', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('product_code'))
                            <span class="help-block">
                                <strong>{{ $errors->first('product_code') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('typeid') ? ' has-error' : '' }}">
                        <label for="typeid" class="col-md-4 control-label">TypeId <a href="{{ route('type.edit',['type'=>$product->typeid]) }}">edit</a></label>
                        <div class="col-md-6">
                        {!! Form::select('typeid', ( $productTypes), null, array('class' => 'form-control')) !!}

                        @if ($errors->has('typeid'))
                            <span class="help-block">
                                <strong>{{ $errors->first('typeid') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        {!! Form::label('description', 'Description', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('description', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('size') ? ' has-error' : '' }}">
                        {!! Form::label('size', 'Size', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('size', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('size'))
                            <span class="help-block">
                                <strong>{{ $errors->first('size') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                        {!! Form::label('price', 'Price', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('price', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('price'))
                            <span class="help-block">
                                <strong>{{ $errors->first('price') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('qty_break') ? ' has-error' : '' }}">
                        {!! Form::label('qty_break', 'Qty break', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('qty_break', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('qty_break'))
                            <span class="help-block">
                                <strong>{{ $errors->first('qty_break') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('qty_discount') ? ' has-error' : '' }}">
                        {!! Form::label('qty_discount', 'Qty discount', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('qty_discount', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('qty_discount'))
                            <span class="help-block">
                                <strong>{{ $errors->first('qty_discount') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('qty_instock') ? ' has-error' : '' }}">
                        {!! Form::label('qty_instock', 'Qty instock', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('qty_instock', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('qty_instock'))
                            <span class="help-block">
                                <strong>{{ $errors->first('qty_instock') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('qty_ordered') ? ' has-error' : '' }}">
                        {!! Form::label('qty_ordered', 'Qty ordered', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('qty_ordered', null,  array('class' => 'form-control', 'disabled'=>'disabled')) !!}

                        @if ($errors->has('qty_ordered'))
                            <span class="help-block">
                                <strong>{{ $errors->first('qty_ordered') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('special') ? ' has-error' : '' }}">
                        {!! Form::label('special', 'Special', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('special',['0'=>'No','1'=>'Yes'], null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('special'))
                            <span class="help-block">
                                <strong>{{ $errors->first('special') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('clearance') ? ' has-error' : '' }}">
                        {!! Form::label('clearance', 'Clearance', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('clearance',['0'=>'No','1'=>'Yes'], null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('clearance'))
                            <span class="help-block">
                                <strong>{{ $errors->first('clearance') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('can_backorder') ? ' has-error' : '' }}">
                        {!! Form::label('can_backorder', 'Can backorder', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('can_backorder',['n'=>'No','y'=>'Yes'], null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('can_backorder'))
                            <span class="help-block">
                                <strong>{{ $errors->first('can_backorder') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                        {!! Form::label('status', 'Status', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('status', Appdata::get('product.status.options') ,null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('status'))
                            <span class="help-block">
                                <strong>{{ $errors->first('status') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('rrp') ? ' has-error' : '' }}">
                        {!! Form::label('rrp', 'Rrp', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('rrp', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('rrp'))
                            <span class="help-block">
                                <strong>{{ $errors->first('rrp') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('cost') ? ' has-error' : '' }}">
                        {!! Form::label('cost', 'Cost', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('cost', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('cost'))
                            <span class="help-block">
                                <strong>{{ $errors->first('cost') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('last_costed_date') ? ' has-error' : '' }}">
                        {!! Form::label('last_costed_date', 'Last costed date', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('last_costed_date', null,  array('class' => 'form-control datepicker')) !!}

                        @if ($errors->has('last_costed_date'))
                            <span class="help-block">
                                <strong>{{ $errors->first('last_costed_date') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('supplier') ? ' has-error' : '' }}">
                        {!! Form::label('supplier', 'Supplier', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('supplier', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('supplier'))
                            <span class="help-block">
                                <strong>{{ $errors->first('supplier') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('width') ? ' has-error' : '' }}">
                        {!! Form::label('width', 'Width', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('width', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('width'))
                            <span class="help-block">
                                <strong>{{ $errors->first('width') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('height') ? ' has-error' : '' }}">
                        {!! Form::label('height', 'Height', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('height', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('height'))
                            <span class="help-block">
                                <strong>{{ $errors->first('height') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('length') ? ' has-error' : '' }}">
                        {!! Form::label('length', 'Length', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('length', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('length'))
                            <span class="help-block">
                                <strong>{{ $errors->first('length') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('shipping_volume') ? ' has-error' : '' }}">
                        {!! Form::label('shipping_volume', 'Shipping volume', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('shipping_volume', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('shipping_volume'))
                            <span class="help-block">
                                <strong>{{ $errors->first('shipping_volume') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('shipping_weight') ? ' has-error' : '' }}">
                        {!! Form::label('shipping_weight', 'Shipping weight', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('shipping_weight', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('shipping_weight'))
                            <span class="help-block">
                                <strong>{{ $errors->first('shipping_weight') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('shipping_container') ? ' has-error' : '' }}">
                        {!! Form::label('shipping_container', 'Shipping container', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('shipping_container', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('shipping_container'))
                            <span class="help-block">
                                <strong>{{ $errors->first('shipping_container') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('display_order') ? ' has-error' : '' }}">
                        {!! Form::label('display_order', 'Display order', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('display_order', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('display_order'))
                            <span class="help-block">
                                <strong>{{ $errors->first('display_order') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('barcode') ? ' has-error' : '' }}">
                        {!! Form::label('barcode', 'Barcode', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('barcode', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('barcode'))
                            <span class="help-block">
                                <strong>{{ $errors->first('barcode') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('color_name') ? ' has-error' : '' }}">
                        {!! Form::label('color_name', 'Color name', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('color_name', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('color_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('color_name') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('color_background_color') ? ' has-error' : '' }}">
                        {!! Form::label('color_background_color', 'Color background color', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('color_background_color', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('color_background_color'))
                            <span class="help-block">
                                <strong>{{ $errors->first('color_background_color') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('color_background_image') ? ' has-error' : '' }}">
                        {!! Form::label('color_background_image', 'Color background image', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('color_background_image', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('color_background_image'))
                            <span class="help-block">
                                <strong>{{ $errors->first('color_background_image') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('notify_when_instock') ? ' has-error' : '' }}">
                        {!! Form::label('notify_when_instock', 'Notify when instock', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('notify_when_instock', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('notify_when_instock'))
                            <span class="help-block">
                                <strong>{{ $errors->first('notify_when_instock') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('source') ? ' has-error' : '' }}">
                        {!! Form::label('source', 'Source', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('source', Appdata::get('product.source.options'), null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('source'))
                            <span class="help-block">
                                <strong>{{ $errors->first('source') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('new_product') ? ' has-error' : '' }}">
                        {!! Form::label('new_product', 'New product', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('new_product',Appdata::get('product.new_product.options'), null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('new_product'))
                            <span class="help-block">
                                <strong>{{ $errors->first('new_product') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('core_product') ? ' has-error' : '' }}">
                        {!! Form::label('core_product', 'Core product', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('core_product',Appdata::get('product.core_product.options'), null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('core_product'))
                            <span class="help-block">
                                <strong>{{ $errors->first('core_product') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('low_stock_level') ? ' has-error' : '' }}">
                        {!! Form::label('low_stock_level', 'Low stock level', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('low_stock_level', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('low_stock_level'))
                            <span class="help-block">
                                <strong>{{ $errors->first('low_stock_level') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                         <button type="submit"  value="Save" class="btn btn-primary pull-right" >Save</button>
                         </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    @if($product->bom->count())
     <div class="row">
        <div class="col-md-6 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Bill of Material: {{ dump($product->bom)  }}</div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                        </head>
                        <tbody>
                        @foreach($product->bom as $item)
                        <tr>
                        <td>{{ $item->item_product_code }}</td>
                        <td>{{ $item->item_qty }}</td>
                        <td>{{ is_null($item->item_price) ? '-' :  $item->item_price }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <a class="btn btn-primary pull-right" href="{{ route('bom.edit',$item->parent_product_code) }}">Edit BoM</a>
                </div>
            </div>
        </div>
        @endif;
    </div>


</div>

@endsection

@section('script')
<script>
  $( function() {
    $( ".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd'
    });
  } );
  </script>


@endsection
