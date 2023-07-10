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
            {!! Form::open(['url' => route('template.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
        @else
            {!! Form::open(['url' => route('template.store'), 'method' => 'post', 'files'=>true]) !!}
        @endif
      
         <div class="card-body py-3">
               <div class="tab-content">
                     @foreach(supportedLanguages() as $keyLang => $valueLang)
                    <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                        <div class="row">
                           <div class="col-md-12">
                              <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.notification_title')}}</label>
                                 <div class="mb-5 {{ $errors->has('title.'.$keyLang) ? 'has-error' : '' }}">
                                    <input type="text" name="title[{{$keyLang}}]" class="form-control form-control-solid" placeholder="{{__('admin.notification_title')}}" value="{{old('title.'.$keyLang, $data->getTranslation('title', $keyLang))}}"/>
                                    <span class="text-danger">{{ $errors->first('title.'.$keyLang) }}</span>
                                </div>
                             </div>
                             <div class="col-md-12">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.notification_content')}}</label>
                                   <div class="mb-5 {{ $errors->has('content.'.$keyLang) ? 'has-error' : '' }}">
                                         <textarea name="content[{{$keyLang}}]" class="form-control form-control-solid"  placeholder="{{__('admin.notification.content')}}"   id="kt_docs_ckeditor_classic">{{old('content.'.$keyLang, $data->getTranslation('content', $keyLang))}}</textarea>
                                        <span class="text-danger">{{ $errors->first('content.'.$keyLang) }}</span>
                                   </div>
                              </div>
                               <div class="col-md-12">
                                   <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.content_smss')}}</label>
                                       <div class="mb-5 {{ $errors->has('content_sms.'.$keyLang) ? 'has-error' : '' }}">
                                        <textarea name="content_sms[{{$keyLang}}]" class="form-control form-control-solid" placeholder="{{__('admin.content_smss')}}">{{old('content_sms.'.$keyLang, $data->getTranslation('content_sms', $keyLang))}}</textarea>
                                        <span class="text-danger">{{ $errors->first('content_sms.'.$keyLang) }}</span>
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

<script src="{{asset('dashboard/dist/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/plugins/custom/ckeditor/ckeditor-inline.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/plugins/custom/ckeditor/ckeditor-balloon.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/plugins/custom/ckeditor/ckeditor-balloon-block.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/plugins/custom/ckeditor/ckeditor-document.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script>
        $(function (){
            $('#db_table').dataTable({
                "ordering": false
            });
        })



        ClassicEditor
    .create(document.querySelector('#kt_docs_ckeditor_classic'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
    </script>

@endsection

