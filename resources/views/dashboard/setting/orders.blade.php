@extends('dashboard.layouts.master')
@section('toolbar')
@endsection
@section('content')
    <div class="card card-xl-stretch mb-5 mb-xl-10">
          @if(Session::has('success'))
               <div class="col-sm-6">
                <div class="alert alert-success text-center"><em> {!! session('success') !!}</em></div>
            </div>
            @endif
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">{{$page_title}}</span>
            </h3>
            <div class="card-toolbar">
               
            </div>
        </div>

            {!! Form::open(['url' => route('setting.update'), 'method' => 'post', 'files'=>true]) !!}

          <div class="card-body py-3">
               <div class="tab-content">
                  @foreach(supportedLanguages() as $keyLang => $valueLang)
                    <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                        <div class="row">
                            <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.min_number_products')}}</label>
                                    <div class="mb-5">
                                         <input type="hidden" name="types[]" value="min_number_products">
                                         <input type="text" class="form-control" placeholder="{{__('admin.min_number_products')}}" name="min_number_products" value="{{ get_setting('min_number_products')}}">
                                   </div>
                             </div>

                             <div class="col-md-6">
                                 <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('الحد الأدنى لقيمة الطلب')}}</label>
                                   <div class="mb-5">
                                        <input type="hidden" name="types[]" value="min_order_price">
                                        <input type="number" step=".01" class="form-control" placeholder="{{__('0.00')}}" name="min_order_price" value="{{ get_setting('min_order_price')}}">
                                  </div>
                             </div>

                             <div class="col-md-6">
                                  <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.store_title')}}</label>
                                      <div class="mb-5">
                                            <input type="hidden" name="types[]" value="store_title">
                                            <input type="text" class="form-control" placeholder="{{__('admin.store_title')}}" name="store_title" value="{{ get_setting('store_title')}}">
                                      </div>
                               </div>

                               <div class="col-md-6">
                                     <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('trans_admin.statuses_available_to_cancel')}}</label>
                                        <div class="mb-5">
                                           @php
                                               $selectedStatuses = get_setting('statuses_available_to_cancel');
                                              $selectedStatuses = $selectedStatuses?json_decode($selectedStatuses):[];
                                          @endphp
                                        <input type="hidden" name="types[]" value="statuses_available_to_cancel">
                                          <select class="form-select"  data-control="select2" name="statuses_available_to_cancel[]" multiple="">
                                              @foreach(\App\Models\Order::ACTIVE_ORDER_STATUS_LIST as $statusNumber)
                                               @if(!in_array($statusNumber, [\App\Models\Order::STATUS_CANCELED, \App\Models\Order::STATUS_DELIVERED]))
                                                <option value="{{$statusNumber}}" @if(in_array($statusNumber, $selectedStatuses)) selected @endif>{{ __('admin.orders_status_options.'.$statusNumber)}}</option>
                                               @endif
                                               @endforeach
                                         </select>
                                      </div>
                                  </div>
                             </div>
                       </div>
                      @endforeach
                </div>
          </div>

          <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2"  onClick="window.location.href=window.location.href">{{__('admin.discard')}}</button>
                <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">{{__('admin.save')}}</button>
          </div>
             {!! Form::close() !!}
       </div>
@endsection
