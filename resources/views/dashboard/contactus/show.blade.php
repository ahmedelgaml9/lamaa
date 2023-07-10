@extends('dashboard.layouts.master')
@section('content')

						   <div class="post d-flex flex-column-fluid" id="kt_post">
						     	<div id="kt_content_container" class="container">
								    <div class="row g-5 g-xl-8">
								   	   <div class="col-xl-12">
                                           <div class="card mb-5 mb-xl-8">
											   <div class="card-header border-0 pt-5">
												     <h3 class="card-title align-items-start flex-column">
												   	    <span class="card-label fw-bolder fs-3 mb-1">{{__('admin.view')}} - {{$contacts->title}} </span>
												   </h3>
										            <div class="card-toolbar">
										        
									                </div>
                                                </div>

									             <div class="card-body py-3">
												 	 <div class="d-flex flex-wrap py-5">
														  <div class="flex-equal me-5">
														  	   <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
															      <tr>
																	 <td class="text-gray-400">{{__('admin.title')}}</td>
																	 <td class="text-gray-800">{{ $contacts->title }}</td>
															 	  </tr>

																  <tr>
																	 <td class="text-gray-400">{{__('admin.content')}}</td>
																	 <td class="text-gray-800">{{ $contacts->info }}</td>
																  </tr>

																  <tr>
																	 <td class="text-gray-400">{{__('admin.user_name')}}</td>
																 	 <td class="text-gray-800">{{ $contacts->name }}</td>
																  </tr>
																
																  <tr>
																	 <td class="text-gray-400">{{__('admin.email')}}</td>
																 	 <td class="text-gray-800">{{ $contacts->email }}</td>
																  </tr>
															
																   <tr>
																 	  <td class="text-gray-400">{{__('admin.phone')}}</td>
																	  <td class="text-gray-800">{{ $contacts->mobile }}</td>
															       </tr>

															  	    <tr>
																   	   <td class="text-gray-400">{{__('admin.created')}}</td>
																	   <td class="text-gray-800">{{ $contacts->created_at }}</td>
															 	   </tr>
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



