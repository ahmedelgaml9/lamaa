<div id="profile">
    <div class="wrap">
        <img id="profile-img" src="{{url('/images/support-avatar.png')}}" class="online" alt="{{$authUser->name}}" />
        <p>{{$authUser->name}}</p>
        <i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>
        {{--             <div id="status-options">--}}
        {{--              <ul>--}}
        {{--                <li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>--}}
        {{--                <li id="status-away"><span class="status-circle"></span> <p>Away</p></li>--}}
        {{--                <li id="status-busy"><span class="status-circle"></span> <p>Busy</p></li>--}}
        {{--                <li id="status-offline"><span class="status-circle"></span> <p>Offline</p></li>--}}
        {{--              </ul>--}}
        {{--            </div>--}}
        <div id="expanded" dir="rtl">
            <div class="text-right"><strong>{{__('trans_admin.opened_conversations')}}: ({{$chatsCounts['opened']}})</strong></div>
            <div class="text-right"><strong>{{__('trans_admin.closed_conversations')}}: ({{$chatsCounts['closed']}})</strong></div>
            <hr>
            <div class="text-right"><strong>{{__('trans_admin.results')}}: ({{$conversations->total()}})</strong></div>
            <br>
            <form action="{{route('message.index')}}">
                <label for="twitter">{{-- <i class="fa fa-facebook fa-fw" aria-hidden="true"></i>--}}{{__('admin.name')}}</label>
                <input name="filter[customer_name]" type="text" value="{{request()->input('filter.customer_name')}}" />
                <label for="filter[customer_mobile]">{{__('trans_admin.mobile')}}</label>
                <input name="filter[customer_mobile]" type="text" value="{{request()->input('filter.customer_name')}}" />
                <label for="filter[order_number]">{{__('trans_admin.order')}}</label>
                <input name="filter[order_number]" type="text" value="{{request()->input('filter.order_number')}}" />
                <label for="filter[city]">{{__('admin.city')}}</label>
                <select class="form-control" name="filter[city]">
                   <option></option>
                   @foreach(\App\Models\City::where('active', 1)->get() as $city)
                   <option value="{{$city->id}}" @if($city->id == request()->input('city')) selected @endif>{{$city->name}}</option>
                   @endforeach
                </select>
                <label for="filter[status]">{{__('admin.status')}}</label>
                <select class="form-control" name="filter[status]">
                    <option></option>
                    @foreach(__('trans_admin.chat_status_list') as $key => $value)
                        <option value="{{$key}}" @if(request()->input('filter.status') && $key == request()->input('filter.status', 'not_selected')) selected @endif>{{$value}}</option>
                    @endforeach
                </select>
                <label for="filter[action]">{{__('trans_admin.chat_action')}}</label>
                <select class="form-control" name="filter[action]">
                    <option></option>
                    @foreach(__('trans_admin.chat_actions_list') as $key => $value)
                        <option value="{{$key}}" @if($key == request()->input('filter.action')) selected @endif>{{$value}}</option>
                    @endforeach
                </select>
                <button class="btn btn-default">{{__('admin.search')}}</button>
            </form>

        </div>
    </div>
</div>
<div id="contacts">
            <ul>
              @php
              $user_ids = [];
              @endphp
        @foreach($conversations as $inbox)
                 @php
                   $currentConversationStatus = $inbox->status > 0?true:false;
                   $authCustomer = $inbox->authCustomer?:new \App\User;
                   $authEmployee = $inbox->authEmployee?:new \App\User;
                 @endphp

                    <li @if(isset($user) && $user) class="contact {{$inbox->id == request()->route('conversation')?'active':''}}" @else class="contact"@endif>
                    <div class="wrap">
                         <a href="{{route('message.read', $inbox->id).'?'.http_build_query(request()->query())}}" style="color: #fff;">
                             @if($currentConversationStatus)
                         <span class="contact-status online"></span>
                                 @else
                                 <span class="contact-status offline" style="background: #ff0018;"></span>

                             @endif
                        <img src="{{url('/images/customer-avatar.png')}}" alt="" />
                        <div class="meta">
                            <p class="name">{{$inbox->title?:$authCustomer->name}}</p>
                            <p class="preview">{{$authEmployee->job_title}}</p>
                        </div>
                       </a>
                    </div>
                </li>
        @endforeach

            </ul>


        </div>
{!! $conversations->links() !!}
