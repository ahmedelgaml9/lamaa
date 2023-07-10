<div class="modal fade" id="kt_change_status_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
           <div class="modal-content">
                <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">{{__('admin.change_order_status')}}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <div>
                         <div class="mb-10">
                              <label class="form-label fw-bold">{{__('admin.status')}}:</label>
                                 <div>
                                     <select name="status" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true">
                                         <option></option>
                                          @foreach(orderStatusesList() as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.Close')}}</button>
                                  <button type="submit" class="btn btn-primary">{{__('admin.change')}}</button>
                            </div>
                       </div>
                  </div>
             </div>
         </div>
    </div>
