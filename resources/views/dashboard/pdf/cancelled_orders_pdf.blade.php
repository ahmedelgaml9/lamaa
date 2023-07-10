<html>
<head>
<base href="">
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('dashboard/dist/assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <title>{{__('admin.cancelled_orders')}}</title>
</head>
    <body> 
        <h4  style="text-align:right; margin:20px;"> {{__('admin.cancelled_orders')}}</h4>
            <div class="card">
            <div class="card-header border-0 pt-5">
             <div class="card-toolbar">

            </div>
         </div>
    
           <div class="card-body pt-0">
             <table class="table align-middle table-row-dashed fs-6 gy-5" >
                <thead>
                  <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th>{{__('admin.status')}}</th>
                        <th >{{__('admin.cancel_reason')}}</th>
                        <th>{{__('admin.payment_method')}}</th>
                        <th >{{__('admin.total')}}</th>
                        <th >{{__('admin.deliverydate')}}</th>
                        <th>{{__('admin.phone')}}</th>
                        <th >{{__('admin.user_name')}}</th>
                        <th >#</th>
                   </tr>
                 </thead>
                    <tbody class="text-gray-600 fw-bold">
                        @foreach($orders  as $key =>$order)
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <td>{!! orderStatusLabel($order->status) !!}</td>
                            <td>{{$order->cancelled_reason }}</td>
                            <td>{{$order->paymentmethod }}</td>
                            <td>{{$order->total}}</td>
                            <td>{{$order->delivery_date->format('Y-m-d') }}</td>
                            @if(isset($order->user))<td>{{$order->user->mobile }}</td>@endif
                            @if(isset($order->user))<td>{{$order->user->name }}</td>@endif
                            <td>{{ ++$key }}</td>
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

<script src="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/js/scripts.bundle.js')}}"></script>
<script>

$(document).ready(function(){
    window.print();
});

</script>
</body>
</html>