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
                  {!! Form::open(['url' => route('services.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
              @endif
         <div class="card-body py-3">
             <div class="row">
                 <div class="col-md-6">
                     <label
                          class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.title')}}</label>
                          <div class="mb-5 {{ $errors->has('title') ? 'has-error' : '' }}">
                          <input type="text" name="title" class="form-control form-control-solid" placeholder="{{__('admin.title')}}" value="{{old('title', $data->title)}}"/>
                          <span class="text-danger">{{ $errors->first('title') }}</span>
                     </div>
                  </div>

                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-8 fv-row">
                             <label  class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.categories')}}</label>
                                 <select class="form-select"  data-control="select2" name="category_id">
                                     @foreach($categories as  $category)
                                      <option value="{{$category->id}}" @if($category->id == $data->category_id ) selected @endif>{{ $category->name }}</option>
                                     @endforeach
                                 </select>
                            </div>
                       </div>

                       <div class="col-md-6">
                           <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.price')}}</label>
                              <div class="mb-5 {{ $errors->has('price') ? 'has-error' : '' }}">
                                  <input type="number" step="0.01" name="price" class="form-control form-control-solid" value="{{old('price',$data->price)}}" placeholder="0.00" min="0"/>
                                  <span class="text-danger">{{ $errors->first('price') }}</span>
                             </div>
                        </div>

                        <div class="col-md-6">
                               <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.status')}}</label>
                                  <div class="mb-5 form-check form-switch form-check-custom form-check-solid {{ $errors->has('available') ? 'has-error' : '' }}">
                                        <input class="form-check-input" type="checkbox" value="1" id="available" name="available" @if($data->available > 0) checked="checked" @endif />
                                        <label class="form-check-label fw-bold text-gray-400 ms-3" for="status">{{__('admin.active')}}</label>
                                       <span class="text-danger">{{ $errors->first('available') }}</span>
                                 </div>
                             </div>
                       </div>

                      <div class="row">
                           <div class="col-md-6">
                               <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.description')}}</label>
                                <div class="mb-5 {{ $errors->has('description') ? 'has-error' : '' }}">
                                      <textarea name="description" class="form-control form-control-solid" placeholder="{{__('admin.description')}}" rows="6">{{old('description',$data->description)}}</textarea>
                                      <span class="text-danger">{{ $errors->first('description') }}</span>
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

                         <div class="col-md-6">
                             <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.thumbnail')}}</label>
                                 <div class="mb-5">
                                      @include('dashboard.components.image_upload_input', ['input_name' => 'img','current_image' =>asset('images/products/'.$data->img)])
                                 </div>
                         </div>
                         </div>
                          <br>
                          <h2>{{__('admin.carsizes')}}</h2>

                         <div class="table-responsive">
                             <table id="kt_create_new_custom_fields" class="table align-middle table-row-dashed fw-bold fs-6 gy-5">
                                 <thead>
                                     <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                     <th class="pt-0">{{__('admin.size')}}</th>
                                     <th class="pt-0">{{__('admin.price')}}</th>
                                     <th class="pt-0 text-end">{{__('admin.remove')}}</th>
                                 </tr>
                               </thead>
                                 <tbody id="new_size">
                                        @foreach($all_sizes as $z)
                                       <tr id="sizeform">
                                           <td>
                                              <select class="form-select"  data-control="select2"  name="size[]" required>
                                                  <option value="">اختر حجم</option>
                                                     @foreach($sizes as $size)
                                                       <option value="{{ $size->size }}" @if($size->size == $z->size)  selected @endif>{{ $size->size }}</option>
                                                     @endforeach
                                                 </select>
                                             </td>
                                            <td>
                                                <input class="form-control form-control-solid"  value="{{$z->size_price}}" name="size_price[]" />
                                            </td>
 
                                              <td class="text-end">
                                                    <button type="button" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px me-3" id ="removesize" data-kt-action="field_remove">
                                                       <span class="svg-icon svg-icon-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g  stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24" />
                                                                    <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero" />
                                                                    <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                                                 </g>
                                                            </svg>
                                                        </span>
                                                     </button>
                                                 </td>
                                              </tr>
                                              @endforeach
                                         </tbody>
                                      </table>
                                         <a id="addsize" class="btn btn-primary" >{{__('admin.create')}}</a>
                                    </div>
                                   <br>
                                   <h2>{{__('admin.additions')}}</h2>

                                 <div class="table-responsive">
                                     <table id="kt_create_new_custom_fields" class="table align-middle table-row-dashed fw-bold fs-6 gy-5">
                                         <thead>
                                             <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                 <th class="pt-0">{{__('admin.addition')}}</th>
                                                 <th class="pt-0">{{__('admin.price')}}</th>
                                                 <th class="pt-0 text-end">{{__('admin.remove')}}</th>
                                             </tr>
                                        </thead>
                                   <tbody id="newRow">
                                       @foreach($additions as $addition)
                                    <tr id="inputFormRow">
                                     <td>
                                         <input class="form-control form-control-solid" name="addition[]" value="{{$addition->addition}}" />
                                     </td>
                                      <td>
                                         <input class="form-control form-control-solid" name="addition_price[]" value="{{$addition->addition_price}}"/>
                                     </td>

                                      <td class="text-end">
                                            <button type="button" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px me-3" id ="removeRow" data-kt-action="field_remove">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                             <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                  <rect x="0" y="0" width="24" height="24" />
                                                                  <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero" />
                                                                  <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                                             </g>
                                                       </svg>
                                                  </span>
                                             </button>
                                         </td>
                                     </tr>
                                 @endforeach
                             </tbody>
                           </table>
                             <a id="addRow" class="btn btn-primary" >{{__('admin.create')}}</a>
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

       });


    $(document).on('click', '#removeRow', function (){

       $(this).closest('#inputFormRow').remove();

    });

     $("#addsize").click(function () {

           var array = {!! $sizes !!}
           var options;
           $.each(array, function(key, value ) {
           options = options + '<option value="'+value.size+'">'+value.size +'</option>';

         });

        var html = '';
        html += '<tr id="sizeform">';
        html +='<td><select name="size[]"  data-control="select2"  class="form-select" required><option value="">اختر حجم</option>';
        html +=  options;
        html += '</select></td>';
        html +='<td><input type="text" class="form-control form-control-solid" name="size_price[]"  />';
        html +='</td>';
        html += '<td class="text-end">';
        html += '<button type="button" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px me-3" data-kt-action="field_remove" id ="removesize" >';
        html += '<span class="svg-icon svg-icon-3">',
        html += '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">';
        html += '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">';
        html += '<rect x="0" y="0" width="24" height="24" />';
        html += '<path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero" />';
        html += '<path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />';
        html += '</g></svg></span></button>';
        html += '</td></tr>';

      $('#new_size').append(html);
 
    });

    $(document).on('click', '#removesize', function (){
  
       $(this).closest('#sizeform').remove();

    });

</script>
@endsection