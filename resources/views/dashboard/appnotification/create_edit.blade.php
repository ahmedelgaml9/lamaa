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
                  {!! Form::open(['url' => route('notification-messages.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
              @else
                  {!! Form::open(['url' => route('notification-messages.store'), 'method' => 'post', 'files'=>true]) !!}
              @endif
              <div class="card-body py-3">
                      <div class="tab-content">
                           <div class="row">
                                <div class="col-md-6">
                                      <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.title')}}</label>
                                         <div class="mb-5 {{ $errors->has('title') ? 'has-error' : '' }}">
                                             <input type="text" name="title" class="form-control form-control-solid" placeholder="{{__('admin.title')}}" value="{{old('title', $data->title)}}"/>
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
                                        <div class="d-flex flex-column mb-8 fv-row">
                                             <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.type')}}</label>
                                                  <select class="form-control" id="type" name="group">
                                                     <option value="">{{__('admin.select')}}</option>
                                                     <option value="auth_users">{{__('admin.register_users')}}</option>
                                                     <option value="all">{{__('admin.allusers')}}</option>
                                                     <option value="select_user">{{__('admin.select_user')}}</option>
                                                </select>
                                         </div>
                                   </div>

                                   <div class="col-md-6" id="users" style="display:none;">
                                        <div class="d-flex flex-column mb-8 fv-row" >
                                             <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.users')}}</label>
                                                <select class="form-control" data-control="select2" id="user_selection" name="users[]" multiple="">
                                                    <option value="">اختر عميل</option>
                                               </select>
                                         </div>
                                    </div>
  
                                    <div class="row">
                                         <div class="col-md-6">
                                             <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.link')}}</label>
                                              <div class="mb-5">
                                                  <input type="text"  name="link" class="form-control form-control-solid" placeholder="{{__('admin.link')}}" value="{{old('link', $data->link)}}"/>
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
                            </div>

                              <div class="card-footer d-flex justify-content-end py-6 px-9">
                                   <button type="reset" class="btn btn-light btn-active-light-primary me-2"  onClick="window.location.href=window.location.href">{{__('admin.discard')}}</button>
                                   <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">{{__('admin.save')}}</button>
                             </div>
                              {!! Form::close() !!}
                         </div>
@endsection
@section('script')
<script>

$(document).ready(function(){
    $(".kt-datetime-picker").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });
$("#user_selection" ).select2({
  ajax: {
    url: "{{route('getusers')}}",
    type: "post",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
       _token: '{!! csrf_token() !!}',
        search: params.term
      };
    },
    processResults: function (response) {
      return {
        results: response
      };
    },
    cache: true
  }

});

});

$("#type").change(function(){

if(this.value == "select_user")

{

$("#users").show();
$("#cities").hide();

}

else{

$("#users").hide();

}
});

/*$(document).ready(function() {

    $('#type').change(function(){

        $.ajax({
           type:"get",
           url:"{{url('/admin/getusers')}}",
           success:function(res)
           {
                if(res)
                {
                    $.each(res,function(key,value){
                        $("#user_selection").append('<option value="'+key+'">'+value+'</option>');
                    });
                }
           }

        });

    });

});

*/

</script>
@endsection
