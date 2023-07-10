<!--begin::Table-->
<table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
    <!--begin::Table head-->
    <thead>
    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
        <th class="min-w-125px">{{__('admin.mobile')}}</th>
        <th class="min-w-125px">{{__('admin.address')}}</th>
        <th class="min-w-125px">{{__('admin.username')}}</th>
        <th class="min-w-125px">{{__('admin.created_at')}}</th>
    </tr>
    </thead>
    <tbody class="text-gray-600 fw-bold">
    @foreach($addresses as $address)
        <tr>
            <td>{{$address->mobile}}</td>
            <td>{{$address->address}}</td>
            <td>{{$address->username}}</td>
            <td>{{$address->created_at->format('d-m-Y h:i A')}}</td>
        </tr>
    @endforeach
    </tbody>
    <!--end::Table body-->
</table>
<!--end::Table-->
{!! $addresses->links('dashboard.partials.paginator') !!}
