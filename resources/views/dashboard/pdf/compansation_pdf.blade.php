<html>
<head>
<base href="">
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('dashboard/dist/assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <title>{{__('admin.compansations')}}</title>

  </head>
         <body> 
        <h4  style="text-align:right; margin:20px;">{{__('admin.compansations')}}</h4>
          <div class="card">
               <div class="card-header border-0 pt-5">
                <div class="card-toolbar">
                </div>
              </div>

         <div class="card-body pt-0" style="direction:rtl;">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">ID</th>
                    <th class="min-w-125px">{{__('admin.customer')}}</th>
                    <th class="min-w-125px">{{__('admin.created_at')}}</th>
                    <th class="min-w-125px">{{__('admin.total')}}</th>
                    <th class="min-w-125px">{{__('admin.status')}}</th>
                    <th class="min-w-125px">{{__('admin.employee')}}</th>
                    <th class="min-w-125px">{{__('admin.delivery_man')}}</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold">
                    @foreach($compansations as $key => $c)
                   <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <td>{{ $c->order->id }}</td>
                        <td>{{ $c->order->user->name }}</td>
                        <td>{{ $c->order->created_at }}</td>
                        <td>{{ $c->order->total }}</td>
                        <td>{!! orderStatusLabel($c->order->status) !!}</td>
                        <td>{{ $c->user->name }}</td>
                        <td>{{ $c->order->driver_name }}</td>
                      </tr>
                         @endforeach
                     </tbody>
                 </table>
                  <div class="row">
                       <div class="col-sm-6">
                          <h4>{{__('admin.compansations_numbers')}}: {{$count}}</h4>
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