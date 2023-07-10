@extends('dashboard.layouts.master')
@section('content')
                         
	<div class="d-flex flex-column flex-lg-row">
	 	 <div class="flex-lg-row-fluid me-lg-15 order-2 order-lg-1 mb-10 mb-lg-0">
		 	 <div class="card card-flush pt-3 mb-5 mb-xl-10">
				  <div class="card-header">
					  <div class="card-title">
					 	 <h2 class="fw-bolder">{{__('admin.notifications_detail')}}</h2>
				 	  </div>
				 </div>
			
			  	<div class="card-body pt-3">
					  <div class="mb-10">
						   <div class="d-flex flex-wrap py-5">
							   <div class="flex-equal me-5">
								    <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
									     <tr>
										     <td class="text-gray-400">{{__('admin.notification_title')}}</td>
										     <td class="text-gray-800">{{ $notification->title }}</td>
									      </tr>
							
										    <tr>
											   <td class="text-gray-400">{{__('admin.notification_content')}}</td>
										  	   <td class="text-gray-800">{{ $notification->content }}</td>
										   </tr>

										     <tr>
											    <td class="text-gray-400">{{__('admin.created_at')}}</td>
											    <td class="text-gray-800">{{ $notification->created_at}}</td>
										     </tr>
									    </table>
								    </div>
							    </div>
						    </div>
					   </div>
		    	   </div>
		       </div>
	      </div>
							
@endsection
@section('after_content')
@endsection
@section('css')

    <link href="{{asset('dashboard/dist/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
@endsection
@section('script')
    <script src="{{asset('dashboard/dist/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>

@endsection
