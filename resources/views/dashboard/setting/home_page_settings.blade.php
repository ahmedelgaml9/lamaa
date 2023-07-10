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
                             <span class="card-label fw-bolder fs-3 mb-1">{{__('admin.home_page')}}</span>
                         </h3>
                     </div>
                  
                          {!! Form::open(['url' => route('settings.update'), 'method' =>'post', 'files'=>true]) !!}
                       
                         <div class="card-body py-3">
                              <div class="row">
                                <div class="col-md-6">
                                  <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.aboutus_title')}}</label>
                                   <div class="mb-5 {{ $errors->has('iban') ? 'has-error' : '' }}">
    							    	<textarea  rows="5" class="form-control" placeholder="{{__('admin.abouts_title') }}" name="aboutus_title">{{ $gs->aboutus_title }}</textarea>
                                  </div>
                              </div>

                              <div class="col-md-6">
                                 <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.aboutus_desc')}}</label>
                                   <div class="mb-5 {{ $errors->has('iban') ? 'has-error' : '' }}">
                                      <textarea rows="5" class="form-control" placeholder="{{__('admin.aboutus_desc') }}" name="aboutus_description" >{{ $gs->aboutus_description }}</textarea>
                                  </div>
                              </div>

                             <div class="col-md-6">
                                 <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.vision_title')}}</label>
                                   <div class="mb-5 {{ $errors->has('iban') ? 'has-error' : '' }}">
    							      <textarea  rows="5" class="form-control" placeholder="{{__('admin.vision_title') }}" name="vision_title">{{ $gs->vision_title }}</textarea>
                                  </div>
                              </div>

                                <div class="col-md-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.vision_desc')}}</label>
                                       <div class="mb-5 {{ $errors->has('iban') ? 'has-error' : '' }}">
                                          <textarea  rows="5" class="form-control" placeholder="{{__('admin.vision_desc') }}" name="vision_description">{{ $gs->vision_description }}</textarea>
                                      </div>
                                 </div>
                             </div>
                         </div>
                     </div>

                        <div class="card card-xl-stretch mb-5 mb-xl-10">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                   <span class="card-label fw-bolder fs-3 mb-1">{{__('admin.features')}}</span>
                               </h3>
                              <div class="card-toolbar">
                        </div>
                    </div>

                   <div class="card-body py-3">
                      <div class="row">
                         <div class="col-md-6">
                            <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.management')}}</label>
                             <div class="mb-5 ">
                               <textarea  rows="5" class="form-control" placeholder="{{ __('admin.management') }}" name="management" >{{ $gs->management }}</textarea>
                           </div>
                       </div>

                      <div class="col-md-6">
                          <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.goal')}}</label>
                            <div class="mb-5">
                               <textarea   rows="5" class="form-control" placeholder="{{__('admin.goal') }}" name="goal">{{ $gs->goal }}</textarea>
                           </div>
                       </div>
                   </div>

                   <div class="row">
                      <div class="col-md-6">
                          <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.development')}}</label>
                          <div class="mb-5">
                            <textarea  rows="5" class="form-control" placeholder="{{ __('admin.development') }}" name="develop">{{ $gs->develop }}</textarea>
                        </div>
                   </div>

                     <div class="col-md-6">
                         <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.evaluation')}}</label>
                           <div class="mb-5">
                               <textarea  rows="5"  class="form-control" placeholder="{{ __('admin.evaluation') }}" name="evaluation">{{ $gs->evaluation }}</textarea>
                           </div>
                      </div>
                  </div>                        
              </div>
         </div>

         <div class="card">
            <div class="card-header border-0 pt-5">
                 <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">{{__('admin.contactus')}}</span>
                </h3>
                 <div class="card-toolbar">
                 </div>
            </div>
        
              <div class="card-body py-3">
                 <div class="row">                                
                     <div class="col-md-6">
                         <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.phone')}}</label>
                             <div class="mb-5">
                                 <input type="text" class="form-control" placeholder="{{('admin.phone') }}" name="phone" value="{{ $gs->phone }}">
                             </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.whatsapp')}}</label>
                              <div class="mb-5">
                                  <input type="text" class="form-control" placeholder="{{('admin.whatsapp') }}" name="whatsapp" value="{{ $gs->whatsapp }}">
                              </div>
                         </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.address')}}</label>
                              <div class="mb-5">
                                  <input type="text" class="form-control" placeholder="{{__('admin.address') }}" name="address" value="{{ $gs->address }}">
                              </div>
                         </div>

                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.email')}}</label>
                              <div class="mb-5">
                                  <input type="text" class="form-control" placeholder="{{__('admin.email') }}" name="email" value="{{ $gs->email }}">
                              </div>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-4">
                             <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.twitter_link')}}</label>
                                <div class="mb-5 {{ $errors->has('account_number') ? 'has-error' : '' }}">
                                    <input type="text" class="form-control" placeholder="http://" name="twitter" value="{{ $gs->twitter}}">
                              </div>
                         </div> 

                         <div class="col-md-4">
                             <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.instagram_link')}}</label>
                                <div class="mb-5">
                                    <input type="text" class="form-control" placeholder="http://" name="instagram" value="{{$gs->instagram}}">
                                </div>
                            </div>
                        </div>

                         <div class="row">
                             <div class="col-md-4">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.tiktok')}}</label>
                                   <div class="mb-5">
                                        <input type="text" class="form-control" placeholder="http://" name="tiktok" value="{{ $gs->tiktok}}">
                                   </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  
                  <div class="card-footer d-flex justify-content-end py-6 px-9">
                      <button type="reset" class="btn btn-light btn-active-light-primary me-2"  onClick="window.location.href=window.location.href">{{__('admin.discard')}}</button>
                      <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">{{__('admin.save')}}</button>
                 </div>
                   {!! Form::close() !!}

@endsection
