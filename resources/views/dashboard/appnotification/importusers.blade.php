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
     
                  {!! Form::open(['url' => route('notification-messages.store'), 'method' => 'post', 'files'=>true]) !!}

               <div class="card-body py-3">
                    <div class="tab-content">
                        @foreach(supportedLanguages() as $keyLang => $valueLang)
                      <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                          <div class="row">
                              <div class="col-md-6">
                                  <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.title')}}</label>
                                     <div class="mb-5 {{ $errors->has('title.'.$keyLang) ? 'has-error' : '' }}">
                                         <input type="text" name="title[{{$keyLang}}]" class="form-control form-control-solid" placeholder="{{__('admin.title')}}" value="{{old('title.'.$keyLang, $data->getTranslation('title', $keyLang))}}"/>
                                         <span class="text-danger">{{ $errors->first('title.'.$keyLang) }}</span>
                                    </div>
                                </div>

                                 <div class="col-md-12">
                                     <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.content')}}</label>
                                      <div class="mb-5 {{ $errors->has('content') ? 'has-error' : '' }}">
                                          <textarea  name="content" class="form-control form-control-solid"  rows="6"  placeholder="{{__('admin.content')}}"></textarea>
                                          <span class="text-danger">{{ $errors->first('content') }}</span>
                                     </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex flex-column mb-8 fv-row" >
                                        <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.users')}}</label>
                                             <select class="form-control" data-control="select2" id="user_selection" name="users[]" multiple="">
                                                @foreach($insert[0]  as $user)
                                                 <option value="{{$user[0]}}">{{ $user[1]}}</option>
                                               @endforeach
                                            </select> 
                                       </div>
                                 </div>

                                <div class="row">
                                   <div class="col-md-6">
                                       <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.link')}}</label>
                                        <div class="mb-5">
                                            <input type="text"  name="link" class="form-control form-control-solid" placeholder="{{__('admin.link')}}" value="{{old('link', $data->link)}}"/>
                                        </div>
                                   </div>

                                   <div class="col-md-6" id="products">
                                       <div class="d-flex flex-column mb-8 fv-row">
                                            <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.cities')}}</label>
                                              <select class="form-select"  data-control="select2"  name="cities[]">
                                                @foreach($cities as $city)
                                                 <option value="{{$city->id}}">{{ $city->getTranslation('name', $keyLang)}}</option>
                                                @endforeach
                                            </select> 
                                         </div>
                                   </div>

                                  <div class="col-md-6">
                                        <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.image')}}</label>
                                        <div class="mb-5">
                                            @include('dashboard.components.image_upload_input', ['input_name' => 'image', 'current_image' => $data->image])
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
