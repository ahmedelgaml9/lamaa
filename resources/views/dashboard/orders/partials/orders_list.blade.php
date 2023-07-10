<div class="card card-flush">
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table id="kt_project_users_table" class="table align-middle table-row-dashed fs-6 gy-5 no-footer">
                <thead class="fs-7 text-gray-400 text-uppercase">
                  <tr>
                    <th class="min-w-10px">
                        @if(!is_null(request()->get('status')))
                            <input id="parent-checkbox-table" type="checkbox">
                        @endif
                        &nbsp;ID
                    </th>
                    <th class="min-w-10px">{{__('admin.created_at')}}</th>
                    <th class="min-w-10px">{{__('admin.deliverydate')}}</th>
                    <th class="min-w-10px">{{__('admin.customer')}}</th>
                    <th class="min-w-10px">{{__('admin.total')}}</th>
                    <th class="min-w-10px">{{__('admin.city')}}</th>
                    <th class="min-w-10px">{{__('admin.payment_method')}}</th>
                    <th class="min-w-10px">{{__('admin.status')}}</th>
                    @if($template == 'downloads')
                    <th class="min-w-10px">{{__('trans_admin.driver')}}</th>
                    @endif
                    <th class="text-end min-w-100px">{{__('admin.actions')}}</th>

                 </tr>
                  </thead>
                 <tbody class="fs-6">
                  @foreach($orders as $order)
                    @php
                    $user = $order->user;
                    $driver = $order->driver;
                    $address= $order->address;
                    $customer = $order->user;
                    $customerCityName = null;
                    if($orderCity = $order->city){
                    
                     $customerCityName = $orderCity->name;

                        }else{

                            if($customer){

                                $customerCity = $customer->city;
                                if($customerCity){

                                    $customerCityName = $customerCity->name;
                                }
                            }
                        }

                            $conversationsCount = $order->conversations()->count();
                            $openedConversation = $order->conversations()->where('conversations.status', 1)->first();
                            @endphp
                           <tr>
                              <td>
                                 @if(!is_null(request()->get('status')))
                                   <input class="child-checkbox-row" type="checkbox" name="selected_orders[]" value="{{$order->id}}">
                                 @endif
                                  @if($openedConversation)
                                   <button type="button" onclick="window.open({{route('message.read', ['conversation' => $openedConversation->id, 'customer_id' => $order->user_id])}}, 'MsgWindow', 'width=450,height=700')" class="btn btn-icon btn-active-light-primary position-relative w-30px h-30px w-md-40px h-md-40px">
                                       <span class="svg-icon svg-icon-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                 <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"></path>
                                                 <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"></path>
                                               </svg>
                                           </span>
                                        <span class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>
                                    </button>
                                    @endif
                                     &nbsp; {{$order->order_number}}
                                 </td>

                                <td>{{$order->created_at->format('Y-m-d')}}
                                    <div class="fw-bold fs-6 text-gray-400"><i class="fa fa-clock"></i> {{$order->created_at->format('h:i A')}}</div>
                                </td>
                                <td>{{$order->delivery_date }}
                                <div class="fw-bold fs-6 text-gray-400" style="font-size: 12px !important; font-weight: bold !important;"><i class="fa fa-clock"></i> {{implode(' - ', [date('H:i',strtotime($order->delivery_start_time)) , date('H:i',strtotime($order->delivery_end_time))])}}</div>
                                </td>
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{$user?$user->name:'--'}}">{{$user?\Str::limit($user->name, 15):'--'}}
                                    <div class="fw-bold fs-6 text-gray-400"> {{$address?$address->mobile:'---'}}</div>
                                </td>
                                <td>{{$order->total}}</td>
                                @if(isset($order->city))<td>{{ $order->city->name }} </td>@else<td></td>@endif
                                <td>{{$order->origin_payment_method }}</td>
                                <td><a href="javascript:void(0);" class="change-order-status-btn" @if(in_array($order->status, [\App\Models\Order::STATUS_CANCELED]) ) data-bs-toggle="tooltip" data-bs-placement="top" title="{{$order->cancelled_reason?:'لم يتم إضافة سبب للإلغاء'}}" @endif data-url="{{route('orders.statuses-list', ['id' => $order->id])}}">{!! orderStatusLabel($order->status) !!}</a></td>
                                @if($template == 'downloads')
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{$driver?$driver->name:'--'}}">{{$driver?\Str::limit($driver->name, 15):'--'}}
                                    <div class="fw-bold fs-6 text-gray-400"> {{$driver?$driver->route_code:'---'}}</div>
                                    </td>
                                 @endif
                                 <td class="text-end">
                                    @php
                                     $actions = [

                                           ['label' => __('admin.show'), 'url' => route('orders.show', $order->id)],
                                           ['label' => __('admin.change_status'), 'url' => route('orders.statuses-list', $order->id), 'ajax' => true, 'class' => 'change-order-status-btn'],

                                          ];
                                     @endphp
                                    @include('dashboard.components.table_actions', ['actions', $actions])
                                </td>
                            </tr>
                           @endforeach
                     </tbody>
                 </table>
             </div>
                 {!! $orders->links('dashboard.partials.paginator', ['disableJS' => true]) !!}
         </div>
     </div>

