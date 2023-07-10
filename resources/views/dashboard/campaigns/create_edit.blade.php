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
            {!! Form::open(['url' => route('campaigns.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
        @else
            {!! Form::open(['url' => route('campaigns.store'), 'method' => 'post', 'files'=>true]) !!}
        @endif

        <div class="card-body py-3">
            <div class="tab-content">
                @foreach(supportedLanguages() as $keyLang => $valueLang)
                    <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                        <div class="row">
                            <div class="col-md-6">
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.type')}}</label>
                                <div class="mb-5 {{ $errors->has('type') ? 'has-error' : '' }}">
                                    <input type="text" name="type" class="form-control form-control-solid" placeholder="{{__('admin.type')}}" value="{{old('type', $data->type)}}"/>
                                    <span class="text-danger">{{ $errors->first('type') }}</span>
                                </div>
                            </div>
                          
                            <div class="col-md-6">
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.name')}}</label>
                                <div class="mb-5 {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <input type="text" name="name" class="form-control form-control-solid" placeholder="{{__('admin.name')}}" value="{{old('name', $data->name)}}"/>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                           <div class="col-md-6">
                             <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.offer_type')}}</label>
                               <div class="mb-5 {{ $errors->has('offer_type') ? 'has-error' : '' }}">
                                 <select name="offer_type" id="offer_type"  class="form-select">
                                       <option value="">اختر النوع</option>
                                       <option value="discount" @if($data->offer_type == "discount") selected @endif>{{__('admin.discount')}}</option>
                                       <option value="free" @if($data->offer_type == "free") selected @endif>{{__('admin.free')}}</option>
                                        <option value="other" @if($data->offer_type == "other") selected @endif>{{__('admin.other')}}</option>
                                     </select>
                                  </div>
                               </div>

                               <div class="col-md-6" id="promocode" style="display:none">
                                   <div class="d-flex flex-column mb-8 fv-row">
                                     <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.promocodes')}}</label>
                                        <select class="form-select"  data-control="select2"  name="promocode">
                                             @foreach($promocodes as $promocode)
                                               <option value="{{ $promocode->id }}">{{ $promocode->code }}</option>
                                             @endforeach
                                         </select> 
                                      </div>
                                  </div>
                              </div>

                            <div class="row">
                               <div class="col-md-6">
                                   <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.start_at')}}</label>
                                       <div class="mb-5 {{ $errors->has('start_at') ? 'has-error' : '' }}">
                                          <input type="text" name="start_date" class="form-control form-control-solid" id="kt_datepicker_1"  value="{{old('start_date',$data->start_date)}}" placeholder="{{__('admin.start_at')}}"/>
                                          <span class="text-danger">{{ $errors->first('start_at') }}</span>
                                     </div>
                                </div>

                               <div class="col-md-6">
                                  <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.expires_at')}}</label>
                                     <div class="mb-5 {{ $errors->has('end_date') ? 'has-error' : '' }}">
                                        <input type="text" name="end_date" class="form-control form-control-solid" id="kt_datepicker_2" value="{{old('end_date',$data->end_date)}}" placeholder="{{__('admin.expires_at')}}"/>
                                        <span class="text-danger">{{ $errors->first('end_date') }}</span>
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
if($("#offer_type option:selected").val() == "discount")
{

$("#promocode").show();

}


$("#offer_type").change(function(){

if(this.value  == "discount")

{

$("#promocode").show();

}
else{

$("#promocode").hide();

}

}); 

$("#kt_datepicker_1").flatpickr();
$("#kt_datepicker_2").flatpickr();

</script>
@endsection
