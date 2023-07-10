<html>
<head>
<base href="">
    <meta name="description" content="Mawared" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('dashboard/dist/assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <title>  {{__('admin.reports')}}</title>

 </head>
  <body> 

<div class="card" style="direction:rtl;" >
<div class="card-header">
<h3 class="card-title">
@php


if($city){

  $city_name =\App\Models\City::where('id',$city)->first();

}

@endphp

@if($type == "today" && $city == 'null')

تم تصفية نتائج التقرير    باليوم الحالى 

@elseif($type == "today"&& $city != 'null')

تم تصفية نتائج التقرير    باليوم الحالى وبمدينة {{ $city_name->getTranslation('name','ar') }}

@elseif($type == "yesterday" && $city == '')

تم تصفية نتائج التقرير    باليوم الماضى 


@elseif($type == "yesterday" && $city != 'null')

تم تصفية نتائج التقرير    باليوم الماضى  وبمدينة {{ $city_name->getTranslation('name','ar') }}


@elseif($type  == "week" && $city == 'null')

تم تصفية نتائج التقرير    بالاسبوع الحالى 


@elseif($type  == "week" && $city != 'null')

تم تصفية نتائج التقرير    بالاسبوع الحالى وبمدينة {{ $city_name->getTranslation('name','ar') }}


@elseif($type  == "month" && $city == 'null')

تم تصفية نتائج التقرير   بالشهر الحالى 


@elseif($type  == "month" && $city != 'null')

تم تصفية نتائج التقرير   بالشهر الحالى  وبمدينة   {{ $city_name->getTranslation('name','ar') }}


@elseif($type  == "year" && $city == 'null')

تم تصفية نتائج التقرير   بالعام الحالى 


@elseif($type  =="year" && $city != 'null')

تم تصفية نتائج التقرير   بالعام الحالى وبمدينة       {{ $city_name->getTranslation('name','ar') }}

@elseif($type =="fromto" && $city == 'null')

تم تصفية نتائج التقرير بالفترة من  {{ $request }}


@elseif($type =="fromto" && $city != 'null')

تم تصفية نتائج التقرير بالفترة من    {{ $request }} وبمدينة  {{ $city_name->getTranslation('name','ar') }}

@endif
</h3>
<div class="card-toolbar">  

</div>
</div>
           <div class="card-body" style="direction:rtl;">
                    <div class="row">
                         @if($id == 1)
                          <div class="col-sm-6">
                             <div class="card">
                               <div class="card-header">{{__('admin.sales')}}</div>
                                 <div class="card-body">
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
                         </div>
                        @endif

                        @if($id == 2 )
                         <div class="col-sm-6">
                            <div class="card">
                              <div class="card-header" >{{__('admin.payment_methods')}}</div>
                                <div class="card-body">
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
                         @endif
                     </div>
                </div>
          </div>

<script src="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/js/scripts.bundle.js')}}"></script>

<script>

$(document).ready(function(){
    window.print();
});

</script>
</body>
</html>