@extends('dashboard.layouts.master')
@section('toolbar')
@endsection
@section('content')

      <div class="post d-flex flex-column-fluid" id="kt_post">
           <div id="kt_content_container" class="container">
                 <div class="d-flex flex-column flex-lg-row">
                     <div class="flex-lg-row-fluid me-lg-15 order-2 order-lg-1 mb-10 mb-lg-0">
                        <div class="card card-flush pt-3 mb-5 mb-xl-10">
                           <div class="card-header">
                                <div class="card-title">
                                    <h2 class="fw-bolder">{{__('admin.order_details')}}</h2>
                                </div>

                                 <div class="card-toolbar">
                                     <a href="{{route('orders.edit', $order->id)}}" class="btn btn-sm btn-light-primary" style="margin: 0 2px;"><i class="fa fa-edit"></i> {{__('admin.edit')}}</a>
                                     <a href="{{route('orders.index', ['user_id' => $order->user_id])}}" class="btn btn-sm btn-light-info" target="_blank" style="margin: 0 2px;"><i class="fa fa-list"></i> {{__('trans_admin.history_orders')}}</a>
                                     <a href="{{route('orders.view_invoice', $order->orderNumber)}}" class="btn btn-sm btn-light-dark" target="_blank" style="margin: 0 2px;"><i class="fa fa-file-invoice"></i> {{__('trans_admin.invoice')}}</a>
                                 </div>
                             </div>
                   
                            <div class="card-body pt-3">
                                <div class="mb-0">
                                     <div class="d-flex flex-wrap py-5">
                                        <div class="flex-equal me-5">
                                            <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
                                               <tr>
                                                  <td class="text-gray-400 min-w-175px w-175px">{{__('admin.order_number')}} :</td>
                                                  <td class="text-gray-800 min-w-200px">
                                                      <a href="" class="text-gray-800 text-hover-primary">{{$order->order_number}}</a>
                                                   </td>
                                                </tr>
                                        
                                                <tr>
                                                   <td class="text-gray-400">{{__('admin.created_date')}} :</td>
                                                   <td class="text-gray-800">{{$order->created_at->format('d/m/Y h:i A')}}</td>
                                                </tr>
                                               
                                                 <tr>
                                                    <td class="text-gray-400">{{__('admin.deliverydate')}} :</td>
                                                     <td class="text-gray-800">
                                                         {{$order->delivery_date }}
                                                       <div class="fw-bold fs-6 text-gray-400" style="font-size: 12px !important; font-weight: bold !important;"><i class="fa fa-clock"></i> {{implode(' - ', [date('H:i',strtotime($order->delivery_start_time)) , date('H:i',strtotime($order->delivery_end_time))])}}</div>
                                                   </td>
                                                 </tr>
                                      
                                                   <tr>
                                                      <td class="text-gray-400">{{__('admin.status')}} :</td>
                                                      <td class="text-gray-800"><a href="javascript:void(0);" class="change-order-status-btn" data-url="{{route('orders.statuses-list', ['id' => $order->id])}}">{!! orderStatusLabel($order->status) !!} <i class="fa fa-edit" style="color: #F1416C;"></i></a> </td>
                                                   </tr>
                                             </table>
                                         </div>
                                    </div>
                                </div>
                          </div>
                      </div>
        
                      <div class="card card-flush pt-3 mb-5 mb-lg-10">
                         <div class="card-header">
                             <div class="card-title">
                                   @if(count($products) > 0)
                                    <h2 class="fw-bolder">{{__('admin.products')}}</h2>
                                    @endif
                               </div>
                          </div>
                  
                           <div class="card-body pt-0">
                               <div id="order_products_details">
                                    @if(count($products) > 0)
                                      @include('dashboard.orders.partials.product_items')
                                    @endif
                                   <h5 class="mb-4">{{__('admin.services')}}</h5>
                                    @include('dashboard.orders.partials.service_items')
                               </div>
                          </div>
                    </div>
             
                    <div class="card card-flush pt-3 mb-5 mb-xl-10" style="display: none;">
                       <div class="card-header">
                           <div class="card-title">
                               <h2>Recent Events</h2>
                           </div>
                
                            <div class="card-toolbar">
                                <a href="#" class="btn btn-light-primary">View All Events</a>
                            </div>
                       </div>
                
                       <div class="card-body pt-0">
                          <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 text-gray-600 fw-bold gy-5" id="kt_table_customers_events">
                                    <tbody>
                                       <tr>
                                          <td class="min-w-400px">Invoice
                                              <a href="#" class="fw-bolder text-gray-800 text-hover-primary me-1">7142-2432</a>status has changed from
                                               <span class="badge badge-light-primary me-1">In Transit</span>to
                                               <span class="badge badge-light-success">Approved</span></td>
                                              <td class="pe-0 text-gray-600 text-end min-w-200px">10 Mar 2021, 6:05 pm</td>
                                         </tr>
                        
                                        <tr>
                                           <td class="min-w-400px">Invoice
                                               <a href="#" class="fw-bolder text-gray-800 text-hover-primary me-1">4868-5981</a>is
                                                <span class="badge badge-light-info">In Progress</span></td>
                                               <td class="pe-0 text-gray-600 text-end min-w-200px">20 Jun 2021, 5:30 pm</td>
                                           </tr>
                                        <tr>
                                        <td class="min-w-400px">Invoice
                                          <a href="#" class="fw-bolder text-gray-800 text-hover-primary me-1">5628-9772</a>status has changed from
                                             <span class="badge badge-light-warning me-1">Pending</span>to
                                             <span class="badge badge-light-info">In Progress</span></td>
                                       <td class="pe-0 text-gray-600 text-end min-w-200px">05 May 2021, 5:30 pm</td>
                                      </tr>
                                      <tr>
                                        <td class="min-w-400px">
                                           <a href="#" class="fw-bolder text-gray-800 text-hover-primary me-1">Brian Cox</a>has made payment to
                                           <a href="#" class="fw-bolder text-gray-800 text-hover-primary">9699-4604</a></td>
                                           <td class="pe-0 text-gray-600 text-end min-w-200px">22 Sep 2021, 2:40 pm</td>
                                       </tr>
                                        <tr>
                                           <td class="min-w-400px">Invoice
                                              <a href="#" class="fw-bolder text-gray-800 text-hover-primary me-1">2499-3930</a>status has changed from
                                                <span class="badge badge-light-info me-1">In Progress</span>to
                                                <span class="badge badge-light-primary">In Transit</span></td>
                                               <td class="pe-0 text-gray-600 text-end min-w-200px">22 Sep 2021, 6:05 pm</td>
                                           </tr>
                                       </tbody>
                                  </table>
                             </div>
                         </div>
                    </div>
               </div>

               <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-300px mb-10 order-1 order-lg-2">
                  <div class="card card-flush mb-0">
                      <div class="card-body pt-0 fs-6 mt-5">
                          <div class="mb-7">
                             <div class="d-flex align-items-center">
                               <div class="symbol symbol-35px symbol-circle m-5">
                                   @if($customer->avatar)
                                  <img src="{{$customer->avatar}}" alt="image" />
                                    {{--                                        <div class="bg-success position-absolute border border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"></div>--}}
                                    @else
                                    @php
                                    $customerBgColor = randomBootstrapColorsLabel($customer->id);
                                    $firstLetterOfCustomer = strtoupper(substr(Str::slug($customer->name), 0, 1));
                                    @endphp
                                    <span class="symbol-label fs-2x fw-bold text-{{$customerBgColor}} bg-light-{{$customerBgColor}}">{{$firstLetterOfCustomer}}</span>
                                    {{--                                        <div class="bg-success position-absolute border border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"></div>--}}
                                    @endif
                                </div>
                                 <div class="d-flex flex-column">
                                     <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-2">{{$customer->name}}</a>
                                     <a href="#" class="fw-bold text-gray-600 text-hover-primary">{{$customer->mobile}}</a>
                                </div>
                           </div>
                      </div>
                  
                      <div class="separator separator-dashed mb-7"></div>
                          <div class="mb-7">
                             <form action="{{route('orders.update', $order->id)}}" method="POST">
                                @method('put')
                                @csrf
                      
                              {{--<h5 class="mb-4">{{__('admin.address_details')}} <i class="fa fa-edit" style="color: #F1416C;"></i>
                                </h5>
                                 <div class="mb-10">
                                   <label class="form-label fw-bold">{{__('الإختيار من العناوين المسجلة')}}:</label>
                                      <div>
                                           <select name="address[address_id]" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true">
                                              <option value=""></option>
                                               @foreach($customer->addresses as $addressRow)
                                                 <option value="{{$addressRow->id}}" @if($address->id == $addressRow->id) selected @endif>{{$addressRow->type.' '.$addressRow->address}}</option>
                                               @endforeach
                                           </select>
                                      </div>
                                  </div>--}}
                                   {{--<p style="text-align: center;"> --- OR --- </p>--}}
                                   {{--<div class="mb-10">
                                        <label class="form-label fw-bold">{{__('admin.city')}}:</label>
                                      <div>
                                         <select name="address[city_id]" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true">
                                             <option value=""></option>
                                              @foreach(citiesList() as $key => $value)
                                                <option value="{{$key}}" @if($key == $address->city_id) selected @endif>{{$value}}</option>
                                             @endforeach
                                         </select>
                                     </div>
                                </div>--}}
                     
                              {{--<div class="mb-10">
                                   <label class="form-label fw-bold">{{__('admin.address')}}:</label>
                                     <div>
                                        <input type="text" class="form-control" placeholder=" شارع الملك عبدالله بن عبدالعزيز، السلام، الخرج ، السعودية" name="address[address]" value="{{old('address', $address->address)}}">
                                    </div>
                                 </div>--}}

                           {{--<div class="mb-10">
                                <label class="form-label fw-bold">{{__('admin.geolocation')}}:</label>
                               <div>
                                  @php
                                  $addressGeolocation = $address->lat?$address->lat.','.$address->lng:null
                                  @endphp
                                 <input type="text" class="form-control" placeholder="24.177501857, 47.29694739" name="address[geolocation]" value="{{old('geolocation', $addressGeolocation)}}">
                              </div>
                          </div>--}}

                           {{--<div class="mb-10">
                                 <label class="form-label fw-bold">{{__('admin.type')}}:</label>
                                 <select name="address[delivery_type]" class="form-select form-select-solid">
                                    @foreach(__('admin.addresses_types') as $key => $value)
                                       <option value="{{$key}}" @if($key == $address->delivery_type) selected @endif>{{$value}}</option>
                                    @endforeach
                                 </select>
                            </div>--}}
                            {{--<div class="mb-10">
                                <label class="form-label fw-bold">{{__('admin.name')}}:</label>
                                <div>
                                    <input type="text" class="form-control" placeholder="55123456" name="address[username]" value="{{old('username', $address->username?:$customer->name)}}">
                                </div>
                            </div>--}}
                            
                           {{--<div class="mb-10">
                                 <label class="form-label fw-bold">{{__('admin.phone')}}:</label>
                               <div>
                                  <input type="text" class="form-control" placeholder="55123456" name="address[mobile]" value="{{old('mobile', $address->mobile?:$customer->mobile)}}">
                              </div>
                            </div>--}}
                              {{-- <button type="submit" class="btn btn-primary">{{__('admin.save')}}</button>
                             </form>
                        </div>--}}
                   
                      {{--<div class="separator separator-dashed mb-7"></div>--}}
                           <div class="mb-10">
                              <form action="{{route('orders.update', $order->id)}}" method="POST">
                                  @method('put')
                                  @csrf
                                <h5 class="mb-4">{{__('admin.payment_method')}}</h5>
                                  <div class="mb-0">
                                     <div class="mb-10">
                                        @php
                                            $orderPaymentMethod = \App\Models\PaymentMethod::where('status', 1)->where('gateway',$order->origin_payment_method)->first();
                                        @endphp
                                         <label class="form-label fw-bold">{{__('admin.payment_method')}}:</label>
                                             <select name="payment_method" class="form-select form-select-solid">
                                                @foreach(\App\Models\PaymentMethod::where('status', 1)->pluck('name', 'id') as $key => $value)
                                                  <option value="{{$key}}" @if($key == $orderPaymentMethod->id) selected @endif>{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                         <div class="mb-10">
                                             <label class="form-label fw-bold">{{__('trans_admin.payment_status')}}:</label>
                                                  <select name="payment_status" class="form-select form-select-solid">
                                                     @foreach(__('trans_admin.payment_status_options') as $key => $value)
                                                      <option value="{{$key}}" @if($key == $order->payment_status) selected @endif>{{$value}}</option>
                                                     @endforeach
                                                   </select>
                                                </div>
                                            </div>
                                             <button type="submit" class="btn btn-primary">{{__('admin.save')}}</button>
                                         </form>
                                     </div>
                                  <div class="separator separator-dashed mb-7"></div>
                             </div>
                        </div>
                    </div>
              </div>
          </div>
    </div>
@endsection
@section('modal')
    <div class="modal fade" id="products-list-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="min-width: 1000px;">
            <form action="{{route('orders.update', $order->id)}}" method="POST">
                @method('put')
                @csrf
              <div class="modal-content">
                 <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">{{__('admin.products')}}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                  <div class="modal-body">
                     <div id="product-list-container">
                          Loading .... .
                      </div>
                 </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.Close')}}</button>
                      <button type="submit" class="btn btn-primary">{{__('admin.add')}}</button>
                  </div>
                </div>
             </form>
         </div>
     </div>
    @include('dashboard.orders.partials.change_status_modal')

@endsection
@section('script')
<script>
    $(function (){
        $('#btn-products-list-modal').on('click', function (){
            $('#products-list-modal').modal('show');
            $.ajax({
                url: "{{route('orders.products_list', ['id' => $order->id])}}",
                type: "GET",
                success: function (response) {
                    $('#product-list-container').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });

        });
    })
</script>
@endsection
