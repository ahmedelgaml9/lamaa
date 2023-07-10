@extends('dashboard.layouts.master')
@section('toolbar')
     <div class="d-flex align-items-center py-1">
      
</div>
@endsection
@section('content')
<div class="d-flex flex-wrap flex-stack pb-7">
        <div class="d-flex flex-wrap align-items-center my-1">
            <h3 class="fw-bolder me-5 my-1">{{$page_title}}</h3>
            <div class="d-flex flex-wrap align-items-center my-1">
                <div class="d-flex align-items-center position-relative my-1">
                    <form action="{{ route('offers.index')}}" id="search_with_word_form" style="display: inline-flex">
                        <input name="search_word" type="text" id="search_with_word_input" class="form-control form-control-white form-control-sm w-150px ps-9" placeholder="بحث سريع" value="{{request()->get('search_word')}}"/> <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
         </div>

          <div class="d-flex flex-wrap my-1">

          </div>

      </div>

     <div class="card">
           @if(Session::has('success'))
               <div class="col-sm-6">
                <div class="alert alert-success text-center"><em> {!! session('success') !!}</em></div>
            </div>
            @endif
        <div class="card-header border-0 pt-6">
               <div class="card-toolbar">
            
               </div>
                 <div class="card-toolbar">
                      <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                          <a href="{{route('offers.create')}}" type="button" class="btn btn-primary">
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
                    <th class="min-w-125px">{{__('admin.name')}}</th>
                    <th class="min-w-125px">{{__('admin.type')}}</th>
                    <th class="min-w-125px">{{__('admin.status')}}</th>
                    <th class="text-end min-w-100px">{{__('admin.actions')}}</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold">
                  @foreach($offers  as $offer)
                  <tr>
                    <td>{{ $offer->id }}</td>
                    <td>{{ $offer->name }}</td>
                    <td>{{ $offer->type }}</td>
                    <td>
                        {!! intStatusLabel($offer->status) !!}
                    </td>
                    <td class="text-end">
                        <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">{{__('admin.actions')}}
                            <span class="svg-icon svg-icon-5 m-0"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon points="0 0 24 0 24 24 0 24" /><path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)" /></g></svg></span><!--end::Svg Icon--></a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                            <div class="menu-item px-3">
                                <a href="{{route('offers.edit', $offer->id)}}" class="menu-link px-3">{{__('admin.edit')}}</a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_1{{$offer->id}}">{{__('admin.delete')}}</a>                 
                            </div>
                        </div>
                    </td>
                </tr>

                <div id="kt_modal_1{{$offer->id}}"  tabindex="-1" class="modal fade">
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
                                        {!! Form::open(['url' => route('offers.destroy', $offer->id), 'method' => 'delete']) !!}
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
                                                   <form action="{{route('offers_reports')}}" class="px-7 py-5" method="get">
                                                      @csrf
                                                     <div class="fv-row mb-10">
                                                          <label class="form-label fw-bold">{{__('admin.products')}}</label>
                                                             <select class="form-select" name="product">
                                                                <option value=""> اختر منتج</option>
                                                                    @foreach($products as $product)
                                                                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                                                                    @endforeach
                                                             </select>
                                                         </div>

                                                         
													 <div class="fv-row mb-10">
                                                          <label class="form-label fw-bold">{{__('admin.cities')}}</label>
                                                            <select class="form-select"  required name="city">
                                                                    <option value="">اختر مدينة</option>
                                                                    @foreach($cities as $city)
                                                                        <option value="{{ $city->getTranslation('name', $keyLang)}}">{{ $city->getTranslation('name', $keyLang)}}</option>
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

/*function fetch_customer_data(query = '')
 {

 $.ajax({
  url:"{{ route('search-offers') }}",
  method:'GET',
  data:{query:query},
  dataType:'json',
  success:function(data)
  {
   $('tbody').html(data.table_data);
   $('#total_records').text(data.total_data);
  }
 })
}*/

$(document).on('keyup', '#search', function(){
 var query = $(this).val();
 fetch_customer_data(query);
});

$(function (){

  $('#db_table').dataTable({
   "ordering": false

     });
    
     });
</script>
@endsection
