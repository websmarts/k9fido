                    <div class="form-group{{ $errors->has('product_code') ? ' has-error' : '' }}">
                        {!! Form::label('product_code', 'Product code', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        <?php $key = $product->id ? 'disabled' : 'enabled';?>
                        {!! Form::text('product_code', null,  array('class' => 'form-control', $key =>'true')) !!}

                        @if ($errors->has('product_code'))
                            <span class="help-block">
                                <strong>{{ $errors->first('product_code') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('typeid') ? ' has-error' : '' }}">
                        {!! Form::label('typeid', 'Typeid', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('typeid', ([0 => 'Select a type...'] + $productTypes), null, array('class' => 'form-control')) !!}

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
                        {!! Form::label('price', 'Price (cents)', array('class' => 'col-md-4 control-label')) !!}
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
                        {!! Form::label('qty_break', 'Qty break (pieces)', array('class' => 'col-md-4 control-label')) !!}
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
                        {!! Form::label('qty_discount', 'Qty discount (%)', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('qty_discount', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('qty_discount'))
                            <span class="help-block">
                                <strong>{{ $errors->first('qty_discount') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('qty_ordered') ? ' has-error' : '' }}">
                        {!! Form::label('qty_ordered', 'Qty on order', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                            {!! Form::hidden('qty_ordered', null,  array('class' => 'form-control')) !!}

                        {{ $product->qty_ordered }}
                        @if($product->qty_ordered > 0)
                           &nbsp;&nbsp; <a href="/product/{{ $product->id }}/orders">Show orders</a>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('qty_instock') ? ' has-error' : '' }}">
                        {!! Form::label('qty_instock', 'Qty available', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {{ $product->qty_instock }}

                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('qty_onshelf') ? ' has-error' : '' }}">
                        {!! Form::label('qty_onshelf', 'Qty shelf', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('qty_onshelf', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('qty_onshelf'))
                            <span class="help-block">
                                <strong>{{ $errors->first('qty_onshelf') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>



                    <div class="form-group{{ $errors->has('special') ? ' has-error' : '' }}">
                        {!! Form::label('special', 'Special', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('special', [''=>'Select option ...', '1'=>'Yes','0'=>'No'], null,  array('class' => 'form-control')) !!}

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
                        {!! Form::select('clearance', [''=>'Select option ...', '1'=>'Yes','0'=>'No'], null,  array('class' => 'form-control')) !!}

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
                        {!! Form::select('can_backorder', [''=>'Select option ...', 'y'=>'Yes','n'=>'No'], null,  array('class' => 'form-control')) !!}

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
                        {!! Form::select('status', [''=>'Select status ...','active'=>'Active','inactive'=>'In Active','pending'=>'Pending'],null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('status'))
                            <span class="help-block">
                                <strong>{{ $errors->first('status') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('rrp') ? ' has-error' : '' }}">
                        {!! Form::label('rrp', 'RRP (cents)', array('class' => 'col-md-4 control-label')) !!}
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
                        {!! Form::label('cost', 'Cost (cents)', array('class' => 'col-md-4 control-label')) !!}
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
                        {!! Form::label('last_costed_date', 'Last costed date (YYYY-MM-DD)', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('last_costed_date', null,  array('class' => 'form-control')) !!}

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

                    <div class="form-group{{ $errors->has('length') ? ' has-error' : '' }}">
                        {!! Form::label('length', 'Length (cm)', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('length', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('length'))
                            <span class="help-block">
                                <strong>{{ $errors->first('length') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('width') ? ' has-error' : '' }}">
                        {!! Form::label('width', 'Width (cm)', array('class' => 'col-md-4 control-label')) !!}
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
                        {!! Form::label('height', 'Height (cm)', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('height', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('height'))
                            <span class="help-block">
                                <strong>{{ $errors->first('height') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>


                    <div class="form-group{{ $errors->has('shipping_volume') ? ' has-error' : '' }}">
                        <!-- {!! Form::label('shipping_volume', 'Shipping volume( Calculated field = WxH*L / 1,000,000 )', array('class' => 'col-md-4 control-label')) !!} -->
                        <label for="shipping_volume" class="col-md-4 control-label">Shipping volume <br /><span>( Calculated field = WxH*L / 1,000,000 )<span></label>
                        <div class="col-md-6">
                        {{ $product->shipping_volume }}

                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('shipping_weight') ? ' has-error' : '' }}">
                        <!-- {!! Form::label('shipping_weight', 'Shipping weight ( Calculated as Shipping Volume x 250 )', array('class' => 'col-md-4 control-label')) !!} -->
                        <label for="shipping_weight" class="col-md-4 control-label">Shipping weight <br /><span>( Calculated as Shipping Volume x 250 )<span></label>
                        <div class="col-md-6">
                        {{ $product->shipping_weight }}

                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('actual_weight') ? ' has-error' : '' }}">
                        {!! Form::label('actual_weight', 'Actual weight (grams)', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('actual_weight', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('actual_weight'))
                            <span class="help-block">
                                <strong>{{ $errors->first('actual_weight') }}</strong>
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
                        <label for="display_order" class="col-md-4 control-label">Display order <br /><span>( range: 100=top <-> 0=bottom )<span></label>
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

                        <label for="color_background_color" class="col-md-4 control-label">Color background color<br /><span>(HEX - print catalog)</span></label>
                        <div class="col-md-6">
                        {!! Form::text('color_background_color', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('color_background_color'))
                            <span class="help-block">
                                <strong>{{ $errors->first('color_background_color') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>

                    <!-- <div class="form-group{{ $errors->has('color_background_image') ? ' has-error' : '' }}">
                        {!! Form::label('color_background_image', 'Color background image (print catalog) ', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::text('color_background_image', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('color_background_image'))
                            <span class="help-block">
                                <strong>{{ $errors->first('color_background_image') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div> -->


                    <div class="form-group{{ $errors->has('notify_when_instock') ? ' has-error' : '' }}">
                        {!! Form::label('notify_when_instock', 'Notify when instock', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::select('notify_when_instock', [''=>'Select option ...','y'=>'Yes','n'=>'No'], null,  array('class' => 'form-control')) !!}

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
                        {!! Form::select('source', [''=>'Select source...','F'=>'Finished product','B'=>'Build product','i'=>'i for who knows','M'=>'M for who knows'],null,  array('class' => 'form-control')) !!}

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
                        {!! Form::select('new_product',[''=>'Select option ...', '1'=>'Yes','0'=>'No'], null,  array('class' => 'form-control')) !!}

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
                        {!! Form::select('core_product', [''=>'Select option ...', '1'=>'Yes','0'=>'No'], null,  array('class' => 'form-control')) !!}

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



                    <div class="form-group{{ $errors->has('product_note') ? ' has-error' : '' }}">
                        {!! Form::label('product_note', 'Product note', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                        {!! Form::textarea('product_note', null,  array('class' => 'form-control')) !!}

                        @if ($errors->has('product_note'))
                            <span class="help-block">
                                <strong>{{ $errors->first('product_note') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
