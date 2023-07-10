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
        
              {!! Form::open(['url' => route('setting.update'), 'method' =>'post', 'files'=>true]) !!}
    
            <div class="card-body py-3">
               <div class="tab-content">
                       @foreach(supportedLanguages() as $keyLang => $valueLang)
                    <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                        <div class="row">
                             <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.site_title')}}</label>
                                     <div class="mb-5 {{ $errors->has('name.'.$keyLang) ? 'has-error' : '' }}">
                                         <input type="hidden" name="types[][{{ $valueLang }}]" value="sitetitle">
    								     <input type="text" class="form-control" placeholder="{{ __('admin.site_title') }}" name="sitetitle" value="{{ get_setting('sitetitle') }}">
                                    </div>
                               </div>
                          </div>

                           <div class="row">
                                 <div class="col-md-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.email')}}</label>
                                     <div class="mb-5 {{ $errors->has('iban') ? 'has-error' : '' }}">
                                         <input type="hidden" name="types[]" value="email">
    							    	 <input type="text" class="form-control" placeholder="{{__('admin.email') }}" name="email" value="{{ get_setting('email') }}">
                                    </div>
                               </div>

                              <div class="col-md-6">
                                  <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.phone')}}</label>
                                       <div class="mb-5 {{ $errors->has('iban') ? 'has-error' : '' }}">
                                           <input type="hidden" name="types[]" value="contact_phone">
    							    	   <input type="text" class="form-control" placeholder="{{('admin.phone') }}" name="contact_phone" value="{{ get_setting('contact_phone') }}">
                                      </div>
                                 </div>
                           </div>

                            <div class="row">
                                <div class="col-md-4">
                                   <label   class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.facebook')}}</label>
                                     <div class="mb-5 {{ $errors->has('name.'.$keyLang) ? 'has-error' : '' }}">
                                          <input type="hidden" name="types[]" value="facebook_link">
                                          <input type="text" class="form-control" placeholder="http://" name="facebook_link" value="{{ get_setting('facebook_link')}}">
                                   </div>
                               </div>

                             <div class="col-md-4">
                                <label
                                     class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.twitter_link')}}</label>
                                      <div class="mb-5 {{ $errors->has('account_number') ? 'has-error' : '' }}">
                                        <input type="hidden" name="types[]" value="twitter_link">
                                       <input type="text" class="form-control" placeholder="http://" name="twitter_link" value="{{ get_setting('twitter_link')}}">
                                 </div>
                             </div> 

                             <div class="col-md-4">
                                  <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.instagram_link')}}</label>
                                       <div class="mb-5 {{ $errors->has('iban') ? 'has-error' : '' }}">
                                          <input type="hidden" name="types[]" value="instagram_link">
                                          <input type="text" class="form-control" placeholder="http://" name="instagram_link" value="{{ get_setting('instagram_link')}}">
                                    </div>
                               </div>
                          </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.googleplus')}}</label>
                                   <div class="mb-5 {{ $errors->has('name.'.$keyLang) ? 'has-error' : '' }}">
                                      <input type="hidden" name="types[]" value="google_plus_link">
                                      <input type="text" class="form-control" placeholder="http://" name="google_plus_link" value="{{ get_setting('google_plus_link')}}">
                                   </div>
                            </div>

                            <div class="col-md-4">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.whatsapp_link')}}</label>
                                   <div class="mb-5 {{ $errors->has('account_number') ? 'has-error' : '' }}">
                                        <input type="hidden" name="types[]" value="whatsapp_link">
                                        <input type="text" class="form-control" placeholder="http://" name="whatsapp_link" value="{{ get_setting('whatsapp_link')}}">
                                </div>
                           </div> 

                              <div class="col-md-4">
                                   <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.snapchat_link')}}</label>
                                     <div class="mb-5 {{ $errors->has('iban') ? 'has-error' : '' }}">
                                         <input type="hidden" name="types[]" value="snapchat_link">
                                         <input type="text" class="form-control" placeholder="http://" name="snapchat_link" value="{{ get_setting('snapchat_link')}}">
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
