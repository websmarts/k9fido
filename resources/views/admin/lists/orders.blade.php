
<h3>{{ $title }}</h3>
                    <table class="table table-striped">
                    <thead>
                    <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>&nbsp;</th>

                    </tr>
                    </thead>
                    <tbody>
                    @if($data->count() > 0)
                        @foreach($data as $order)
                        <tr>
                            <td>{{ $order->id}}</td>
                            <td>{{ date('j-m-Y',strtotime($order->modified)) }}</td>

                            <td>
                                {{$order->client ? $order->client->name : 'NO CLIENT ATTACHED TO ORDER'}}</td>
                            <td width="20"><a href="{{ route('order.show', ['id' => $order->id] ) }}"><i class="fa fa-pencil-square-o fa-1x" aria-hidden="true"></i></a></td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4">List is currently empty</td></tr>
                    @endif

                    </tbody>
                    </table>
