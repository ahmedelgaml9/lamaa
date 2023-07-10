@extends('dashboard.layouts.master')
@section('toolbar')
@endsection
@section('content')

@php

$type = request()->get('type');

$city = request()->get('city');

$request= '';

if(request()->get('created_at'))
{

 $created = request()->get('created_at');

 $request = str_replace(' >> ', 'الى ', $created);

}

else{

$request ='null';

}

if($city){

  $city_name =\App\Models\City::where('id',$city)->first();
  $city_id  = $city_name->id ;
}

else{

$city_name= '';
$city_id ='null';

}

@endphp
<div class="row">
<div class="col-sm-6">

@if($type == "today" && $city == '')
<h4>
تم تصفية نتائج التقرير    باليوم الحالى 
</h4>

@elseif($type == "today" && $city)
<h4>
  تم تصفية نتائج التقرير    باليوم الحالى وبمدينة  {{  $city_name->getTranslation('name','ar') }}
</h4>


@elseif($type == "yesterday" && $city == '')
<h4>
تم تصفية نتائج التقرير    باليوم الماضى 

</h4>

@elseif($type == "yesterday" && $city)
<h4>
تم تصفية نتائج التقرير    باليوم الماضى  وبمدينة  {{ $city_name->getTranslation('name','ar') }}

</h4>

@elseif($type == "week" && $city)

<h4>
  تم تصفية نتائج التقرير    بالاسبوع الحالى  وبمدينة {{ $city_name->getTranslation('name','ar') }}

</h4>

@elseif($type  == "week" && $city == '')

<h4>
تم تصفية نتائج التقرير    بالاسبوع الحالى 

</h4>

@elseif($type  == "month" && $city == '')

<h4>
تم تصفية نتائج التقرير   بالشهر الحالى 

</h4>

@elseif($type == "month" && $city)

<h4>
 تم تصفية نتائج التقرير   بالشهر الحالى  وبمدينة   {{ $city_name->getTranslation('name','ar') }}

</h4>

@elseif($type  == "year" && $city == '')

<h4>

تم تصفية نتائج التقرير   بالعام الحالى 

</h4>

@elseif($type  == "year" && $city)

<h4>

تم تصفية نتائج التقرير   بالعام الحالى وبمدينة   {{ $city_name->getTranslation('name','ar') }}

</h4>

@elseif($type =="fromto" && $city == '')

<h4>

تم تصفية نتائج التقرير بالفترة من     {{ $request }}

</h4>

@elseif($type =="fromto" && $city)

<h4>

تم تصفية نتائج التقرير بالفترة من   {{ $request }} وبمدينة {{ $city_name->getTranslation('name','ar') }}

</h4>

@elseif($city)

<h4>

تم تصفية نتائج التقرير بمدينة  {{ $city_name->getTranslation('name','ar') }}

</h4>

@endif

</div>

</div>

<div class="card">
@foreach(supportedLanguages() as $keyLang => $valueLang)
<div class="row">
<div class="col-sm-9">
<form action="{{route('reports_results')}}" class="px-7 py-5" method="get">
    @csrf
  <div class="row">
    <div class="col-sm-2">
      <select name="type" id="type" class="form-select" >
        <option value="today">{{__('admin.today')}}</option>
        <option value="yesterday">{{__('admin.yesterday')}}</option>
        <option value="week">{{__('admin.thisweek')}}</option>
        <option value="month">{{__('admin.thismonth')}}</option>
        <option value="year">{{__('admin.thisyear')}}</option>
        <option value="fromto">{{__('admin.periods')}}</option>

    </select>
</div>

  <div class="col-sm-4">
    <input name="created_at" type="text" id="created_at" class="form-control created-at-date-picker"  disabled placeholder="01-01-2022 >> 01-01-2023" >
  </div>

    <div class="col-sm-3">
        <select class="form-select" id="city" name="city">
            <option value="">اختر مدينة</option>
                @foreach($cities as $city)
                  <option value="{{ $city->id }}">{{ $city->getTranslation('name', $keyLang)}}</option>
                 @endforeach
             </select> 
        </div>

         <div class="col-sm-3">
               <button type="submit" class="btn btn-primary">{{__('admin.filter')}}</button>
             </div>
         </div>
     </form>
</div>

<div class="col-sm-3" style="padding-top:18px;">
  <a href="{{url('admin/printview/1/'.$type.'/'.$request.'/'. $city_id)}}"  target="__blank" class="btn btn-danger" id="print"><i class="fa fa-print"></i></a>
</div>
</div>

@endforeach

    <div class="card-header" style="direction:ltr;">
        <h3 class="card-title"></h3>
           <div class="card-toolbar">
              <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0" id="tabs">
                <li class="nav-item" style="font-size:18px; margin-right:20px;" >
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">{{__('admin.payment_methods')}}</a>
                </li>
                <li class="nav-item" style="font-size:18px;" >
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">{{__('admin.sales')}}</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                 <div class="row">
                      <div class="col-sm-6">
                         <div class="d-flex flex-wrap py-5">
                            <div class="flex-equal me-5">
                                <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
                                   
                                     <tr>
                                        <td class="text-gray-400"> {{__('admin.customers_number')}} </td>
                                        <td class="text-gray-800"> {{ $customers }}</td>
                                     </tr>

                                      <tr>  
                                        <td class="text-gray-400"> {{__('admin.newcustomers_number')}} </td>
                                        <td class="text-gray-800"><a href="{{ route('newcustomers_reports')}}">{{$newcustomers}}</td>
                                      </tr>

                                      <tr>
                                        <td class="text-gray-400"> {{__('admin.cashback_total')}} </td>
                                        <td class="text-gray-800"><a href="{{ url('admin/reports/cashback')}}"> {{$cashback}} </a></td>
                                      </tr>

                                     <tr>
                                        <td class="text-gray-400"> {{__('admin.oldcarts_numbers')}} </td>
                                        <td class="text-gray-800"> {{ $carts }}</td>
                                     </tr>

                                     <tr>
                                        <td class="text-gray-400"> {{__('admin.carts_numbers')}} </td>
                                        <td class="text-gray-800"><a href="{{ route('cartorders.index')}}">  {{ $orderscount }}</a></td>
                                    </tr>
                                    
                                     <tr>
                                        <td class="text-gray-400"> {{__('admin.returned_orders_numbers')}} </td>
                                        <td class="text-gray-800"><a href="{{ url('admin/reports/returned_orders')}}"> {{ $returned }}</a></td>
                                      </tr>
                                     <tr>
                                        <td class="text-gray-400"> {{__('admin.delivered_orders_numbers')}} </td>
                                        <td class="text-gray-800">  {{ $delivered }}</td>
                                     </tr>
                                     <tr>
                                        <td class="text-gray-400"> {{__('admin.cancelled_orders_numbers')}} </td>
                                        <td class="text-gray-800"><a href="{{ url('admin/reports/cancelled_orders')}}"> {{ $cancelled }}</a></td>
                                      </tr>
                                
                                     <tr>
                                        <td class="text-gray-400"> {{__('admin.compansations_numbers')}} </td>
                                        <td class="text-gray-800"><a href="{{ url('admin/reports/compansations_reports')}}">{{ $compansations }}</a></td>
                                      </tr>
                                     <tr>

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

             <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                 <div class="row">
                      <div class="col-sm-6">
                         <div class="d-flex flex-wrap py-5">
                            <div class="flex-equal me-5">
                                <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
                                    <tr>
                                        <td class="text-gray-400"> {{__('admin.cash')}} </td>
                                        <td class="text-gray-800"> {{$ordercash}}</td>
                                     </tr>
                                        <tr>
                                        <td class="text-gray-400"> {{__('admin.bank')}} </td>
                                        <td class="text-gray-800"> {{ $orderbank }}</td>
                                       </tr>

                                      <tr>
                                        <td class="text-gray-400"> {{__('admin.visa')}} </td>
                                        <td class="text-gray-800"> {{$ordervisa}}</td>
                                     </tr>

                                      <tr>
                                        <td class="text-gray-400"> {{__('admin.sadad')}} </td>
                                        <td class="text-gray-800"> {{ $ordersadad }}</td>
                                      </tr>
                                
                                      <tr>
                                        <td class="text-gray-400"> {{__('admin.applepay')}} </td>
                                        <td class="text-gray-800"> {{ $orderapplepay }}</td>
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

$("#type").change(function(){

if(this.value == "fromto")
{

  $("#created_at").prop('disabled', false);

}

else{

  $("#created_at").prop('disabled',true);

}

});


$("#tabs").click(function(){

var active = $(".tab-pane.active").attr("id");

if(active == "kt_tab_pane_1")
{

$("#print").attr("href", "{{url('admin/printview/2/'.$type.'/'.$request.'/'.$city_id)}}")

}

if(active == "kt_tab_pane_2")
{

 $("#print").attr("href", "{{url('admin/printview/1/'.$type.'/'.$request.'/'.$city_id)}}")

}

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


</script>
@endsection
