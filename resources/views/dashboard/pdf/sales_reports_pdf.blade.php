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

      if(isset($request))
       {

        $request2 = str_replace('>>', 'الى ', $request);
        echo 'تم تصفية نتائج التقرير بالفترة من '.$request2 ;

       }

       @endphp
 
       </h3>
      <div class="card-toolbar">
  </div>
</div>
      <div class="card-body" style="direction:rtl;">
             <div class="row">
                 <div class="col-sm-6">
                    <div class="card">
                          <div class="card-header">{{__('admin.sales')}}</div>
                             <div class="card-body">
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
                         </div>
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