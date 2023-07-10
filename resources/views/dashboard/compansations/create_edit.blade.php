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
                {!! Form::open(['url' => route('compansations.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
              @else
                 {!! Form::open(['url' => route('compansations.store'), 'method' => 'post', 'files'=>true]) !!}
              @endif

               <div class="card-body py-3">
                   <div class="tab-content">
                         @foreach(supportedLanguages() as $keyLang => $valueLang)
                        <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">
                               <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.order_id')}}</label>
                                           <select name="order_id" data-control="select2" id="order_id" class="form-select">
                                                <option value="">اختر طلب</option>
                                                  @foreach($orders as $order)
                                                    <option value="{{$order}}">{{'99'.$order}}</option>
                                                  @endforeach
                                             </select>
                                       </div>
                                
                                       <div class="col-md-6">
                                          <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.users')}}</label>
                                             <div class="mb-5">
                                                <select class="form-control"  id="user" name="user_id">
  
                                                </select>
                                          </div>
                                    </div>
                               </div>

                               <div class="row">
                                  <div class="col-md-6">
                                     <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.action')}}</label>
                                       <div class="mb-5 {{ $errors->has('action') ? 'has-error' : '' }}">
                                          <input type="text" name="action" class="form-control form-control-solid"  value="{{old('action',$data->action)}}" placeholder="{{__('admin.action')}}"/>
                                          <span class="text-danger">{{ $errors->first('action') }}</span>
                                      </div>
                                </div>

                                <div class="col-md-6">
                                   <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.customer_notes')}}</label>
                                     <div class="mb-5 {{ $errors->has('customer_notes') ? 'has-error' : '' }}">
                                         <input type="text" name="customer_notes" class="form-control form-control-solid"  value="{{old('customer_notes',$data->customer_notes)}}" placeholder="{{__('admin.customer_notes')}}"/>
                                         <span class="text-danger">{{ $errors->first('customer_notes') }}</span>
                                    </div>
                               </div>
                                    <input type="hidden" name="created_by" class="form-control form-control-solid"  value="1" />
                              </div>

                            <div class="row">
                                <div class="col-md-6">
                                     <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.type')}}</label>
                                        <div class="mb-5 {{ $errors->has('offer_type') ? 'has-error' : '' }}">
                                            <select name="type" id="type"  class="form-select">
                                                <option value="">اختر النوع</option>
                                                <option value="product" @if($data->type == "product") selected @endif>{{__('admin.product')}}</option>
                                                <option value="balance" @if($data->type == "balance") selected @endif>{{__('admin.balance')}}</option>
                                          </select>
                                      </div>
                               </div>

                                 <div class="col-md-6"  id="balance" style="display:none;">
                                      <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.balance')}}</label>
                                        <div class="mb-5">
                                            <input type="number" name="value" class="form-control form-control-solid" value="{{old('value',$data->value)}}"  placeholder="{{__('admin.balance')}}"/>
                                       </div>
                               </div>

                               <div class="col-md-6"  id="expiry" style="display:none;">
                                   <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.expiry_date')}}</label>
                                       <div class="mb-5 {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                                            <input class="form-control form-control-solid" name="expiry_date"   value="{{old('expiry_date',$data->expiry_date)}}"  placeholder="{{__('admin.expiry_date')}}" id="kt_datepicker_1"/>
                                            <span class="text-danger">{{ $errors->first('expiry_date') }}</span>
                                       </div>
                                </div>

                                <div class="col-md-6">
                                     <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.staff_notes')}}</label>
                                      <div class="mb-5 {{ $errors->has('staff_notes') ? 'has-error' : '' }}">
                                           <input type="text" name="staff_notes" class="form-control form-control-solid" value="{{old('staff_notes',$data->staff_notes)}}" placeholder="{{__('admin.staff_notes')}}"/>
                                           <span class="text-danger">{{ $errors->first('staff_notes') }}</span>
                                     </div>
                                </div>
                            </div>

                              <div class="table-responsive" id="product" style="display:none;">
                                  <table id="kt_create_new_custom_fields" class="table align-middle table-row-dashed fw-bold fs-6 gy-5">
                                      <thead>
                                          <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                              <th class="pt-0">{{__('admin.products')}}</th>
                                              <th class="pt-0">{{__('admin.quantity')}}</th>
                                              <th class="pt-0 text-end">{{__('admin.remove')}}</th>
                                          </tr>
                                     </thead>
                                     <tbody id="newRow">
                                        <tr id="inputFormRow">
                                           <td>
                                               <select name="products[]"  class="form-select">
                                                  <option value="">اختر النوع</option>
                                                   @foreach($products as $product)
                                                     <option value="{{$product->id}}">{{$product->title}}</option>
                                                   @endforeach
                                              </select>
                                            <td>
                                                <input class="form-control form-control-solid"  name="quantity[]" value="" />
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
                                     </tbody>
                                </table>
                                   <a id="addRow" class="btn btn-primary" >{{__('admin.create')}}</a>
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
<script type="text/javascript">

if($("#type option:selected").val() == "product")
{

    $("#product").show();
    $("#balance").hide();
    $("#expiry").hide();

}

if($("#type option:selected").val() == "balance" )
{

    $("#product").hide();
    $("#balance").show();
    $("#expiry").show();

}

$("#type").change(function(){

 if(this.value  == "product")
 {

    $("#product").show();
    $("#balance").hide();
    $("#expiry").hide();

  }
else{

    $("#product").hide();
    $("#balance").show();
    $("#expiry").show();
}
});

$("#addRow").click(function () {

    var options =  @json($products);
  // Map options to their HTML version, and join them
  var optionsHtml = options.map(function (opt) {
    return '<option value="' + opt.id + '">' + opt.title + '</option>';
  }).join('');

var html = '';
html += '<tr id="inputFormRow">';
html += '<td>';
html +='<select name="products[]"  class="form-select"><option>اختر النوع</option>';
html += optionsHtml;
html += '</select>';
html +='</td><td>';
html +='<input type="text" class="form-control form-control-solid" name="quantity[]"  />';
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

$("#kt_datepicker_1").flatpickr();

$('#order_id').on('change',function(){

var id= $(this).val();
var url = "{{ url('admin/getordercustomer')}}";
var token = $("input[name='_token']").val();

$.ajax({
    url: url,
    method: 'POST',
    data: {id:id, _token:token},
    success: function(data) {

      $("[name='user_id']").html('');
      $("[name='user_id']").html(data.options);
      
    }
 });
});

</script>
@endsection
