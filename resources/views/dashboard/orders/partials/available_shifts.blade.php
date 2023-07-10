    <div class="mb-10">
       <label class="form-label fw-bold">{{__('admin.available_shifts')}}:</label>
       <div>
          <select name="available_times" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true">
            <option></option>
              @foreach($times as $time)
                <option value="{{implode(' - ', [$time->start_time, $time->end_time])}}">{{implode(' - ', [$time->start_time, $time->end_time])}}</option>
              @endforeach
            </select>
        </div>
   </div>
