@extends('dashboard.layouts.master')
@section('content')
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                {{__('admin.list')}}
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <a href="{{route('notification-messages.create')}}" type="button" class="btn btn-primary">
                        <span class="svg-icon svg-icon-2">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><rect fill="#000000" x="4" y="11" width="16" height="2" rx="1" /><rect fill="#000000" opacity="0.5" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000)" x="4" y="11" width="16" height="2" rx="1" /></svg>
                        </span>
                        {{__('admin.create')}}</a>
                </div>
            </div>
        </div>
      
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
                <thead>

                 <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                     <th class="min-w-125px">#</th>
                     <th class="min-w-125px">{{__('admin.title')}}</th>
                     <th class="min-w-125px">{{__('admin.number_of_users')}}</th>
                     <th class="min-w-125px">{{__('admin.created')}}</th>
                 </tr>
                 </thead>
                 <tbody class="text-gray-600 fw-bold">
                     @foreach($notifications  as $app)
                    <tr>
                        <td>{{ $app->id }}</td>
                        <td>{{ $app->title }}</td>
                        <td>{{count($app->users) }}</td>
                        <td>{{ $app->created_at }}</td>
                   </tr>
                      @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('after_content')
@endsection
@section('css')
    <link href="{{asset('dashboard/dist/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('script')
    <script src="{{asset('dashboard/dist/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script>
        $(function (){
            $('#db_table').dataTable({
                "ordering": false
            });
        })
    </script>
@endsection
