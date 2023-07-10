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

         {!! Form::open(array('route'=>'export_offers','method'=>'POST','class'=>'form-inline')) !!} 

        <label  class="col-form-label  fw-bold fs-6"></label>

        <button type="submit" class="btn btn-sm btn-flex btn-dark btn-active-primary fw-bolder"><i class="fa fa-file-download"></i>تصدير</button>
        
        {!! Form::close() !!}

        <label  class="col-form-label  fw-bold fs-6"></label>

        <a href="{{url('admin/printoffers/pdf')}}" target="_blank" class="btn btn-sm btn-danger"><i class="fa fa-print"></i></a>

</div>
@endsection

@section('content')

<div class="d-flex flex-wrap flex-stack pb-7">
        <div class="d-flex flex-wrap align-items-center my-1">
            <h3 class="fw-bolder me-5 my-1">{{$page_title}}</h3>
            <div class="d-flex align-items-center position-relative my-1">
                <span class="svg-icon svg-icon-3 position-absolute ms-3">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
														<path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
													</g>
												</svg>
											</span>
                <input type="text" id="search" class="form-control form-control-white form-control-sm w-150px ps-9" placeholder="Search" />
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

function fetch_customer_data(query = '')
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
}

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
