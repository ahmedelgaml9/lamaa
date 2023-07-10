@extends('dashboard.layouts.master')
@section('content')

						<div class="post d-flex flex-column-fluid" id="kt_post">
							<div id="kt_content_container" class="container">
								<div class="row g-5 g-xl-8">
									<div class="col-xl-12">
                                      <div class="card mb-5 mb-xl-8">
											<div class="card-header border-0 pt-5">
												<h3 class="card-title align-items-start flex-column">
													<span class="card-label fw-bolder fs-3 mb-1">{{__('admin.view')}} </span>
												</h3>
										      <div class="card-toolbar">

									          </div>
                                          </div>
									       <div class="card-body py-3">
													<div class="d-flex flex-wrap py-5">
														<div class="flex-equal me-5">
															<table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
                                                                 <tr>
																	<td class="text-gray-400">#</td>
																	<td class="text-gray-800">{{$cart->id}}</td>
																	<td class="text-gray-400">
                                                                        <form action="{{route('orders.cartToOrder', $cart->id)}}" method="POST">
                                                                            @csrf
                                                                            <button href="{{url('admin/orders/create')}}" class="btn btn-sm  btn-primary">
                                                                                {{__('admin.proccessorder')}}</button>
                                                                        </form>

                                                                    </td>
																 </tr>
                                                                  <tr>
																	<td class="text-gray-400">{{__('admin.user_name')}}</td>
																	@if(isset($cart->user))
                                                                    <td class="text-gray-800">{{ $cart->user->name }}</td>
																	@endif
																</tr>
                                                                <tr>

	                                                               <td class="text-gray-400">{{__('admin.user_phone')}}</td>
																   @if(isset($cart->user))
																   <td class="text-gray-800">{{ $cart->user->phone }}</td>
																   @endif
																</tr>
                                                                <tr>
																	<td class="text-gray-400">{{__('admin.cart_amount')}}</td>
																	<td class="text-gray-800">{{ $cart->amount() }}</td>
																</tr>
                                                                <tr>
																	<td class="text-gray-400">{{__('admin.cart_updated_at')}}</td>
																	<td class="text-gray-800">{{ $cart->updated_at }}</td>
																</tr>
															</table>
														</div>
                                                    </div>

                                                 <div class="d-flex flex-wrap py-5">
														<div class="flex-equal me-5">
															<h4>{{__('admin.products')}}</h4>
															<table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
                                                                <thead>
                                                                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                                            <th class="min-w-125px">#</th>
                                                                            <th class="min-w-125px">{{__('admin.name')}}</th>
                                                                            <th class="min-w-125px">{{__('admin.price')}}</th>

                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="text-gray-600 fw-bold">
                                                                            @foreach($cart->items as $cart)
                                                                           <tr>
                                                                            <td>{{ $cart->id }}</td>
                                                                            <td>{{ $cart->product->title}}</td>
                                                                            <td>{{ $cart->product->price }}</td>
                                                                        </tr>
                                                                           @endforeach
                                                                     </tbody>
															</table>
														</div>
                                                 </div>
									       </div>
							    	  </div>
                              </div>
                        </div>
                 </div>
            </div>


@endsection



