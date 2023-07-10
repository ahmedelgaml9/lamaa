@extends('dashboard.layouts.master')
@section('toolbar')
@endsection
@section('content')
    <div class="card card-xl-stretch mb-5 mb-xl-10">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">{{$data->id? __('admin.edit_info'): __('admin.add_new')}}</span>
            </h3>
            <div class="card-toolbar">
            </div>
        </div>
        @if($data->id)
            {!! Form::open(['url' => route('promocodes.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
        @else
            {!! Form::open(['url' => route('promocodes.store'), 'method' => 'post', 'files'=>true]) !!}
        @endif
        <div class="card-body py-3">
            <div class="tab-content">
                @foreach(supportedLanguages() as $keyLang => $valueLang)
                    <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                        <div class="row">
                            <div class="col-md-6">
                              <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.code')}}</label>
                                 <div class="mb-5 {{ $errors->has('code') ? 'has-error' : '' }}">
                                     <input type="text" name="code" class="form-control form-control-solid" placeholder="{{__('admin.code')}}" value="{{old('code', $data->code)}}"/>
                                     <span class="text-danger">{{ $errors->first('code') }}</span>
                                 </div>
                            </div>

                             <div class="col-md-6">
                                  <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.description')}}</label>
                                      <div class="mb-5">
                                        <input type="text"  name="description" class="form-control form-control-solid" placeholder="{{__('admin.description')}}" value="{{old('description', $data->description )}}"/>
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                  </div>
                              </div>
                          
                              <div class="col-md-6">
                                  <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.status')}}</label>
                                      <div class="mb-5">
                                         <select name="status" class="form-select">
                                             <option value="1">{{__('admin.active')}}</option>
                                             <option value="0">{{__('admin.inactive')}}</option>
                                          </select>
                                    </div>
                                </div>

                              <div class="col-md-6">
                                 <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.type')}}</label>
                                     <div class="mb-5 {{ $errors->has('offer_type') ? 'has-error' : '' }}">
                                          <select name="type"  id="type" class="form-select">
                                               <option value="">اختر النوع</option>
                                               <option value="1"  @if($data->type == 1) selected @endif>{{__('admin.percent')}}</option>
                                               <option value="2"  @if($data->type ==  2) selected @endif>{{__('admin.amount')}}</option>
                                         </select>
                                     </div>
                               </div>

                              <div class="col-md-6" style="display:none" id="percent">
                                  <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.percent')}}</label>
                                     <div class="mb-5 {{ $errors->has('settings') ? 'has-error' : '' }}">
                                        @php
                                        $percentSettings = $data->settings?json_decode($data->settings, true):[];
                                        $percent = isset($percentSettings['percent'])?$percentSettings['percent']:null
                                        @endphp
                                        <input type="text" name="settings" class="form-control form-control-solid" value="{{old('settings',$percent)}}" placeholder="{{__('admin.percent')}}"/>
                                        <span class="text-danger">{{ $errors->first('settings') }}</span>
                                  </div>
                              </div>

                              <div class="col-md-6" style="display:none" id="amount">
                                  <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.amount')}}</label>
                                   <div class="mb-5 {{ $errors->has('amount') ? 'has-error' : '' }}">
                                       <input type="text" name="amount" class="form-control form-control-solid" value="{{old('amount',$data->amount)}}" placeholder="{{__('admin.amount')}}"/>
                                       <span class="text-danger">{{ $errors->first('amount') }}</span>
                                  </div>
                              </div>

                               <div class="col-md-6">
                                  <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.products_type')}}</label>
                                      <div class="mb-5 {{ $errors->has('offer_type') ? 'has-error' : '' }}">
                                          <select name="product_type"  id="product_type" class="form-select">
                                            <option value="">اختر النوع</option>
                                            <option value="1">كل المنتجات</option>
                                            <option value="2">منتجات محددة</option>
                                         </select>
                                     </div>
                                 </div>

                                 <div class="col-md-6" id="products"  style="display:none;">
                                      <div class="d-flex flex-column mb-8 fv-row">
                                         <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.products')}}</label>
                                              <select class="form-select"  data-control="select2" name="products[]" multiple="">
                                                  @foreach($products as $product)
                                                    <option value="{{$product->id}}">{{$product->title}}</option>
                                                  @endforeach
                                              </select>
                                          </div>
                                     </div>
                               </div>
                                  
                            <div class="row">
                               <div class="col-md-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.start_at')}}</label>
                                    <div class="mb-5 {{ $errors->has('start_at') ? 'has-error' : '' }}">
                                        <input type="text" name="start_at" class="form-control form-control-solid" id="kt_datepicker_1"  value="{{old('start_at',$data->start_at)}}" placeholder="{{__('admin.start_at')}}"/>
                                        <span class="text-danger">{{ $errors->first('start_at') }}</span>
                                    </div>
                               </div>

                            <div class="col-md-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.expires_at')}}</label>
                                    <div class="mb-5 {{ $errors->has('expires_at') ? 'has-error' : '' }}">
                                        <input type="text" name="expires_at" class="form-control form-control-solid" id="kt_datepicker_2" value="{{old('expires_at',$data->expires_at)}}" placeholder="{{__('admin.expires_at')}}"/>
                                        <span class="text-danger">{{ $errors->first('expires_at') }}</span>
                                    </div>
                                </div>
                           </div>

                            <div class="row">
                                 <div class="col-md-6">
                                     <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.num_of_use')}}</label>
                                        <div class="mb-5">
                                            <input type="text" name="num_of_use" class="form-control form-control-solid" value="{{old('num_of_use',$data->num_of_use)}}" placeholder="{{__('admin.num_of_use')}}"/>
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
@section('script')

<script type="text/javascript">

$("#kt_datepicker_1").flatpickr();
$("#kt_datepicker_2").flatpickr();

if($("#type option:selected").val() == 1)
{

    $("#percent").show();
    $("#amount").hide();

}

if($("#type option:selected").val() == 2)
{

    $("#percent").hide();
    $("#amount").show();

}

$("#type").change(function(){

if(this.value == 1)
{

    $("#percent").show();
    $("#amount").hide();


}
if(this.value == 2)
{

    $("#percent").hide();
    $("#amount").show();

}

});

$("#product_type").change(function(){

if(this.value == 2 )
{

  $("#products").show();

}

else{

   $("#products").hide();

}
});

</script>
@endsection
