@extends('dashboard.layouts.master')
@section('toolbar')
@endsection
@section('content')
          <div class="card card-xl-stretch mb-5 mb-xl-10">
               <div class="card-header border-0 pt-5">
                   <h3 class="card-title align-items-start flex-column">
                      <span class="card-label fw-bolder fs-3 mb-1">{{ __('admin.add_new')}}</span>
                  </h3>
                  <div class="card-toolbar">

                   </div>
                </div>
                       {!! Form::open(['url' => route('orders.store'), 'method' => 'post', 'files'=>true]) !!}
                    
                     <div class="card-body py-3">
                           <div class="tab-content">
                               <div class="row">
                                   <div class="col-md-6">
                                        <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.is_new_customer')}}</label>
                                          <div class="mb-5">
                                               <select class="select2 form-control"  name="is_new_customer" data-toggle="select2" id="is-new_customer-select">
                                                  @foreach (__('admin.int_yes_no_options') as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                  @endforeach
                                               </select>
                                          </div>
                                     </div>

                                     <div class="col-md-6">
                                         <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.phone')}}</label>
                                         <div class="mb-5">
                                             <input type="text" name="mobile" class="form-control form-control-solid" value="{{old('mobile')}}" placeholder="{{__('admin.phone')}}"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6 show-new-customer-inputs" @if(old('is_new_customer') == 0) style="display: none;" @endif>
                                       <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.name')}}</label>
                                           <div class="mb-5">
                                              <input type="text" name="name" class="form-control form-control-solid" value="{{old('name')}}" placeholder="{{__('admin.name')}}"/>
                                          </div>
                                     </div>
   
                                    <div class="col-md-6 show-new-customer-inputs" @if(old('is_new_customer') == 0) style="display: none;" @endif>
                                        <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.email')}}</label>
                                           <div class="mb-5">
                                               <input type="text" name="email" class="form-control form-control-solid" value="{{old('email', 'random_'.hexdec(uniqid()).'@mawaredal-hayat.com')}}" placeholder="{{__('admin.email')}}"/>
                                           </div>
                                      </div>
                                 </div>
                           </div>
                      </div>
    
                      <div class="card-footer d-flex justify-content-end py-6 px-9">
                          <button type="submit" class="btn btn-primary">{{__('admin.create')}}</button>
                      </div>
                         {!! Form::close() !!}
                  </div>
@endsection
 @section('script')
<script>
    $(function (){
        $('#is-new_customer-select').on('change', function (e){
           if(Number($(this).val()) === 1){
             $('.show-new-customer-inputs').show();
           }else{
             $('.show-new-customer-inputs').hide();
          }
      })
  })

</script>
@endsection
