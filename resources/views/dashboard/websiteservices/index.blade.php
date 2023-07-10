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
            @if(Session::has('success'))
               <div class="col-sm-6">
                  <div class="alert alert-success text-center" id="Message"><em> {!! session('success') !!}</em></div>
             </div>
            @endif
          <div class="card-header border-0 pt-6">
              <div class="card-title">
             </div>
              <div class="card-toolbar">
                 <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <a href="{{route('websiteservice.create')}}" type="button" class="btn btn-primary">
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
                        <th class="min-w-10px">#</th>
                        <th class="min-w-150px">{{__('admin.name')}}</th>
                        <th class="min-w-150px">{{__('admin.image')}}</th>
                        <th class="min-w-50px text-end">{{__('admin.actions')}}</th>
                    </tr>
                </thead>
                   <tbody class="text-gray-600 fw-bold">
                        @foreach($services as $service)
                      <tr>
                         <td>{{$service->id}}</td>
                         <td>{{$service->title}}</td>
                         <td>
                            <div class="d-flex align-items-center">
                                <div class="me-5 position-relative">
                                    <div class="symbol symbol-70px">
                                        <img src="{{$service->img}}" alt="image"/>
                                     </div>
                                 </div>
                             </div>
                         </td>
                       
                           <td class="text-end">
                              <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">{{__('admin.actions')}}
                                 <span class="svg-icon svg-icon-5 m-0"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon points="0 0 24 0 24 24 0 24" /><path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)" /></g></svg></span><!--end::Svg Icon--></a>
                              <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="{{route('websiteservice.edit', $service->id)}}" class="menu-link px-3">{{__('admin.edit')}}</a>
                                </div>
                                 <div class="menu-item px-3">
                                     <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_1{{$service->id}}">{{__('admin.delete')}}</a>
                                 </div>
                             </div>
                          </td>

                               <div id="kt_modal_1{{$service->id}}"  tabindex="-1" class="modal fade">
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
                                                           {!! Form::open(['url' => route('websiteservice.destroy',  $service->id), 'method' => 'delete']) !!}
                                                             <button type="submit" class="btn btn-primary mt-2">{{__('admin.Delete')}}</a>
                                                           {!! Form::close() !!}
                                                    </div>
                                                </div>
                                             </div>
                                        </div>
                                    </div>
                                </div>
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

        setTimeout(function() {
            $("#Message").hide()
          }, 1000);
    </script>
@endsection
