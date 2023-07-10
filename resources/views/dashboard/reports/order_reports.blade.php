@extends('dashboard.layouts.master')
@section('toolbar')
@endsection
@section('content')

<div class="card">
<div class="row">
<div class="col-sm-9">

</div>

  <div class="col-sm-3" style="padding-top:18px;">
      <a href="{{url('admin/orders_status/pdf')}}"  target="__blank" class="btn btn-danger" id="print"><i class="fa fa-print"></i></a>
    </div>
</div>

    <div class="card-header" style="direction:ltr;">
        <h3 class="card-title"></h3>
           <div class="card-toolbar">
              <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                <li class="nav-item" style="font-size:18px;" >
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_6">{{__('admin.orders_status')}}</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card-body">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="kt_tab_pane_6" role="tabpanel">
                 <div class="row">
                      <div class="col-sm-6">
                         <div class="d-flex flex-wrap py-5">
                            <div class="flex-equal me-5">
                                <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
                                   
                                          <tr>
                                             <td > عدد الطلبات جارية التوصيل</td>
                                             <td class="text-gray-800">  {{ $current }}</td>
                                          </tr>

                                          <tr>
                                             <td > مبيعات الطلبات جارية التوصيل </td>
                                             <td> {{ $current_sales }}</td>
                                            </tr>

                                          <tr>
                                             <td > عدد الطلبات الجديدة </td>
                                             <td class="text-gray-800">  {{ $new  }}</td>
                                          </tr>

                                          <tr>
                                             <td> مبيعات الطلبات الجديدة </td>
                                             <td class="text-gray-800">{{$new_sales}}</td>
                                          </tr>

                                          <tr>
                                             <td > عدد الطلبات المكتملة </td>
                                             <td class="text-gray-800">  {{ $delivered }}</td>
                                          </tr>

                                          <tr>
                                             <td > مبيعات الطلبات المكتملة</td>
                                             <td class="text-gray-800"> {{ $delivered_sales }}</td>
                                          </tr>

                                          <tr>
                                             <td>عدد الطلبات المعاد جدولتها </td>
                                             <td class="text-gray-800">  {{ $rescheduled }}</td>
                                          </tr>

                                          <tr>
                                             <td> مبيعات الطلبات المعاد جدولتها </td>
                                             <td class="text-gray-800"> {{ $rescheduled_sales }}</td>
                                          </tr>

                                          <tr>
                                             <td >عدد الطلبات المتوقفة </td>
                                             <td class="text-gray-800">  {{ $stoped }}</td>
                                          </tr>

                                          <tr>
                                             <td> مبيعات الطلبات المتوقفة </td>
                                             <td class="text-gray-800"> {{ $stoped_sales }}</td>
                                          </tr>

                                          <tr>
                                             <td> عدد طلبات الاسترجاع </td>
                                             <td class="text-gray-800"> {{$returned }}</td>
                                          </tr>

                                           <tr>
                                              <td>مبيعات  طلبات  الاسترجاع </td>
                                              <td class="text-gray-800"> {{ $returned_sales }}</td>
                                           </tr>
                                      </table>
                                   </div>
                              </div>
                          </div>
                     </div>
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

$("#filters").click(function(){

  $("#created_at").attr("required", "true");

});

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
