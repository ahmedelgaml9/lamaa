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
       
                {!! Form::open(['url' => route('websiteservice.store'), 'method' => 'post', 'files'=>true]) !!}
        
                  <div class="card-body py-3">
                       <div class="row">
                           <div class="col-md-6">
                              <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.title')}}</label>
                                 <div class="mb-5 {{ $errors->has('title') ? 'has-error' : '' }}">
                                     <input type="text" name="title" class="form-control form-control-solid" placeholder="{{__('admin.title')}}"/>
                                     <span class="text-danger">{{ $errors->first('title') }}</span>
                                </div>
                          </div>

                           <div class="col-md-6">
                               <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.description')}}</label>
                                  <div class="mb-5 {{ $errors->has('description') ? 'has-error' : '' }}">
                                     <textarea name="description" class="form-control form-control-solid" placeholder="{{__('admin.description')}}" rows="6"></textarea>
                                     <span class="text-danger">{{ $errors->first('description') }}</span>
                                </div>
                           </div>

                           <div class="col-md-6">
                               <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.thumbnail')}}</label>
                                   <div class="mb-5">
                                        @include('dashboard.components.image_upload_input', ['input_name' => 'img', 'current_image' => $data->img])
                                  </div>
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

@section('script')
<script type="text/javascript">

    $("#addRow").click(function () {

    var html = '';
    html += '<tr id="inputFormRow">';
    html +='<td><input type="text" class="form-control form-control-solid" name="addition[]"  />';
    html +='<td><input type="text" class="form-control form-control-solid" name="addition_price[]"  />';
    html +='</td>';
    html += '<td class="text-end">';
    html += '<button type="button" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px me-3" data-kt-action="field_remove" id ="removeRow" >';
    html += '<span class="svg-icon svg-icon-3">',
    html += '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">';
    html += '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">';
    html += '<rect x="0" y="0" width="24" height="24" />';
    html += '<path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero" />';
    html += '<path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />';
    html += '</g></svg></span></button>';
    html += '</td></tr>';

      $('#newRow').append(html);

            $(document).on('click', '#removeRow', function (){
            $(this).closest('#inputFormRow').remove();
       });
    });

</script>
@endsection




