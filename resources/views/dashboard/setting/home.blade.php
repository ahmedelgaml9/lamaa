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
                <span class="card-label fw-bolder fs-3 mb-1"> {{$page_title}}</span>
            </h3>
            
        </div>

            {!! Form::open(['url' => route('setting.update'), 'method' => 'post', 'files'=>true]) !!}
    
           <div class="card-body py-3">
               <div class="tab-content">
                    @foreach(supportedLanguages() as $keyLang => $valueLang)
                     <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                         <div class="row">
                             <div class="col-md-6">
                                 <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.copy_rights')}}</label>
                                   <div class="mb-5">
                                      <input type="hidden" name="types[]" value="copy_rights">
                                      <input type="text" class="form-control" placeholder="" name="copy_rights" value="{{ get_setting('copy_rights')}}">
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
