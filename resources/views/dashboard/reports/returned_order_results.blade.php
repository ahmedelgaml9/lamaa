@extends('dashboard.layouts.master')
@section('content')
 @php
 $city = request()->get('city') ;
 $from = request()->get('from') ;
 $to  = request()->get('to') ;
 @endphp
         <div class="card">
                <div class="card-header border-0 pt-5">
                <div class="card-title">
                   {{__('admin.returned_orders')}}
                  </div>
                  <div class="card-toolbar">
                   <div class="row">
                    <div class="col-lg-6">

                    <a href="{{route('returnedorders_ToExcel', request()->query())}}" class="btn btn-sm btn-flex btn-dark btn-active-primary fw-bolder"><i class="fa fa-file-download"></i>تصدير</a>

                   </div>
                     {{--<div class="col-lg-6">
                         <label  class="col-form-label  fw-bold fs-6"></label><br><br>
                          @if(($city != '') && ($from || $to == ''))
                          <a href="{{url('admin/cancelledorders/pdf/'.$city)}}" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                          @endif

                          @if(($from && $to != '') && $city == '')
                          <a href="{{url('admin/cancelledorders/pdf/'.$from.'/'.$to)}}" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                          @endif

                          @if($from && $to && $city != '')
                          <a href="{{url('admin/cancelledorders/pdf/'.$from.'/'.$to.'/'.$city)}}" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                          @endif
                         
                        </div>--}}
                     </div>
                 </div>
             </div>
      
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">{{__('admin.user_name')}}</th>
                    <th class="min-w-125px">{{__('admin.phone')}}</th>
                    <th class="min-w-125px">{{__('admin.created')}}</th>
                    <th class="min-w-125px">{{__('admin.total')}}</th>
                    <th class="min-w-125px">{{__('admin.payment_method')}}</th>
                    <th class="min-w-125px">{{__('admin.status')}}</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold">
                  @foreach($orders  as $order)
                  <tr>
                    @if(isset($order->user))<td>{{$order->user->name }}</td>@endif
                    @if(isset($order->user))<td>{{$order->user->mobile }}</td>@endif
                    <td>{{ date('Y-m-d',strtotime($order->created_at))}}</td>
                    <td>{{$order->total}}</td>
                    <td>{{$order->payment_method }}</td>
                    <td>{!! orderStatusLabel($order->status) !!}</td>
                   </tr>
                    @endforeach
                 </tbody>
            </table>

             <div class="row">
                 <div class="col-sm-6">
                    <h4>{{__('admin.orders_number')}}: {{$count}}</h4>
                </div>
             </div>
          </div>
     </div>

@endsection
@section('after_content')
@endsection
@section('css')

<link href="{{asset('dashboard/dist/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>

@endsection
@section('script')

    <script src="{{asset('dashboard/dist/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script>
        $(function (){
            $('#db_table').dataTable({
                "ordering": false
            });
        })

        
</script>
@endsection
