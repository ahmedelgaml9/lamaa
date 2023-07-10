@extends('dashboard.layouts.master')
@section('toolbar')
@endsection
@section('content')

@php

$created = request()->get('created_at');

$request  =str_replace('>>', 'الى ', $created );

@endphp

<div class="row">
    
<div class="col-sm-6">

<h4>
تم تصفية نتائج التقرير بالفترة من     {{ $request }}
 
</h4>

</div>
</div>
<div class="card">
<div class="row">
<div class="col-sm-9">
  <form action="{{route('sales_orders_results')}}" class="px-7 py-5" method="get">
    @csrf
  <div class="row">
  <div class="col-sm-4">
    <input name="created_at" type="text" id="created_at"  class="form-control created-at-date-picker" placeholder="01-01-2022 >> 01-01-2023">
 </div>

     <div class="col-sm-3">
          <button type="submit" class="btn btn-primary" id="filters">{{__('admin.filter')}}</button>
       </div>
     </div>
  </form>
</div>

<div class="col-sm-3" style="padding-top:18px;">
     <a href="{{route('print_sales_results', request()->get('created_at'))}}"  target="__blank" class="btn btn-danger" id="print"><i class="fa fa-print"></i></a>
 </div>

</div>

    <div class="card-header" style="direction:ltr;">
        <h3 class="card-title"></h3>
           <div class="card-toolbar">
              <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                <li class="nav-item" style="font-size:18px;" >
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_6">{{__('admin.sales')}}</a>
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
                                           <td class="text-gray-400"> {{__('admin.delivered_orders_numbers')}} </td>
                                           <td class="text-gray-800">  {{ $delivered }}</td>
                                       </tr>
                                      <tr>
                                          <td class="text-gray-400"> {{__('admin.sales_total')}} </td>
                                          <td class="text-gray-800"> {{$sales}}</td>
                                      </tr>
                                     </table>
                                  </div>
                              </div>
                          </div>

                          <div class="col-sm-6">
                             <div class="d-flex flex-wrap py-5">
                               <div id="kt_apexcharts_3" style="height:300px;width:400px;"></div>

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

  $("#created_at").prop('required',true);


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


{{--
var element = document.getElementById('kt_apexcharts_3');
var height = parseInt(KTUtil.css(element, 'height'));
var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
var baseColor = KTUtil.getCssVariableValue('--bs-info');
var lightColor = KTUtil.getCssVariableValue('--bs-light-info');

var options = {
    series: [{
        name: 'عدد الطلبات',
        data: [ @foreach($orders_numbers as $key => $value) {{$value}} , @endforeach],
      
    }],
    
    chart: {
        fontFamily: 'inherit',
        type: 'area',
        height: height,
        toolbar: {
            show: false
        }
    },
    plotOptions: {

    },
    legend: {
        show: false
    },
    dataLabels: {
        enabled: false
    },
    fill: {
        type: 'solid',
        opacity: 1
    },
    stroke: {
        curve: 'smooth',
        show: true,
        width: 3,
        colors: [baseColor]
    },
    xaxis: {
        categories: ['الطلبات المكتملة','طلبات الاسترجاع','الطلبات الملغية'],
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
        labels: {
            style: {
                colors: labelColor,
                fontSize: '15px',
          
            }
        },
        crosshairs: {
            position: 'front',
            stroke: {
                color: baseColor,
                width: 1,
                dashArray: 3
            }
        },
        tooltip: {
            enabled: true,
            formatter: undefined,
            offsetY: 0,
            style: {
                fontSize: '12px'
            }
        }
    },
    yaxis: {
        labels: {
            style: {
                colors: labelColor,
                fontSize: '12px'
            }
        }
    },
    states: {
        normal: {
            filter: {
                type: 'none',
                value: 0
            }
        },
        hover: {
            filter: {
                type: 'none',
                value: 0
            }
        },
        active: {
            allowMultipleDataPointsSelection: false,
            filter: {
                type: 'none',
                value: 0
            }
        }
    },
    tooltip: {
        style: {
            fontSize: '12px'
        },
        y: {
            formatter: function (val) {
                return val 
            }
        }
    },
    colors: [lightColor],
    grid: {
        borderColor: borderColor,
        strokeDashArray: 4,
        padding: {
            top:0,
            right: 0,
            bottom:0,
            left:50
        },

        yaxis: {
            lines: {
                show: true
            }
        }
    },
    markers: {
        strokeColor: baseColor,
        strokeWidth: 3
    }
};

var chart = new ApexCharts(element, options);

chart.render();

--}}

</script>
@endsection
