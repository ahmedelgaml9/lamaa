<html>
<head>
<base href="">
    <meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('dashboard/dist/assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <title>{{__('admin.offers_reports')}}</title>
</head>
    <body> 

    <h4 style="text-align:right; margin:20px;">{{__('admin.offers_reports')}}</h4>
        <div class="card">
              <div class="card-header border-0 pt-5">
                   <div class="card-toolbar">
                </div>
             </div>

           <div class="card-body pt-0">
              <table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
                  <thead>
                      <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">{{__('admin.status')}}</th>
                        <th class="min-w-125px">{{__('admin.get')}}</th>
                        <th class="min-w-125px">{{__('admin.take')}}</th>
                        <th class="min-w-125px">{{__('admin.name')}}</th>
                        <th class="min-w-125px">#</th>

                    </tr>

                </thead>
                 <tbody class="text-gray-600 fw-bold">
                    @foreach($offers as   $key => $offer)
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <td>
                        {!! intStatusLabel($offer->status) !!}
                    </td>
                    <td>{{ $offer->get }}</td>
                    <td>{{ $offer->take }}</td>
                    <td>{{ $offer->name }}</td>
                    <td>{{ ++$key }}</td>
                    @endforeach
                 </tbody>
            </table>
        </div>
    </div>

<script src="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/js/scripts.bundle.js')}}"></script>

</body>
</html>