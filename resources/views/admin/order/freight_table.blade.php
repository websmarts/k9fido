 {{--dump($freight)--}}
                    @if(!empty($freight['toll']))


                        <div style="width:40%">
                            <table class="table">
                                <tr><th colspan="4"><center>Toll</center></th></tr>

                                <tr>
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
                                </tr>
                            </table>
                        </div>

                    @endif

                   <table>
                    <tr>
                    @if(!empty($freight['eparcel']))
                        <td style="width:15%">
                            <center><strong>eParcel</strong></center>
                            <table class="table">
                                <tr><td>Base</td><td>Per Kg</td></tr>
                                <tr><td>{{$freight['eparcel']->base_rate }}</td><td>{{$freight['eparcel']->per_kg }}</td></tr>
                            </table>
                        </td>

                    @endif
                    @if(!empty($freight['auspost']))
                        <td style="width:15%">
                            <center><strong>Auspost</strong></center>
                            <table class="table">
                                <tr><td>Base</td><td>Per kg</td></tr>
                                <tr><td>{{$freight['auspost']->base_rate }}</td><td>{{$freight['auspost']->per_kg }}</td></tr>
                            </table>
                        </td>
                    @endif
                    @if(!empty($freight['if']))
                        <td style="width:30%">
                            <center><strong>IF</strong></center>
                            <table class="table">
                                <tr><td>R1</td><td>R2</td><td>R3</td></tr>
                                <tr><td>{{$freight['if']->rate1}}</td><td>{{$freight['if']->rate2}}</td><td>{{$freight['if']->rate3}}</td></tr>
                            </table>
                        </td>
                    @endif
                    @if(!empty($freight['tmcc']))
                        <td style="width:30%">
                            <center><strong>TMCC</strong></center>
                            <table class="table">
                                <tr><td>R1</td><td>R2</td><td>R3</td></tr>
                                <tr><td>{{$freight['tmcc']->rate1}}</td><td>{{$freight['tmcc']->rate2}}</td><td>{{$freight['tmcc']->rate3}}</td></tr>
                            </table>
                        </td>
                    @endif
                </tr>
            </table>
