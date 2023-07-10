<html>
<head>
<base href="">
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('dashboard/dist/assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <title>{{__('admin.newcustomers')}}</title>
</head>
    <body> 
        <h4  style="text-align:right; margin:20px;"> {{__('admin.newcustomers')}}</h4>
            <div class="card">
               <div class="card-header border-0 pt-5">
                <div class="card-toolbar">

                </div>
              </div>
      
           <div class="card-body pt-0" style="direction:rtl;">
             <table class="table align-middle table-row-dashed fs-6 gy-5" >
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-10px">ID</th>
                    <th class="min-w-150px">Name</th>
                    <th class="min-w-90px">Orders</th>
                    <th class="min-w-90px">Wallet</th>
                    <th class="min-w-50px text-end">Status</th>

                 </tr>
                 </thead>
                 <tbody class="text-gray-600 fw-bold">
                    @foreach($customers as $customer)
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                     <td>{{$customer->id}}</td>
                     <td>
                        <div class="d-flex align-items-center">
                            <div class="me-5 position-relative">
                                <div class="symbol symbol-35px symbol-circle">
                                       @if($customer->avatar)
                                        <img src="{{$customer->avatar}}" alt="image" />
                                        @else
                                        @php
                                         $customerBgColor = randomBootstrapColorsLabel($customer->id);
                                         $firstLetterOfCustomer = strtoupper(substr(Str::slug($customer->name), 0, 1));
                                         @endphp
                                        <span class="symbol-label fs-2x fw-bold text-{{$customerBgColor}} bg-light-{{$customerBgColor}}">{{$firstLetterOfCustomer}}</span>
                                         @endif
                                        <div class="position-absolute border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"><span style="color: red">{{$customer->cart_rate}}</span></div>

                                   </div>
                               </div>
                           
                            <div class="d-flex flex-column justify-content-center">
                                <a href="{{route('customers.show', $customer->id)}}" class="mb-1 text-gray-800 text-hover-primary">{{$customer->name}}</a>
                                <div class="fw-bold fs-6 text-gray-400">{{$customer->email}}</div>
                                <div class="fw-bold fs-6 text-gray-400">{{$customer->mobile}}</div>
                             </div>
                         </div>
                      </td>
                            <td>{{$customer->orders_count}}</td>
                            <td>{{ (float) $customer->balance }}</td>
                            <td>
                                {!! customerStatusLabels($customer->orders_status) !!}
                            </td>
                        </tr>
                            @endforeach
                     </tbody>
                  </table>
                <div class="row">
                    <div class="col-sm-6">
                    <h4>{{__('admin.customers_number')}}: {{$count}}</h4>
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