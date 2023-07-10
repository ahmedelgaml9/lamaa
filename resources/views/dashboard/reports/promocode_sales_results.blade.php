@extends('dashboard.layouts.master')
@section('content')

					 	<div class="post d-flex flex-column-fluid" id="kt_post">
							 <div id="kt_content_container" class="container">
								 <div class="row g-5 g-xl-8">
									 <div class="col-xl-12">
                                       <div class="card mb-5 mb-xl-8">
											<div class="card-header border-0 pt-5">
												<h3 class="card-title align-items-start flex-column">
													<span class="card-label fw-bolder fs-3 mb-1">{{__('admin.show_results')}} </span>
												</h3>
										        <div class="card-toolbar">

												<div class="col-lg-6">
                                                  <a href="{{route('export_campaign_results',$id)}}" class="btn btn-sm btn-flex btn-dark btn-active-primary fw-bolder"><i class="fa fa-file-download"></i>تصدير</a>
                                                </div>
									          </div>
                                          </div>
										  
									        <div class="card-body py-3">
													<div class="d-flex flex-wrap py-5">
														<div class="flex-equal me-5">
															<table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
																<tr>
																	<td class="text-gray-400">{{__('admin.orders_number')}}</td>
																	<td class="text-gray-800">{{ $result }}</td>
															 	</tr>
																  <tr>
																	<td class="text-gray-400">{{__('admin.sales_total')}}</td>
																	<td class="text-gray-800">{{ $promocode_results }}</td>
																 </tr>
														     </table>
														 </div>
                                                     </div>
                                               </div>
                                          </div>


											  <div class="card">
												  <div class="card-header" style="direction:ltr;">
													 <h3 class="card-title"></h3>
													   <div class="card-toolbar">
														   <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0" id="tabs">
															  <li class="nav-item" style="font-size:18px; margin-right:20px;" >
																  <a class="nav-link" data-bs-toggle="tab"  href="#kt_tab_pane_2">{{__('admin.newcustomers')}}</a>
															  </li>
 
															  <li class="nav-item" style="font-size:18px;" >
																  <a class="nav-link active" data-bs-toggle="tab"  href="#kt_tab_pane_1">{{__('admin.cities')}}</a>
															    </li>
														    </ul>
                                                       </div>
                                                  </div>

									        	<div class="card-body">
											        <div class="tab-content" id="myTabContent">
											            <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
													         <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
                                                                   <thead>
                                                                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                                            <th class="min-w-125px">#</th>
                                                                            <th class="min-w-125px">{{__('admin.name')}}</th>
                                                                          </tr>
                                                                          </thead>

                                                                         <tbody class="text-gray-600 fw-bold">
                                                                               @foreach($cities as $city)
																				<tr>
																				<td>{{ $city->city->id }}</td>
																				<td>{{ $city->city->name}}</td>
																		    	</tr>
                                                                               @endforeach
                                                                         </tbody>
														    	    </table>
												                </div>

											     	          <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
													             <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
                                                                     <thead>
                                                                         <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                                            <th class="min-w-125px">#</th>
                                                                            <th class="min-w-125px">{{__('admin.name')}}</th>
                                                                            <th class="min-w-125px">{{__('admin.phone')}}</th>

                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="text-gray-600 fw-bold">
                                                                            @foreach($users as $user)
                                                                           <tr>
                                                                            <td>{{$user->id }}</td>
                                                                            <td>{{ $user->name }}</td>
                                                                            <td>{{ $user->mobile }}</td>
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



