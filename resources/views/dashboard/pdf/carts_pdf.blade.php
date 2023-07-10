<html>
<head>
<base href="">
    <meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('dashboard/dist/assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />   
    <title>  {{__('admin.oldcarts')}}</title>

   </head>
       <body> 

        <h4  style="text-align:right; margin:20px;">{{__('admin.oldcarts')}}</h4>
            <div class="card">
            <div class="card-header border-0 pt-5">
                <div class="card-toolbar">
            </div>
          </div>

          <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                   <th class="min-w-125px">{{__('admin.cart_updated_at')}}</th>
                   <th class="min-w-125px">{{__('admin.cart_amount')}}</th>
                   <th class="min-w-125px">{{__('admin.user_phone')}}</th>
                   <th class="min-w-125px">{{__('admin.user_name')}}</th>
                   <th class="min-w-125px">#</th>

                </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold">
                  @foreach($carts as $cart)
                  <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <td>{{$cart->updated_at }}</td>
                    <td>{{ $cart->amount() }}</td>
                    @if(isset($cart->user)) 
                    <td>{{$cart->user->mobile }}</td>
                    @else <td></td>@endif
                    @if(isset($cart->user))
                    <td>{{$cart->user->name }}</td>
                    @else <td></td>@endif
                    <td>{{ $cart->id }}</td>

                  </tr>
                @endforeach
                 </tbody>
            </table>
            <div class="row">
                <div class="col-sm-6">
                  <h4>{{__('admin.carts_number')}}: {{$count}}</h4>
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