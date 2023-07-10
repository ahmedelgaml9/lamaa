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
                    {!! Form::open(['url' => route('slider.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
                @endif
               <div class="card-body py-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.thumbnail')}}</label>
                               <div class="mb-5">
                                    @include('dashboard.components.image_upload_input', ['input_name' =>'image','current_image' => $data->image])
                               </div>
                          </div>
 
                           <div class="col-md-6">
                               <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.status')}}</label>
                                   <div class="mb-5 form-check form-switch form-check-custom form-check-solid {{ $errors->has('available') ? 'has-error' : '' }}">
                                       <input class="form-check-input" type="checkbox" value="1" id="available" name="status" @if($data->status > 0) checked="checked" @endif />
                                       <label class="form-check-label fw-bold text-gray-400 ms-3" for="status">{{__('admin.active')}}</label>
                                       <span class="text-danger">{{ $errors->first('status') }}</span>
                                   </div>
                              </div>
                          </div>

                         <div class="card-footer d-flex justify-content-end py-6 px-9">
                             <button type="reset" class="btn btn-light btn-active-light-primary me-2"  onClick="window.location.href=window.location.href">{{__('admin.discard')}}</button>
                             <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">{{__('admin.save')}}</button>
                        </div>
                              {!! Form::close() !!}
                        </div>

 @endsection
 