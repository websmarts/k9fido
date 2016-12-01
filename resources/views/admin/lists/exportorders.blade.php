<h3>{{ $title }}</h3>
                    {{ Form::open( ['route' => ['order.batchexport'], 'method'=>'post'] ) }}
                    <table class="table table-striped">
                    <thead>
                    <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Export</th>
                    <th>&nbsp;</th>

                    </tr>
                    </thead>
                    <tbody>
                    @if($data->count() > 0)
                        @foreach($data as $order)
                        <tr>
                            <td>{{ $order->id}}</td>
                            <td>{{ date('j-m-Y',strtotime($order->modified)) }}</td>

                            <td>{{ $order->client->name }}</td>
                            <td>{{ Form::checkbox('exportorders[]', $order->id) }}</td>
                            <td width="20"><a href="{{ route('order.show', ['id' => $order->id] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
                        </tr>
                        @endforeach
                        <tr><td colspan="5"><button type="submit" value="Export" class="btn btn-primary">Export checked orders</button></td></tr>
                    @else
                        <tr><td colspan="4">List is currently empty</td></tr>
                    @endif

                    </tbody>
                    </table>

                    {{ Form::close() }}
