<html>
<head>
<base href="">
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('dashboard/dist/assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <title> {{__('admin.promocodes_reports')}}</title>
</head>
    <body> 
            <div class="card">
                <div class="card-header border-0 pt-5">
                <div class="card-title" style="text-align:right">
                    {{__('admin.promocodes_reports')}}
                  </div>
                <div class="card-toolbar">
                </div>
              </div>
      
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">#</th>
                    <th class="min-w-125px">{{__('admin.promocode')}}</th>
                    <th class="min-w-125px">{{__('admin.number_of_users')}}</th>
                    <th class="min-w-125px">{{__('admin.status')}}</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold">
                  @foreach($promocodes  as $key => $promocode)
                 <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{$promocode->code }} </td>
                    <td>{{ count($promocode->users)}}</td>
                    <td>{!! intStatusLabel($promocode->status) !!}</td>
                  </tr>
                    @endforeach
                 </tbody>
            </table>
        </div>
      </div>
 </div>

<script src="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/js/scripts.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/js/custom/widgets.js')}}"></script>

</body>
</html>