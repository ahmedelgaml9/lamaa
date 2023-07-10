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
             {!! Form::open(['url' => route('banks.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
         @else
             {!! Form::open(['url' => route('banks.store'), 'method' => 'post', 'files'=>true]) !!}
         @endif
        <div class="card-body py-3">
            <div class="tab-content">
                     @foreach(supportedLanguages() as $keyLang => $valueLang)
                    <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                        <div class="row">
                            <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.bank_name')}}</label>
                                 <div class="mb-5 {{ $errors->has('name.'.$keyLang) ? 'has-error' : '' }}">
                                     <input type="text" name="name[{{$keyLang}}]" class="form-control form-control-solid" placeholder="{{__('admin.bank_name')}}" value="{{old('name.'.$keyLang, $data->getTranslation('name', $keyLang))}}"/>
                                     <span class="text-danger">{{ $errors->first('name.'.$keyLang) }}</span>
                                </div>
                            </div>
                            
                             <div class="col-md-6">
                                <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.bank_account_number')}}</label>
                                   <div class="mb-5 {{ $errors->has('account_number') ? 'has-error' : '' }}">
                                       <input type="text" name="account_number" class="form-control form-control-solid" value="{{old('account_number',$data->account_number)}}" placeholder="{{__('admin.bank_account_number')}}"/>
                                       <span class="text-danger">{{ $errors->first('account_number') }}</span>
                                  </div>
                             </div>
                             <div class="col-md-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.iban')}}</label>
                                   <div class="mb-5 {{ $errors->has('iban') ? 'has-error' : '' }}">
                                       <input type="text" name="iban" class="form-control form-control-solid" value="{{old('iban',$data->iban)}}" placeholder="{{__('admin.iban')}}"/>
                                       <span class="text-danger">{{ $errors->first('iban') }}</span>
                                  </div>
                              </div>
 
                              <div class="col-md-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.status')}}</label>
                                      <div class="mb-5">
                                         <select name="status" class="form-select">
                                             <option value="1" @if($data->status == 1 ) selected @endif>{{__('admin.active')}}</option>
                                             <option value="0" @if($data->status == 0 ) selected @endif>{{__('admin.inactive')}}</option>
                                         </select>
                                     </div>
                                </div>
                           </div>
                           <div class="row">
                               <div class="col-md-6">
                                 <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.bank_logo')}}</label>
                                  <div class="mb-5">
                                       @include('dashboard.components.image_upload_input', ['input_name' => 'logo', 'current_image' => $data->logo])
                                  </div>
                             </div>
                             <div class="col-md-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.hint_image')}}</label>
                                 <div class="mb-5">
                                      @include('dashboard.components.image_upload_input', ['input_name' => 'hint_image', 'current_image' => $data->hint_image])
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
