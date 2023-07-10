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

        <a href="{{route('export_compansations', request()->query())}}" class="btn btn-sm btn-flex btn-dark btn-active-primary fw-bolder"><i class="fa fa-file-download"></i>تصدير</a>
          <label  class="col-form-label  fw-bold fs-6"></label>
          @if(request()->query() == null)<a href="{{url('admin/compansationsreport/pdf')}}" target="_blank" class="btn  btn-sm btn-danger"><i class="fa fa-print"></i></a>@endif
</div>
@endsection
@section('content')
        <div class="card">
                <div class="card-header border-0 pt-5">
                <div class="card-title">
                   {{__('admin.compansations')}}
                  </div>
                <div class="card-toolbar">
             </div>
        </div>

          <div class="card-body pt-0">
             @if(Session::has('message'))
               <div class="col-sm-6">
                <div class="alert alert-danger text-center"><em> {!! session('message') !!}</em></div>
              </div>
              @endif

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
                        @php
                            $user = $c->user;
                            $order = $c->order;
                        @endphp
                    <tr>
                        <td>{{ $c->order->id }}</td>
                        <td>{{ $c->order->user->name }}</td>
                        <td>{{ $c->order->created_at }}</td>
                       <td>{{ $c->order->total }}</td>
                       <td>{!! orderStatusLabel($c->order->status) !!}</td>
                       <td>{{ $user?$user->name:null }}</td>
                       <td>{{ $order?$order->driver_name:null }}</td>
                     </tr>
                        @endforeach
                   </tbody>
               </table>
                  {!!$compansations->links('dashboard.partials.paginator', ['disableJS' => true]) !!}
                  <div class="row">
                    <div class="col-sm-6">
                       <h4>{{__('admin.compansations_numbers')}}: {{$count}}</h4>
                   </div>
                </div>
            </div>
       </div>

                            <div class="modal fade" id="subscriptions" tabindex="-1" aria-hidden="true">
                                    @foreach(supportedLanguages() as $keyLang => $valueLang)
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
                                                  <form action="{{route('compansations_reports')}}" class="px-7 py-5" method="get">
                                                   @csrf

                                                   <div class="fv-row mb-10">
                                                      <label  class=" col-form-label fw-bold fs-6">{{__('admin.name')}}</label>
                                                       <input  type="text" class="form-control form-control-solid" name="name" placeholder="{{__('admin.name')}}"/>

												                          	</div>

                                                    <div class="fv-row mb-10">
                                                         <label  class=" col-form-label fw-bold fs-6">{{__('admin.phone')}}</label>
                                                         <input type="text" class="form-control form-control-solid" name="mobile" placeholder="{{__('admin.phone')}}" />
                                                    </div>

                                                    <div class="fv-row mb-10">
                                                      <label  class=" col-form-label fw-bold fs-6">{{__('admin.order_id')}}</label>
                                                       <input  type="text" class="form-control form-control-solid" name="order_id" placeholder="{{__('admin.order_id')}}"/>
												                           	</div>

                                                        <div class="fv-row mb-10">
                                                           <label  class="col-form-label fw-bold fs-6">{{__('admin.created')}}</label>
                                                            <input name="created_at" type="text" class="form-control created-at-date-picker" placeholder="01-01-2022 >> 01-01-2023" >
                                                       </div>

                                                        <div class="fv-row mb-10">
                                                            <label class="form-label fw-bold">{{__('admin.status')}}:</label>
                                                              <div>
                                                               <select name="status" class="form-select form-select-solid">
                                                                  <option></option>
                                                                  @foreach(orderStatusesList() as $key => $value)
                                                                  <option value="{{$key}}">{{$value}}</option>
                                                                  @endforeach
                                                              </select>
                                                          </div>
                                                     </div>

                                                  <div class="text-center">
                                                     <button type="reset"  class="btn btn-light me-3">Discard</button>
                                                      <button type="submit" class="btn btn-primary">
                                                       <span class="indicator-label">Submit</span>
                                                     </button>
                                                  </div>
                                                </form>
                                              </div>
                                             </div>
                                         </div>
                                         @endforeach
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
      $(".created-at-date-picker").flatpickr({
            mode: "range",
            maxDate: "today",
            dateFormat: "Y-m-d",
            @if(!empty($createdAtArray))
            defaultDate: [{{$createdAtArray[0]}}, {{$createdAtArray[1]}}],
            @endif
            onChange: function(selectedDates, dateStr, instance){
                $(".created-at-date-picker").val(dateStr.replace('to', '>>'));
            }
        });

 $(function (){
     $('#db_table').dataTable({
      "ordering": false
     });
})

</script>
@endsection
