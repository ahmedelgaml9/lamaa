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
                        @include('dashboard.orders.partials.filter_options')
                </div>    
         </div>
@endsection
@section('content')
    <div class="d-flex flex-wrap flex-stack pb-7">
        <div class="d-flex flex-wrap align-items-center my-1">
            @if(request()->get('user_id')!= null)
              <h3 class="fw-bolder me-5 my-1">{{__('admin.weborders')}} ({{\App\Models\Order::where('user_id',request()->get('user_id'))->count()}})</h3>
            @elseif(request()->get('search_word'))
             <h3 class="fw-bolder me-5 my-1">{{__('admin.weborders')}}</h3>
            @else
              <h3 class="fw-bolder me-5 my-1">{{__('admin.weborders')}} ({{\App\Models\Order::whereNotNull('id')->count()}})</h3>
            @endif
              <div class="d-flex align-items-center position-relative my-1">
                   <form action="{{request()->url()}}" id="search_with_word_form" style="display: inline-flex">
                       <input name="search_word" type="text" id="search_with_word_input" class="form-control form-control-white form-control-sm w-250px ps-9" placeholder=" بحث بالاسم ورقم الجوال ورقم الاوردر" value="{{request()->get('search_word')}}"/> <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-search"></i></button>
                   </form>
              </div>
         </div>
     
         <div class="d-flex flex-wrap my-1">
            <div class="d-flex my-0">
                @if(!is_null(request()->get('status')))
                   <button class="btn btn-sm btn-danger" type="button"  data-bs-toggle="modal" data-bs-target="#kt_change_status_modal" >{{__('admin.change_status')}}</button>
                @endif
            </div>
        
                 @if($template == 'downloads')
                <div class="d-flex my-0">
                     @php
                         $requestQuery = request()->query();
                         $requestQuery['template'] = request()->get('template', 'orders');
                      @endphp
                    <a href="{{route('orders.exportToExcelDownloadsTemplate', $requestQuery)}}" class="btn btn-sm btn-flex btn-dark btn-active-primary fw-bolder"><i class="fa fa-file-download"></i>تصدير</a>
                </div>
                  @else
                <div class="d-flex my-0">
                     @php
                       $requestQuery = request()->query();
                       $requestQuery['template'] = request()->get('template', 'orders');
                     @endphp
                      <a href="{{route('orders.exportToExcel', $requestQuery)}}" class="btn btn-sm btn-flex btn-dark btn-active-primary fw-bolder"><i class="fa fa-file-download"></i>تصدير</a>
                 </div>
                 @endif
            </div>
       </div>
    
       <div class="tab-content">
           <div id="kt_orders_list_view" class="tab-pane fade show active">
               <form action="{{route('orders.change_bulk_statuses')}}" method="POST">
                    @csrf
                    @include('dashboard.orders.partials.orders_list')
                    @include('dashboard.orders.partials.change_bulk_statuses_modal')
                </form>
           </div>
      </div>
 
@endsection
@section('modal')
    @include('dashboard.orders.partials.change_status_modal')
    @include('dashboard.orders.partials.import_orders_modal')

@endsection
@section('script')
    <script type="text/javascript" src="{{asset('assets/plugins/table-to-excel/dist/tableToExcel.js')}}"></script>
    <script>
        function exportTableToExcel(){
            TableToExcel.convert(document.getElementById("kt_project_users_table"), {
                name: "table1.xlsx",
                sheet: {
                    name: "Sheet 1"
                }
            });
        }
    </script>

    @php
    $createdAtArray = request()->get('created_at')?explode(' >> ', request()->get('created_at')):[];
    $deliveryDateArray = request()->get('delivery_date')?explode(' >> ', request()->get('delivery_date')):[];
    @endphp

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

        $(".delivered-at-date-picker").flatpickr({
            mode: "range",
            maxDate: "today",
            dateFormat: "Y-m-d",
            @if(!empty($deliveryDateArray))
            defaultDate: [{{$deliveryDateArray[0]}}, {{$deliveryDateArray[1]}}],
            @endif
            onChange: function(selectedDates, dateStr, instance){
                $(".delivered-at-date-picker").val(dateStr.replace('to', '>>'));
            }
        });

        $(function () {
            $('body').on('click', '#parent-checkbox-table', function (e) {
                var input = $('#parent-checkbox-table');
                if(input.is(":checked")){
                    $('.child-checkbox-row').prop('checked', true);
                }else{
                    $('.child-checkbox-row').prop('checked', false);
                }
            });

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
                    $('#kt_orders_list_view').html(data.listView);
                }).fail(function () {
                    alert('Items could not be loaded.');
                });
            }
        });
    </script>
@endsection
