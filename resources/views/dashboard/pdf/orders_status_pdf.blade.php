<html>
<head>
<base href="">
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('dashboard/dist/assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />   
   
   <title>{{__('admin.orders')}}</title>
 </head>
 <body>   

<div class="card" style="direction:rtl;">
    <div class="card-header">
        <h3 class="card-title"></h3>
           <div class="card-toolbar">
              <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
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

<script src="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/js/scripts.bundle.js')}}"></script>
<script>

$(document).ready(function(){
    window.print();
});

</script>
</body>
</html>