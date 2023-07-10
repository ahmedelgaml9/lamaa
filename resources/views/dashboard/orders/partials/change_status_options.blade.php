    <form action="{{route('orders.sendToDriver', $order->id)}}" method="POST">
        @csrf
      <div class="mb-10">
           <label class="form-label fw-bold">{{__('admin.status')}}:</label>
                <div>
                   <select name="status" class="form-select form-select-solid" id="change-order-status-select" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true">
                        <option></option>
                        @foreach(orderStatusesList() as $key => $value)
                          <option value="{{$key}}" @if($key == $order->status) selected @endif>{{$value}}</option>
                         @endforeach
                  </select>
             </div>
        </div>
 
         <div class="mb-10">
             <label class="form-label fw-bold">{{__('admin.delivery_man')}}:</label>
                 <div>
                     <select name="driver_id" class="form-select form-select-solid"  data-kt-select2="true" data-placeholder="اختر المندوب" data-allow-clear="true">
                         <option></option>
                          @foreach(\App\User::where('user_type', 'driver')->where('city_id',$order->city_id)->get() as $user)
                           <option value="{{$user->id}}" @if($user->id == $order->driver_id) selected @endif>{{$user->name}}</option>
                          @endforeach
                     </select>
                </div>
          </div>

            <div class="hide-show-status-content" id="cancel-reason-input-container"  @if(!in_array($order->status, [\App\Models\Order::STATUS_CANCELED]))style="display: none"@endif>
               <div class="mb-10">
                   <label class="form-label fw-bold">{{__('admin.cancel_reason')}}:</label>
                     <div>
                         <input name="cancelled_reason" type="text" class="form-control" value="{{$order->cancelled_reason}}">
                      </div>
                 </div>
           </div>

            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.Close')}}</button>
                 <button type="submit" class="btn btn-primary">{{__('admin.save')}}</button>
            </div>
      </form>


