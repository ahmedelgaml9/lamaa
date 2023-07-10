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
                      {!! Form::open(['url' => route('users.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
                   @else
                       {!! Form::open(['url' => route('users.store'), 'method' => 'post', 'files'=>true]) !!}
                   @endif
                   <div class="card-body py-3">
                        <div class="tab-content">
                             @foreach(supportedLanguages() as $keyLang => $valueLang)
                            <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                                  <div class="row">
                                     <div class="col-md-6">
                                          <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.name')}}</label>
                                             <div class="mb-5 {{ $errors->has('name') ? 'has-error' : '' }}">
                                                  <input type="text" name="name" class="form-control form-control-solid" value="{{old('name',$data->name)}}" placeholder="{{__('admin.name')}}"/>
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
                                         @if($data->user_type != "admin")
                                        <div class="col-md-6">
                                             <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.role')}}</label>
                                               <div class="mb-5 {{ $errors->has('name') ? 'has-error' : '' }}">
                                                    <select name="role_id" required class="form-select">
                                                      @foreach($roles as $role)
                                                        <option value="{{$role->id}}" @if($role->id == $staff->role_id) selected @endif>{{$role->name }}</option>
                                                      @endforeach
                                                   </select>
                                            </div>
                                       </div>
                                         @else
                                        <div class="col-md-6" style="display:none;">
                                              <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.role')}}</label>
                                              <div class="mb-5 {{ $errors->has('name') ? 'has-error' : '' }}">
                                                   <select name="role_id" required class="form-select">
                                                       <option value="12">مسئول التطبيق / الادارة</option>
                                                   </select>
                                              </div>
                                        </div>
                                          @endif
                                        <div class="col-md-6">
                                             <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.password')}}</label>
                                              <div class="mb-5 {{ $errors->has('password') ? 'has-error' : '' }}">
                                                   <input type="password" name="password" class="form-control form-control-solid"  placeholder="{{__('admin.password')}}"/>
                                                   <span class="text-danger">{{ $errors->first('password') }}</span>
                                             </div>
                                        </div>
 
                                        <div class="col-md-6">
                                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.password_confirmation')}}</label>
                                              <div class="mb-5">
                                                  <input type="password" name="password_confirmation" class="form-control form-control-solid"  placeholder="{{__('admin.password_confirmation')}}"/>
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

