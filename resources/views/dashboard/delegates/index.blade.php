 @extends('dashboard.layouts.master')
 @section('toolbar')
       <div class="d-flex align-items-center py-1">
             <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#subscriptions">
                <span class="svg-icon svg-icon-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1" transform="rotate(90 12.75 4.25)" fill="black" />
                        <path d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z" fill="black" />
                        <path d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z" fill="#C4C4C4" />
                   </svg>
               </span>
                  {{__('admin.filter')}}</button>
               @php
                 $requestQuery = request()->query();
               @endphp

             <div class="col-sm-3">
                <a href="{{route('print-delegates',$requestQuery)}}"  target="__blank" class="btn btn-danger" id="print"><i class="fa fa-print"></i></a>
           </div>       
    </div>
@endsection
@section('content')
@inject('carbon', 'Carbon\Carbon')

@php

$type = request()->get('type');
$today =$carbon->today();

@endphp

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

             </div>
                 <div class="card-toolbar">
                      <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                           <a href="{{route('delegates.create')}}" type="button" class="btn btn-primary">
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
                                   <th class="min-w-125px">{{__('admin.user_name')}}</th>
                                   <th class="min-w-125px">{{__('admin.phone')}}</th>
                                   <th class="min-w-125px">{{__('admin.code')}}</th>
                                   <th class="min-w-125px">{{__('admin.completed_orders')}}</th>
                                   <th class="min-w-125px">{{__('admin.total')}}</th>
                                   <th class="text-end min-w-100px">{{__('admin.actions')}}</th>
                              </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-bold">
                              @foreach($delegates as $key=> $user)
                              <tr>

                                <td>{{++$key }}</td>
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
                               <td class="text-end">
                                    <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">{{__('admin.actions')}}
                                         <span class="svg-icon svg-icon-5 m-0"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon points="0 0 24 0 24 24 0 24" /><path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)" /></g></svg></span><!--end::Svg Icon--></a>
                                             <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                     <a href="{{route('delegates.edit', $user->id)}}" class="menu-link px-3">{{__('admin.edit')}}</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="{{route('delegates.showorders', $user->id)}}" class="menu-link px-3">{{__('admin.show_results')}}</a>
                                               </div>
                                    
                                                <div class="menu-item px-3">
                                                     <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_1{{$user->id}}">{{__('admin.delete')}}</a>
                                               </div>
                                            </div>
                                       </td>
                                   </tr>
 
                                    <div id="kt_modal_1{{$user->id}}"  tabindex="-1" class="modal fade">
                                           <div class="modal-dialog">
                                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                                     <div class="modal-content">
                                                        <div class="modal-header" style="text-align:center;">
                                                           <h5 class="modal-title" >{{__('admin.Delete Confirmation')}}</h5>
                                                               <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                                  <span class="svg-icon svg-icon-2x"></span>
                                                             </div>
                                                         </div>
 
                                                          <div class="modal-body text-center">
                                                              <p>{{ __('admin.Areyousure')}}</p>
                                                              <div class="text-center pt-15">
                                                               <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="position:relative;float:right;">{{__('admin.Close')}}</button>
                                                                  {!! Form::open(['url' => route('delegates.destroy', $user->id), 'method' => 'delete']) !!}
                                                                    <button type="submit" class="btn btn-primary mt-2">{{__('admin.Delete')}}</a>
                                                                 {!! Form::close() !!}
                                                           </div>
                                                     </div>
                                                </div>
                                           </div>
                                      </div>
                                 </div>
                              @endforeach
                          </tbody>
                    </table>
                 </div>
            </div>

              <div class="modal fade" id="subscriptions" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered mw-650px">
                       <div class="modal-content">
                          <div class="modal-header">
                              <h2 class="fw-bolder">{{__('admin.filteroptions')}}</h2>
                                  <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                       <span class="svg-icon svg-icon-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                 <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                                 <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                            </svg>
                                       </span>
                                 </div>
                           </div>
  
                           <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                               <form action="{{ route('delegates.index')}}" class="px-7 py-5" method="get">
                                 @csrf
                               <div class="fv-row mb-10">
                                     <label  class="col-form-label fw-bold fs-6">{{__('admin.created')}}</label>
                                       <select name="type" id="type" class="form-select" >
                                            <option value="today">{{__('admin.today')}}</option>
                                            <option value="week">{{__('admin.thisweek')}}</option>
                                            <option value="month">{{__('admin.thismonth')}}</option>
                                            <option value="year">{{__('admin.thisyear')}}</option>
                                      </select>
                                 </div>

                               <div class="text-center">
                                   <button type="submit" class="btn btn-primary">
                                      <span class="indicator-label">Submit</span>
                                  </button>
                             </div>
                         </form>
                    </div>
               </div>
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
