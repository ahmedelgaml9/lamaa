@extends('dashboard.layouts.master')
@section('content')

       <div class="post d-flex flex-column-fluid" id="kt_post">
           <div id="kt_content_container" class="container">
               <div class="card">
                   <div class="card-header border-0 pt-5">
                       <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">{{ __('admin.edit_info')}}</span>
                      </h3>
                       <div class="card-toolbar">
                             <ul class="nav">

                            </ul>
                      </div>
                  </div>

                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                         {!! Form::open(['url' => route('deliverydays.update', $day->id), 'method' => 'put', 'files'=>true]) !!}
                       <div class="row">
                            <div class="col-xl-6">
                                  <label class="required fw-bold fs-6 mb-2">{{ __('admin.day')}}</label>
                                    <select class="form-select" name="day_of_week">
                                         <option value="0"  @if($day->day_of_week == 0) selected   @endif>الأحد</option>
                                         <option value="1"  @if($day->day_of_week == 1) selected   @endif >الإثنين</option>
                                         <option value="2"  @if($day->day_of_week == 2) selected   @endif >الثلاثاء</option>
                                         <option value="3"  @if($day->day_of_week == 3) selected   @endif >الأربعاء</option>
                                         <option value="4"  @if($day->day_of_week == 4) selected   @endif>الخميس</option>
                                         <option value="5"  @if($day->day_of_week == 5) selected   @endif >الجمعة</option>
                                         <option value="6"  @if($day->day_of_week == 6) selected   @endif>السبت</option>
                                 </select>
                            </div>

                              <div class="col-md-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">  {{__('admin.status')}}</label>
                                       <div class="mb-5 form-check form-switch form-check-custom form-check-solid {{      $errors->has('status') ? 'has-error' : '' }}">
                                            <input class="form-check-input" type="checkbox" value="1" id="status" name="status" @if($day->status > 0) checked="checked" @endif />
                                            <label class="form-check-label fw-bold text-gray-400 ms-3" for="status">{{__('admin.active')}}</label>
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                      </div>
                                </div>
                            </div>

                                @foreach(supportedLanguages() as $keyLang => $valueLang)
                                <div class="table-responsive">
                                     <table id="kt_create_new_custom_fields" class="table align-middle table-row-dashed fw-bold fs-6 gy-5">
                                        <thead>
                                           <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                <th class="pt-0">{{__('admin.start_time')}}</th>
                                                <th class="pt-0">{{__('admin.end_time')}}</th>
                                                <th class="pt-0">{{__('trans_admin.max_orders')}}</th>
                                                <th class="pt-0">{{__('admin.city')}}</th>
                                                <th class="pt-0 text-end">{{__('admin.remove')}}</th>
                                           </tr>
                                       </thead>
                                        <tbody id="newRow">
                                           @foreach($periods as $period)
                                         <tr id="inputFormRow">
                                              <td><input  type="text" class="form-control kt_datepicker_2" value="{{ $period->start_time }}"  name="start_time[]" />
                                              <td><input  type="text" class="form-control kt_datepicker_2" value="{{ $period->end_time }}"  name="end_time[]" />
                                              </td>
                                              <td><input  type="number" class="form-control" value="{{ $period->max_orders_to_accept }}"  name="max_orders_to_accept[]" required/>
                                              <td>
                                                 <select class="form-select"  data-control="select2"  name="city_id[]" required>
                                                    <option value="">اختر مدينة</option>
                                                    @foreach($cities as $city)
                                                      <option value="{{$city->id}}" @if($period->city_id == $city->id) selected  @endif>{{ $city->getTranslation('name', $keyLang)}}</option>
                                                    @endforeach
                                                 </select>
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
                                     </div>
                                       @endforeach
                                    <a id="addRow" class="btn btn-primary" >{{__('admin.add')}}</a>
                                        <div class="text-center pt-15">
                                              <button type="submit" class="btn btn-primary" >{{__('admin.save')}}
                                             </button>
                                       </div>
                                 </div>
                             </form>
                         </div>
                     </div>
               </div>

@endsection
@section('script')

    <script type="text/javascript">

        $("#addRow").click(function () {

              var array = {!! $cities !!}
              var options;
              $.each(array, function(key, value ) {
              options = options + '<option value="'+value.id+'">'+value.name['ar']+'</option>';

            });

            var html = '';
            html += '<tr id="inputFormRow">';
            html += '<td>';
            html +='<input type="text" class="form-control form-control-solid kt_datepicker_2"   name="start_time[]"  />';
            html +='</td><td>';
            html +='<input type="text" class="form-control form-control-solid kt_datepicker_2" name="end_time[]"  />';
            html +='</td><td>';
            html +='<input type="number" class="form-control form-control-solid" name="max_orders_to_accept[]"  required/>';
            html +='</td>';
            html +='<td><select name="city_id[]"  data-control="select2"  class="form-select" required><option value="">اختر مدينة</option>';
            html +=  options;
            html += '</select></td>';
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

            $(".kt_datepicker_2").flatpickr({

                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",

            });

        });

        $(document).on('click', '#removeRow', function (){

            $(this).closest('#inputFormRow').remove();


        });


        $(".kt_datepicker_2").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",

        });

    </script>

@endsection
