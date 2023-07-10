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
            {!! Form::open(array('route'=>'export_cancelled','method'=>'POST','class'=>'form-inline')) !!}  
            <label  class="col-form-label  fw-bold fs-6"></label>
            <button type="submit" class="btn btn-sm btn-flex btn-dark btn-active-primary fw-bolder"><i class="fa fa-file-download"></i>تصدير</button>
             {!! Form::close() !!}
             <label  class="col-form-label  fw-bold fs-6"></label>
               <a href="{{url('admin/cancelledorders/pdf')}}"  target="_blank" class="btn btn-sm btn-danger"><i class="fa fa-print"></i></a>
           </div>

         @endsection
         @section('content')
         <div class="d-flex flex-wrap flex-stack pb-7">
           <div class="d-flex flex-wrap align-items-center my-1">
              <div class="d-flex align-items-center position-relative my-1">
                <form action="{{ route('cancelled_orders')}}" id="search_with_word_form" style="display: inline-flex">
                    <input name="search_word" type="text" id="search_with_word_input" class="form-control form-control-white form-control-sm w-150px ps-9" placeholder="بحث سريع" value="{{request()->get('search_word')}}"/> <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
          </div>
      </div>

        <div class="card">
                <div class="card-header border-0 pt-5">
                <div class="card-title">
                   {{__('admin.cancelled_orders')}}
                  </div>
                  @foreach(supportedLanguages() as $keyLang => $valueLang)
                    <div class="card-toolbar">
                    </div>
                 </div>
                 @endforeach
         
          <div class="card-body pt-0">
             <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">{{__('admin.user_name')}}</th>
                    <th class="min-w-125px">{{__('admin.phone')}}</th>
                    <th class="min-w-125px">{{__('admin.city')}}</th>
                    <th class="min-w-125px">{{__('admin.created')}}</th>
                    <th class="min-w-125px">{{__('admin.total')}}</th>
                    <th class="min-w-125px">{{__('admin.payment_method')}}</th>
                    <th class="min-w-125px">{{__('admin.cancel_reason')}}</th>
                    <th class="min-w-125px">{{__('admin.status')}}</th>

                </thead>
                <tbody class="text-gray-600 fw-bold">
                  @foreach($orders  as $order)
                  @php
                    $address = $order->address;
                    @endphp
                  <tr>
                    @if(isset($order->user))<td>{{$order->user->name }}</td>@endif
                    @if(isset($order->user))<td>{{$order->user->mobile }}</td>@endif
                    <td>{{$address?$address->city_name:'---'}}</td>
                    <td>{{ date('Y-m-d',strtotime($order->created_at))}}</td>
                    <td>{{$order->total}}</td>
                    <td>{{$order->payment_method }}</td>
                    <td>{{$order->cancelled_reason }}</td>
                    <td>{!! orderStatusLabel($order->status) !!}</td>
                  </tr>
                    @endforeach
                   </tbody>
               </table>

               {!! $orders ->links('dashboard.partials.paginator', ['disableJS' => true]) !!}
               <div class="row">
                <div class="col-sm-6">
                  <h4>{{__('admin.orders_number')}}: {{$count}}</h4>
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
                                                  <form action="{{ route('cancelled_orders')}}" class="px-7 py-5" method="get">
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

                                                        <label class="form-label fw-bold">{{__('admin.payment_method')}}:</label>
                                                            <select name="payment_method" class="form-select form-select-solid">
                                                                <option></option>
                                                                @foreach(paymentMethodsList() as $key => $value)
                                                                   <option value="{{$key}}">{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                      </div>

                                                        <div class="fv-row mb-10">
                                                           <label  class="col-form-label fw-bold fs-6">{{__('admin.created')}}</label>
                                                            <input name="created_at" type="text" class="form-control created-at-date-picker" placeholder="01-01-2022 >> 01-01-2023" >
                                                       </div>

                                                        <div class="fv-row mb-10">
                                                            <label class="form-label fw-bold">{{__('admin.cities')}}</label>
                                                                <select class="form-select" name="city" >
                                                                    <option value="">اختر مدينة</option>
                                                                     @foreach($cities as $city)
                                                                         <option value="{{ $city->id }}">{{ $city->getTranslation('name', $keyLang)}}</option>
                                                                     @endforeach
                                                                 </select> 
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

 $(function (){
     $('#db_table').dataTable({
      "ordering": false,

     });
})

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

</script>
@endsection
