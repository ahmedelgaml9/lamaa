@extends('dashboard.layouts.master')
@section('content')
    <!--begin::Card-->
    <div class="card">
       
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                {{__('admin.list')}}
            </div>

        </div>
    
         <div class="card-body pt-0">
             <table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
                <thead>
                 <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                     <th class="min-w-125px">#</th>
                     <th class="min-w-125px">{{__('admin.user_name')}}</th>
                 </tr>
                 </thead>
                  <tbody class="text-gray-600 fw-bold">
                      @foreach($users as $user)
                    <tr>
                     <td>{{ $user->id }}</td>
                       @if(isset($user->user))<td>{{ $user->user->name }}</td>@endif
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
