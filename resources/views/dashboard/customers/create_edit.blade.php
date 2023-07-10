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
                   {!! Form::open(['url' => route('customers.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
                 @else
                    {!! Form::open(['url' => route('customers.store'), 'method' => 'post', 'files'=>true]) !!}
                 @endif
               <div class="card-body py-3">
                   <div class="tab-content">
                         @foreach(supportedLanguages() as $keyLang => $valueLang)
                      <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                          <div class="row">
                             <div class="col-md-6">
                                 <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.user_name')}}</label>
                                 <div class="mb-5 {{ $errors->has('name') ? 'has-error' : '' }}">
                                     <input type="text" name="name" class="form-control form-control-solid" value="{{old('name',$data->name)}}" placeholder="{{__('admin.user_name')}}"/>
                                     <span class="text-danger">{{ $errors->first('name') }}</span>
                                 </div>
                             </div>

                              <div class="col-md-6">
                                 <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.email')}}</label>
                                 <div class="mb-5 {{ $errors->has('email') ? 'has-error' : '' }}">
                                      <input type="email" name="email" class="form-control form-control-solid" value="{{old('email',$data->email)}}" placeholder="{{__('admin.email')}}"/>
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
                                 </div>
                             </div>

                             <div class="col-md-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.city')}}</label>
                                     <div class="mb-5">
                                        <select class="form-select"  data-control="select2"  name="city_id" required>
                                            <option value="">اختر مدينة</option>
                                             @foreach($cities as $city)
                                              <option value="{{$city->id}}"@if ($city->id == $data->city_id) selected  @endif>{{ $city->getTranslation('name', $keyLang)}}</option>
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

