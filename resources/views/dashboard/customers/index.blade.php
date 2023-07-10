@extends('dashboard.layouts.master')
@section('toolbar')
     <div class="d-flex align-items-center py-1">
           <div class="me-4">
                 <a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bolder" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                      <span class="svg-icon svg-icon-5 svg-icon-gray-500 me-1">
                          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                               <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                   <rect x="0" y="0" width="24" height="24" />
                                   <path d="M5,4 L19,4 C19.2761424,4 19.5,4.22385763 19.5,4.5 C19.5,4.60818511 19.4649111,4.71345191 19.4,4.8 L14,12 L14,20.190983 C14,20.4671254 13.7761424,20.690983 13.5,20.690983 C13.4223775,20.690983 13.3458209,20.6729105 13.2763932,20.6381966 L10,19 L10,12 L4.6,4.8 C4.43431458,4.5790861 4.4790861,4.26568542 4.7,4.1 C4.78654809,4.03508894 4.89181489,4 5,4 Z" fill="#000000" />
                               </g>
                         </svg>
                    </span>
                       {{__('admin.advanced_search')}}</a>
                       @include('dashboard.customers.partials.filter_options')
                </div>
                  <a href="{{route('customers.create')}}" class="btn btn-sm btn-primary">{{__('admin.create')}}</a>
           </div>

@endsection
@section('content')

    <div class="d-flex flex-wrap flex-stack pb-7">
        <div class="d-flex flex-wrap align-items-center my-1">
            <h3 class="fw-bolder me-5 my-1">{{__('admin.customers')}} ({{\App\User::whereNotNull('id')->where('user_type', 'customer')->count()}})</h3>
            <div class="d-flex align-items-center position-relative my-1">
                <form action="{{route('customers.index')}}" id="search_with_word_form" style="display: inline-flex">
                    <input name="search_word" type="text" id="search_with_word_input" class="form-control form-control-white form-control-sm w-150px ps-9" placeholder="بحث سريع" value="{{request()->get('search_word')}}"/> <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-search"></i></button>
                </form>
                
            </div>
        </div>
      
        <div class="d-flex flex-wrap my-1">
            <div class="d-flex my-0">
                <label>
                    <button class="btn btn-sm btn-flex btn-danger btn-active-primary fw-bolder" type="button"  data-bs-toggle="modal" data-bs-target="#kt_import_customers_modal" ><i class="fa fa-file-upload"></i> {{__('استيراد')}}</button>
                </label>
            </div>
            <div class="d-flex my-0" style="margin: 0px 10px;">
                <a href="{{route('customers.exportToExcel', request()->query())}}" class="btn btn-sm btn-flex btn-dark btn-active-primary fw-bolder"><i class="fa fa-file-download"></i>تصدير</a>
            </div>
         
               <ul class="nav nav-pills me-6 mb-2 mb-sm-0">
                      <li class="nav-item m-0">
                            <a class="btn btn-sm btn-icon btn-light btn-color-muted btn-active-primary me-3 active" data-bs-toggle="tab" href="#kt_customers_grid_view">
                               <span class="svg-icon svg-icon-2">
                                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                         <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                              <rect x="5" y="5" width="5" height="5" rx="1" fill="#000000" />
                                              <rect x="14" y="5" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
                                              <rect x="5" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
                                              <rect x="14" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
                                         </g>
                                    </svg>
                               </span>
                           </a>
                      </li>
 
                      <li class="nav-item m-0">
                         <a class="btn btn-sm btn-icon btn-light btn-color-muted btn-active-primary" data-bs-toggle="tab" href="#kt_customers_list_view">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                   <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                       <rect x="0" y="0" width="24" height="24" />
                                       <rect fill="#000000" opacity="0.3" x="4" y="5" width="16" height="6" rx="1.5" />
                                       <rect fill="#000000" x="4" y="13" width="16" height="6" rx="1.5" />
                                    </g>
                               </svg>
                            </span>
                        </a>
                    </li>
                </ul>
           </div>
      </div>
   
     <div class="tab-content">
        <div id="kt_customers_grid_view" class="tab-pane fade show active">
            @include('dashboard.customers.partials.customers_grid')
        </div>
        
        <div id="kt_customers_list_view" class="tab-pane fade">
            <!--begin::Card-->
            @include('dashboard.customers.partials.customers_list')
            <!--end::Card-->
        </div>
        <!--end::Tab pane-->
    </div>
    <!--end::Tab Content-->
@endsection

@section('modal')
    @include('dashboard.customers.partials.import_customers_modal')
@endsection

@section('script')
    <script>
        $(function () {
            $('body').on('click', '.page-item a', function (e) {
                e.preventDefault();
                let btn = $(this);
                let url = btn.attr('href');
                getPaginationItem(btn);
                window.history.pushState("", "", url);
            });

            function getPaginationItem(btn) {
                let url = btn.attr('href');
                $.ajax({
                    url: url,
                    cache: false
                }).done(function (data) {
                    $('#kt_customers_grid_view').html(data.gridView);
                    $('#kt_customers_list_view').html(data.listView);
                }).fail(function () {
                    alert('Items could not be loaded.');
                });
            }
        });
    </script>
@endsection
