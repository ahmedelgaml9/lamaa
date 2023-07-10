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
                {!! Form::open(['url' => route('cities.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
            @else
                {!! Form::open(['url' => route('cities.store'), 'method' => 'post', 'files'=>true]) !!}
            @endif
        
             <div class="card-body py-3">
                 <div class="tab-content">
                        @foreach(supportedLanguages() as $keyLang => $valueLang)
                      <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                           <div class="row">
                               <div class="col-md-6">
                                   <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.title')}}</label>
                                      <div class="mb-5 {{ $errors->has('name.'.$keyLang) ? 'has-error' : '' }}">
                                           <input type="text" name="name[{{$keyLang}}]" class="form-control form-control-solid" placeholder="{{__('admin.title')}}" value="{{old('name.'.$keyLang, $data->getTranslation('name', $keyLang))}}"/>
                                           <span class="text-danger">{{ $errors->first('name.'.$keyLang) }}</span>
                                     </div>
                                </div>
 
                                <div class="col-md-6">
                                    <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.code')}}</label>
                                     <div class="mb-5">
                                         <input type="text" name="code" class="form-control form-control-solid" value="{{old('code',$data->code)}}" placeholder="{{__('admin.code')}}"/>
                                    </div>
                                </div>

                                 <div class="col-md-6">
                                     <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.region')}}</label>
                                     <div class="mb-5">
                                         <select class="select2 form-control"  name="region_id" data-toggle="select2">
                                             @foreach ($regions as $region)
                                             <option  value="{{ $region->id }}" @if ($region->id == $data->region_id) selected  @endif>{{ $region->name }}</option>
                                            @endforeach
                                         </select>
                                    </div>
                                </div>
                                    @include('dashboard.components.geometry_input', ['item' => $data])
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
