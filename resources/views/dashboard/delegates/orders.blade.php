@extends('dashboard.layouts.master')
@section('toolbar')

   <div class="d-flex align-items-center py-1">
      <a href="{{route('delegates.exportToExcel', $data)}}" class="btn btn-sm btn-flex btn-dark btn-active-primary fw-bolder"><i class="fa fa-file-download"></i>تصدير</a>
</div>

@endsection
@section('content')
       <div class="card">
            <div class="card-header border-0 pt-5">
                <div class="card-title">
                     {{__('admin.weborders')}}
                </div>
            </div>
               <div class="card-body pt-0">
                  <table class="table align-middle table-row-dashed fs-6 gy-5">
                     <thead>
                          <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                              <th class="min-w-10px">ID</th>
                              <th class="min-w-10px">{{__('admin.created_at')}}</th>
                              <th class="min-w-10px">{{__('admin.deliverydate')}}</th>
                              <th class="min-w-10px">{{__('admin.customer')}}</th>
                              <th class="min-w-10px">{{__('admin.total')}}</th>
                              <th class="min-w-10px">{{__('admin.city')}}</th>
                              <th class="min-w-10px">{{__('admin.payment_method')}}</th>
                              <th class="min-w-10px">{{__('admin.status')}}</th>
                         </tr>
                     </thead>
                      <tbody class="text-gray-600 fw-bold">
                            @foreach($orders as $order)
                              @php

                                 $user = $order->user;
                                 $driver = $order->driver;
                                 $address= $order->address;
                                 $customer = $order->user;
                                 $customerCityName = null;
                                 if($orderCity = $order->city){
                        
                                 $customerCityName = $orderCity->name;
                            
                                     }else{

                                          if($customer){

                                              $customerCity = $customer->city;

                                               if($customerCity){
                                                
                                                $customerCityName = $customerCity->name;
                                            }
                                        }
                                     }

                                    @endphp
                                   <tr>
                                      <td>
                                         @if(!is_null(request()->get('status')))
                                           <input class="child-checkbox-row" type="checkbox" name="selected_orders[]" value="{{$order->id}}">
                                        @endif
                                          &nbsp; {{$order->order_number}}
                                       </td>
                                       <td>{{$order->created_at->format('Y-m-d')}}
                                         <div class="fw-bold fs-6 text-gray-400"><i class="fa fa-clock"></i> {{$order->created_at->format('h:i A')}}</div>
                                       </td>
                                       <td>{{$order->delivery_date }}
                                          <div class="fw-bold fs-6 text-gray-400" style="font-size: 12px !important; font-weight: bold !important;"><i class="fa fa-clock"></i> {{implode(' - ', [date('H:i',strtotime($order->delivery_start_time)) , date('H:i',strtotime($order->delivery_end_time))])}}</div>
                                      </td>
                                      <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{$user?$user->name:'--'}}">{{$user?\Str::limit($user->name, 15):'--'}}
                                         <div class="fw-bold fs-6 text-gray-400"> {{$address?$address->mobile:'---'}}</div>
                                      </td>
                                      <td>{{$order->total}}</td>
                                      @if(isset($order->city))<td>{{ $order->city->name }} </td>@else<td></td>@endif
                                      <td>{{$order->origin_payment_method }}</td>
                                      <td><a href="javascript:void(0);" class="change-order-status-btn" @if(in_array($order->status, [\App\Models\Order::STATUS_CANCELED]) ) data-bs-toggle="tooltip" data-bs-placement="top" title="{{$order->cancelled_reason?:'لم يتم إضافة سبب للإلغاء'}}" @endif data-url="{{route('orders.statuses-list', ['id' => $order->id])}}">{!! orderStatusLabel($order->status) !!}</a></td>
                                  </tr>
                                    @endforeach
                                </tbody>
                             </table>
                                 {!! $orders ->links('dashboard.partials.paginator', ['disableJS' => true]) !!}
                              <div class="row">
                                  <div class="col-sm-6">
                                     <h4>{{__('admin.sales')}}: {{ $driverssales }}</h4>
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
      "ordering": false,

     });
   })

       $(".created-at-date-picker").flatpickr({
            mode: "range",
            maxDate: "today",
            dateFormat: "Y-m-d",
            @if(!empty($createdAtArray))
            defaultDate: [{{$createdAtArray[0]}}, {{$createdAtArray[1]}}],
            @endif
            onChange: function(selectedDates, dateStr, instance){
                $(".created-at-date-picker").val(dateStr.replace('to', '>>'));
            }
        });

</script>
@endsection
