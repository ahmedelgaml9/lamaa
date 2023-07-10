
@inject('carbon', 'Carbon\Carbon')
@php

$type = request()->get('type');
$today = $carbon->today();

@endphp

<html>
<head>
<base href="">
<meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
<meta charset="utf-8" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
<link href="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('dashboard/dist/assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />   
<title>  {{__('admin.delegates')}}</title>
</head>
<body> 
                  
           <h4  style="text-align:right; margin:20px;">{{__('admin.delegates')}}</h4>
               <div class="d-flex flex-wrap flex-stack pb-7">
      
               <div class="d-flex flex-wrap my-1">
           </div> 
        </div>
         <div class="card">
             <div class="card-header border-0 pt-6">
                 <div class="card-toolbar">
 
                 </div>
            </div>

              <div class="card-body pt-0" style="direction:rtl;">
                  <table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
                     <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">#</th>
                            <th class="min-w-125px">{{__('admin.user_name')}}</th>
                            <th class="min-w-125px">{{__('admin.phone')}}</th>
                            <th class="min-w-125px">{{__('admin.code')}}</th>
                            <th class="min-w-125px">{{__('admin.completed_orders')}}</th>
                            <th class="min-w-125px">{{__('admin.total')}}</th>
                      </tr>
                   </thead>
                     <tbody class="text-gray-600 fw-bold">
                        @foreach($delegates as $key=>$user)
                        <tr>
                         <td>{{++$key}}</td>
                         <td>{{ $user->name }}</td>
                         <td>{{ $user->mobile }}</td>
                         <td>{{ $user->route_code }}</td>

                         @if($type =="today")

                          <td>{{ $user->driverOrders()->where('orders.status', \App\Models\Order::STATUS_DELIVERED)->where('orders.created_at',$carbon->today())->count() }}</td>
                          <td>{{ $user->driverOrders()->where('orders.status', \App\Models\Order::STATUS_DELIVERED)->where('orders.created_at',$carbon->today())->sum('total') }}</td>

                          @elseif($type =="week")

                          <td>{{ $user->driverOrders()->where('orders.status', \App\Models\Order::STATUS_DELIVERED)->whereBetween('orders.created_at',[$carbon->now()->startOfWeek(), $carbon->now()->endOfWeek()])->count() }}</td>
                          <td>{{ $user->driverOrders()->where('orders.status', \App\Models\Order::STATUS_DELIVERED)->whereBetween('orders.created_at',[$carbon->now()->startOfWeek(), $carbon->now()->endOfWeek()])->sum('total') }}</td>

                          @elseif($type =="month")

                          <td>{{ $user->driverOrders()->where('orders.status', \App\Models\Order::STATUS_DELIVERED)->whereBetween('orders.created_at', [$carbon->now()->startOfMonth(), $carbon->now()->endOfMonth()])->count() }}</td>
                          <td>{{ $user->driverOrders()->where('orders.status', \App\Models\Order::STATUS_DELIVERED)->whereBetween('orders.created_at', [$carbon->now()->startOfMonth(), $carbon->now()->endOfMonth()])->sum('total')}}</td>

                          @elseif($type =="year")
                          <td>{{ $user->driverOrders()->where('orders.status', \App\Models\Order::STATUS_DELIVERED)->whereYear('orders.created_at', date('Y'))->count() }}</td>
                          <td>{{ $user->driverOrders()->where('orders.status', \App\Models\Order::STATUS_DELIVERED)->whereYear('orders.created_at', date('Y'))->sum('total') }}</td>
                          @else
                           <td>{{ $user->driverOrders()->where('orders.status', \App\Models\Order::STATUS_DELIVERED)->count() }}</td>
                           <td>{{ $user->driverOrders()->where('orders.status', \App\Models\Order::STATUS_DELIVERED)->sum('total') }}</td>
                           @endif
                       </tr>
                       @endforeach
                 </tbody>
             </table>
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

