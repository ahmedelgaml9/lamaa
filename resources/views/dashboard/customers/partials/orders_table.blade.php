<!--begin::Table-->
<table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
    <!--begin::Table head-->
    <thead>
    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
        <th class="min-w-125px">#</th>
        <th class="min-w-125px">{{__('admin.payment_method')}}</th>
        <th class="min-w-125px">{{__('admin.total')}}</th>
        <th class="min-w-125px">{{__('admin.created_at')}}</th>
        <th class="min-w-125px">{{__('admin.status')}}</th>
        <th class="text-end min-w-100px">{{__('admin.actions')}}</th>
    </tr>
    </thead>
    <tbody class="text-gray-600 fw-bold">
         @foreach($orders as $order)
        <tr>
            <td>{{$order->order_number}}</td>
            <td>{{$order->payment_method}}</td>
            <td>{{$order->total}}</td>
            <td>{{$order->created_at->format('d-m-Y h:i A')}}</td>

            <td>
                {!! orderStatusLabel($order->status) !!}
            </td>
            <td class="text-end">
                <a href="{{route('orders.show', $order->id)}}" class="btn btn-light btn-sm" target="_blank">{{__('admin.view')}}</a>
            </td>
        </tr>
    @endforeach
    </tbody>
    <!--end::Table body-->
</table>
<!--end::Table-->
{!! $orders->links('dashboard.partials.paginator') !!}
