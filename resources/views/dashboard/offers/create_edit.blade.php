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
            {!! Form::open(['url' => route('offers.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
        @else
            {!! Form::open(['url' => route('offers.store'), 'method' => 'post', 'files'=>true]) !!}
        @endif

          <div class="card-body py-3">
              <div class="tab-content">
                    @foreach(supportedLanguages() as $keyLang => $valueLang)
                    <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                        <div class="row">
                            <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.title')}}</label>
                                     <div class="mb-5 {{ $errors->has('name') ? 'has-error' : '' }}">
                                      <input type="text"  name="name" class="form-control form-control-solid" placeholder="{{__('admin.title')}}" value="{{old('name', $data->name )}}"/>
                                      <span class="text-danger">{{ $errors->first('name') }}</span>
                                 </div>
                             </div>

                             <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.description')}}</label>
                                     <div class="mb-5 {{ $errors->has('name') ? 'has-error' : '' }}">
                                      <input type="text"  name="description" class="form-control form-control-solid" placeholder="{{__('admin.description')}}" value="{{old('description', $data->description )}}"/>
                                      <span class="text-danger">{{ $errors->first('description') }}</span>
                                 </div>
                             </div>

                               <div class="col-md-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.type')}}</label>
                                     <div class="mb-5">
                                       <select name="type" id="type" class="form-select">
                                       <option value="">اختر النوع</option>
                                        <option value="product" @if($data->type == "product" ) selected @endif>{{__('admin.offer_product')}}</option>
                                        <option value="price"  @if($data->type == "price" ) selected @endif>{{__('admin.offer_price')}}</option>

                                    </select>
                                    <span class="text-danger">{{ $errors->first('type') }}</span>

                                </div>
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-md-4" id="percent" style="display:none;">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.price')}}</label>
                                    <div class="mb-5">
                                        <input type="number" name="percent" class="form-control form-control-solid"  value="{{old('percent',$data->percent)}}" placeholder="{{__('admin.percent')}}"/>
                                    </div>
                                </div>

                                 <div class="col-md-4" id="take" style="display:none;">
                                     <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.take')}}</label>
                                       <div class="mb-5">
                                         <input type="number" name="take" class="form-control form-control-solid"   value="{{old('take',$data->take)}}" placeholder="{{__('admin.take')}}"/>
                                     </div>
                                 </div>

                              <div class="col-md-4"  id="get" style="display:none;">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.get')}}</label>
                                <div class="mb-5">
                                    <input type="number" name="get" class="form-control form-control-solid"  value="{{old('take',$data->take)}}" placeholder="{{__('admin.get')}}"/>
                                </div>
                             </div>

                                <div class="col-md-4" id="other_product_id"  style="display:none;">
                                    <div class="d-flex flex-column mb-8 fv-row" >
                                        <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('trans_admin.other_offer_product_id')}}</label>
                                        <select class="form-control" data-control="select2" name="other_product_id">
                                            <option value="">{{__('trans_admin.choose')}} {{__('trans_admin.other_offer_product_id')}}</option>
                                            @foreach($products  as  $product)
                                                <option value="{{$product->id}}" @if($product->id == $data->other_product_id) selected @endif>{{$product->title}}</option>
                                            @endforeach
                                        </select>
                                        <small>{{__('trans_admin.other_offer_product_id_hint')}}</small>
                                    </div>
                                </div>

                             {{--<div class="col-md-4"  id="price" style="display:none;">
                                  <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.price_group')}}</label>
                                  <div class="mb-5">
                                     <input type="number" name="group_price" class="form-control form-control-solid"  value="{{old('price',$data->price)}}" placeholder="{{__('admin.price')}}"/>
                                 </div>
                              </div>--}}
                          </div>

                        <div class="row">
                           <div class="col-md-6" id="products" >
                              <div class="d-flex flex-column mb-8 fv-row" >
                                 <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.products')}}</label>
                                   <select class="form-control" data-control="select2" name="products[]" multiple="">
                                    @foreach($products  as  $product)
                                    <option value="{{$product->id}}" @if(in_array($product->id, $selected_products_ids)) selected @endif>{{$product->title}}</option>
                                    @endforeach
                                  </select>
                                </div>
                            </div>

                          <div class="col-md-6" id="cities" >
                             <div class="d-flex flex-column mb-8 fv-row">
                                <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.cities')}}</label>
                                 <select class="form-select"  data-control="select2" name="cities[]" multiple="">
                                     @foreach($cities as $city)
                                       <option value="{{$city->id}}" @if(in_array($city->id, $selected_cities_ids)) selected @endif>{{ $city->getTranslation('name', 'ar')}}</option>
                                     @endforeach
                                   </select>
                                </div>
                              </div>
                            </div>

                              <div class="row">
                                 <div class="col-md-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.status')}}</label>
                                     <div class="mb-5">
                                       <select name="status" class="form-select">
                                        <option value="1" @if($data->status == 1 ) selected @endif>{{__('admin.active')}}</option>
                                        <option value="0" @if($data->status == 0 ) selected @endif>{{__('admin.inactive')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                 <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.start_at')}}</label>
                                  <div class="mb-5 {{ $errors->has('start_date') ? 'has-error' : '' }}">
                                      <input class="form-control form-control-solid" name="start_date" value="{{old('start_date',$data->start_date)}}" placeholder="{{__('admin.start_at')}}" id="kt_datepicker_1"/>
                                     <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                  </div>
                             </div>

                                <div class="col-md-6">
                                   <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.expiry_date')}}</label>
                                     <div class="mb-5 {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                                      <input class="form-control form-control-solid" name="expiry_date" value="{{old('expiry_date',$data->expiry_date)}}" placeholder="{{__('admin.expiry_date')}}" id="kt_datepicker_2"/>
                                      <span class="text-danger">{{ $errors->first('expiry_date') }}</span>
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

<script>

if($("#type option:selected").val() == "product")
{

$("#take").show();
$("#get").show();
$("#price").show();
$("#percent").hide();

}

if($("#type option:selected").val() == "price" )
{

$("#take").hide();
$("#get").hide();
$("#price").hide();
$("#percent").show();

}


$("#type").change(function(){

if(this.value == "product")
{

$("#take").show();
$("#get").show();
$('#other_product_id').show();
$("#price").show();

$("#percent").hide();


}
if(this.value == "price")
{

$("#take").hide();
$("#get").hide();
$('#other_product_id').hide();
$("#price").hide();
$("#percent").show();

}

});

$("#kt_datepicker_1").flatpickr();
$("#kt_datepicker_2").flatpickr();

</script>

@endsection
