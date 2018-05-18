 {{--dump($freight)--}}
                    @if(!empty($freight['toll']))
                    <div class="row" >

                        <div class="col-md-12">
                            <table class="table">
                                <tr><th colspan="12"><center>Toll</center></th></tr>
                                <tr>
                                    <th colspan="4">Basic</th>
                                    <th colspan="4">with fuel levy</th>
                                    <th colspan="4">with fuel levy &amp; GST</th>
                                </tr>
                                <tr>
                                    <th>0-25kg</th>
                                    <th>25-50kg</th>
                                    <th>50-75kg</th>
                                    <th>75-100kg</th>
                                    <th>0-25kg</th>
                                    <th>25-50kg</th>
                                    <th>50-75kg</th>
                                    <th>75-100kg</th>
                                    <th>0-25kg</th>
                                    <th>25-50kg</th>
                                    <th>50-75kg</th>
                                    <th>75-100kg</th>
                                </tr>

                                <tr>
                                    <td>{{ $freight['toll']->rate1_1 }}</td>
                                    <td>{{ $freight['toll']->rate1_2 }}</td>
                                    <td>{{ $freight['toll']->rate1_3}}</td>
                                    <td>{{ $freight['toll']->rate1_4 }}</td>

                                    <td>{{ $freight['toll']->rate2_1 }}</td>
                                    <td>{{ $freight['toll']->rate2_2 }}</td>
                                    <td>{{ $freight['toll']->rate2_3}}</td>
                                    <td>{{ $freight['toll']->rate2_4 }}</td>

                                    <td>{{ $freight['toll']->rate3_1 }}</td>
                                    <td>{{ $freight['toll']->rate3_2 }}</td>
                                    <td>{{ $freight['toll']->rate3_3}}</td>
                                    <td>{{ $freight['toll']->rate3_4 }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                    @if(!empty($freight['eparcel']))
                        <div class="col-md-2">
                            <center><strong>eParcel</strong></center>
                            <table class="table">
                                <tr><td>Base</td><td>Per Kg</td></tr>
                                <tr><td>{{$freight['eparcel']->base_rate }}</td><td>{{$freight['eparcel']->per_kg }}</td></tr>
                            </table>
                        </div>

                    @endif
                    @if(!empty($freight['auspost']))
                        <div class="col-md-2">
                            <center><strong>Auspost</strong></center>
                            <table class="table">
                                <tr><td>Base</td><td>Per kg</td></tr>
                                <tr><td>{{$freight['auspost']->base_rate }}</td><td>{{$freight['auspost']->per_kg }}</td></tr>
                            </table>
                        </div>
                    @endif
                    @if(!empty($freight['if']))
                        <div class="col-md-4">
                            <center><strong>IF</strong></center>
                            <table class="table">
                                <tr><td>R1</td><td>R2</td><td>R3</td></tr>
                                <tr><td>{{$freight['if']->rate1}}</td><td>{{$freight['if']->rate2}}</td><td>{{$freight['if']->rate3}}</td></tr>
                            </table>
                        </div>
                    @endif
                    @if(!empty($freight['tmcc']))
                        <div class="col-md-4">
                            <center><strong>TMCC</strong></center>
                            <table class="table">
                                <tr><td>R1</td><td>R2</td><td>R3</td></tr>
                                <tr><td>{{$freight['tmcc']->rate1}}</td><td>{{$freight['tmcc']->rate2}}</td><td>{{$freight['tmcc']->rate3}}</td></tr>
                            </table>
                        </div>
                    @endif

                    </div><!-- end row -->
