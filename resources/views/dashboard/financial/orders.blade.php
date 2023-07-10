@extends('dashboard.layouts.master')
@section('content')
  <div class="d-flex flex-wrap flex-stack pb-7">
      <div class="d-flex flex-wrap align-items-center my-1">
           <h3 class="fw-bolder me-5 my-1">{{$page_title}}</h3>
          
          </div>
        
         <div class="d-flex flex-wrap my-1">

          </div>

      </div>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                {{__('admin.list')}}
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    
                </div>
            </div>
        </div>
      
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
            <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">#</th>
                    <th class="min-w-125px">{{__('admin.name')}}</th>
                    <th class="min-w-125px">{{__('admin.created')}}</th>
                    <th class="min-w-125px">{{__('admin.total')}}</th>
                    <th class="text-end min-w-100px">{{__('admin.actions')}}</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold">
                    @foreach($orders  as $order)
                  <tr>
                   <td>{{$order->id}}</td>
                   @if(isset($order->user))<td>{{$order->user->name }}</td>@endif
                    <td>{{ date('Y-m-d',strtotime($order->created_at))}}</td>
                    <td>{{$order->total}}</td>
                    <td class="text-end">
                            @php
                                $actions = [
                                   ['label' => __('admin.show'), 'url' => route('orders.show', $order->id)],

                                  ];

                               @endphp
                               @include('dashboard.components.table_actions', ['actions', $actions])

                           </td>
                        </td>
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
            $(".confirm-delete").click(function (e) {
                e.preventDefault();
                var url = $(this).data("href");
                $("#delete-modal").modal("show");
                $("#delete-link").attr("href", url);
            });
    </script>
@endsection
