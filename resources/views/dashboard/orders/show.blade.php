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
                              <div class="mb-10">
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
                                                 <td class="text-gray-800">{!! orderStatusLabel($order->status) !!} <br> {{$order->cancelled_reason}}</td>
                                              </tr>
                                           </table>
                                      </div>
                                 </div>
                             </div>
                             @if(count($products) > 0)
                            <div class="mb-0">
                                <h5 class="mb-4">{{__('admin.products')}}</h5>
                                   @include('dashboard.orders.partials.product_items')
                             </div>
                             @endif

                           <div class="mb-0">
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
              
                   <div class="col-xxl-12">
                        <div class="card card-xxl-stretch">
                            <div class="card-header align-items-center border-0 mt-4">
                                <h3 class="card-title align-items-start flex-column">
                                   <span class="fw-bolder mb-2 text-dark">{{__('trans_admin.activities')}}</span>
                               </h3>
                             <div class="card-toolbar">
                             </div>
                        </div>
                        <div class="card-body pt-5">
                             <div class="timeline-label">
                                 <div class="timeline-item">
                                     <div class="timeline-label fw-bolder text-gray-800 fs-6" style="width:200px;">{{$order->created_at}}</div>
                                        <div class="timeline-badge">
                                            <i class="fa fa-genderless text-success fs-1"></i>
                                       </div>
                                    
                                        <div class="timeline-content d-flex">
                                           <span class="fw-bolder text-gray-800 ps-3">تم إنشاء الطلب</span>
                                       </div>
                                   </div>
                               </div>
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
                                       @else
                                       @php
                                            $customerBgColor = randomBootstrapColorsLabel($customer->id);
                                            $firstLetterOfCustomer = strtoupper(substr(Str::slug($customer->name), 0, 1));
                                         @endphp
                                         <span class="symbol-label fs-2x fw-bold text-{{$customerBgColor}} bg-light-{{$customerBgColor}}">{{$firstLetterOfCustomer}}</span>
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
                              <h5 class="mb-4">{{__('admin.address_details')}}</h5>
                         
                              <div class="mb-0">
                                 <span class="badge badge-light-info me-2">{{$address->city_name}}</span>
                                 <span class="fw-bold text-gray-600">{{$address->address}}</span>
                             </div>
                         </div>
                      
                          <div class="separator separator-dashed mb-7"></div>
                             <div class="mb-10">
                                 <h5 class="mb-4">{{__('admin.payment_method')}}</h5>
                                 <div class="mb-0">
                                    <div class="fw-bold text-gray-600">{{$order->payment?$order->origin_payment_method:null}}</div>
                                </div>
                           </div>
                        
                           <div class="mb-10">
                               <h5 class="mb-4">{{__('admin.type')}}</h5>
                                  @php
                                    $address= \App\Models\OrderShippingAddress::where('order_id',$order->id)->first();
                                  @endphp
                         
                                 <div class="mb-0">
                                     <div class="fw-bold text-gray-600">{{$address?$address->delivery_type:null}}</div>
                                 </div>
                             </div>
                        
                           <div class="separator separator-dashed mb-7"></div>
                       </div>
                   </div>
               </div>
           </div>
      </div>
 </div>
@endsection
