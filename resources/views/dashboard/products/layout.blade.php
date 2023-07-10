@extends('dashboard.layouts.master')
@section('toolbar')
@endsection
@section('content')
        
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            @if($customer->avatar)
                                <img src="{{$customer->avatar}}" alt="image" />
                                <div class="bg-{{customerStatusClass($customer->status)}} position-absolute border border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"></div>
                            @else
                                @php
                                    $firstLetterOfCustomer = strtoupper(substr(Str::slug($customer->name), 0, 1));
                                    $customerBgColor = randomBootstrapColorsLabel($customer->id);
                                @endphp
                                <span class="symbol-label fs-2x fw-bold text-{{$customerBgColor}} bg-light-{{$customerBgColor}}">{{$firstLetterOfCustomer}}</span>
                                <div class="bg-{{customerStatusClass($customer->status)}} position-absolute border border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"></div>
                            @endif
                        </div>
                    </div>
                   
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javascript:void(0);" class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">{{$customer->name}}</a>
{{--                                    <a href="#">--}}
{{--                                        <!--begin::Svg Icon | path: icons/duotone/Design/Verified.svg-->--}}
{{--                                        <span class="svg-icon svg-icon-1 svg-icon-primary">--}}
{{--																	<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">--}}
{{--																		<path d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z" fill="#00A3FF" />--}}
{{--																		<path class="permanent" d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z" fill="white" />--}}
{{--																	</svg>--}}
{{--																</span>--}}
{{--                                        <!--end::Svg Icon-->--}}
{{--                                    </a>--}}
                                    {!! customerStatusLabels((int) $customer->status) !!}
                                </div>
                                <!--end::Name-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                    <a href="javascript:void(0)" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                        <!--begin::Svg Icon | path: icons/duotone/Map/Marker1.svg-->
                                        <span class="svg-icon svg-icon-4 me-1">
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<rect x="0" y="0" width="24" height="24" />
																		<path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero" />
																	</g>
																</svg>
															</span>
                                        <!--end::Svg Icon-->{{Str::limit($customer->default_address?:'----------------------------------', 40, '...')}}</a>
                                    <a href="javascript:void(0)" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                        <!--begin::Svg Icon | path: icons/duotone/General/User.svg-->
                                        <span class="svg-icon svg-icon-4 me-1">
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
	                                                              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                      <rect x="0" y="0" width="24" height="24"/>
                                                                      <path d="M8,2.5 C7.30964406,2.5 6.75,3.05964406 6.75,3.75 L6.75,20.25 C6.75,20.9403559 7.30964406,21.5 8,21.5 L16,21.5 C16.6903559,21.5 17.25,20.9403559 17.25,20.25 L17.25,3.75 C17.25,3.05964406 16.6903559,2.5 16,2.5 L8,2.5 Z" fill="#000000" opacity="0.3"/>
                                                                      <path d="M8,2.5 C7.30964406,2.5 6.75,3.05964406 6.75,3.75 L6.75,20.25 C6.75,20.9403559 7.30964406,21.5 8,21.5 L16,21.5 C16.6903559,21.5 17.25,20.9403559 17.25,20.25 L17.25,3.75 C17.25,3.05964406 16.6903559,2.5 16,2.5 L8,2.5 Z M8,1 L16,1 C17.5187831,1 18.75,2.23121694 18.75,3.75 L18.75,20.25 C18.75,21.7687831 17.5187831,23 16,23 L8,23 C6.48121694,23 5.25,21.7687831 5.25,20.25 L5.25,3.75 C5.25,2.23121694 6.48121694,1 8,1 Z M9.5,1.75 L14.5,1.75 C14.7761424,1.75 15,1.97385763 15,2.25 L15,3.25 C15,3.52614237 14.7761424,3.75 14.5,3.75 L9.5,3.75 C9.22385763,3.75 9,3.52614237 9,3.25 L9,2.25 C9,1.97385763 9.22385763,1.75 9.5,1.75 Z" fill="#000000" fill-rule="nonzero"/>
                                                                  </g>
																</svg>
															</span>
                                        <!--end::Svg Icon-->{{$customer->mobile}}</a>
{{--                                    @if($customer->email)--}}
{{--                                    <a href="javascript:void(0)" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">--}}
{{--                                        <!--begin::Svg Icon | path: icons/duotone/Communication/Mail-at.svg-->--}}
{{--                                        <span class="svg-icon svg-icon-4 me-1">--}}
{{--																<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">--}}
{{--																	<path d="M11.575,21.2 C6.175,21.2 2.85,17.4 2.85,12.575 C2.85,6.875 7.375,3.05 12.525,3.05 C17.45,3.05 21.125,6.075 21.125,10.85 C21.125,15.2 18.825,16.925 16.525,16.925 C15.4,16.925 14.475,16.4 14.075,15.65 C13.3,16.4 12.125,16.875 11,16.875 C8.25,16.875 6.85,14.925 6.85,12.575 C6.85,9.55 9.05,7.1 12.275,7.1 C13.2,7.1 13.95,7.35 14.525,7.775 L14.625,7.35 L17,7.35 L15.825,12.85 C15.6,13.95 15.85,14.825 16.925,14.825 C18.25,14.825 19.025,13.725 19.025,10.8 C19.025,6.9 15.95,5.075 12.5,5.075 C8.625,5.075 5.05,7.75 5.05,12.575 C5.05,16.525 7.575,19.1 11.575,19.1 C13.075,19.1 14.625,18.775 15.975,18.075 L16.8,20.1 C15.25,20.8 13.2,21.2 11.575,21.2 Z M11.4,14.525 C12.05,14.525 12.7,14.35 13.225,13.825 L14.025,10.125 C13.575,9.65 12.925,9.425 12.3,9.425 C10.65,9.425 9.45,10.7 9.45,12.375 C9.45,13.675 10.075,14.525 11.4,14.525 Z" fill="#000000" />--}}
{{--																</svg>--}}
{{--															</span>--}}
{{--                                        <!--end::Svg Icon-->{{$customer->email}}</a>--}}
{{--                                        @endif--}}
                                </div>
                                <!--end::Info-->
                            </div>
                           
                            <div class="d-flex my-4">
                                <!--begin::Col-->
                                <div class="col">
                                    <div class="card card-dashed flex-center min-w-175px">
                                        <span class="fs-4 fw-bold text-info pb-1 px-2">{{__('admin.sum_payments')}}</span>
                                        <span class="fs-lg-2tx fw-bolder d-flex justify-content-center"><span style="font-size: 15px">ريال</span>
													<span data-kt-countup="true" data-kt-countup-value="{{$countStatistics['sum_payments']}}">0</span></span>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                          
                            <div class="d-flex my-4">
                                <!--begin::Col-->
                                <div class="col">
                                    <div class="card card-dashed flex-center min-w-175px">
                                        <span class="fs-4 fw-bold text-info pb-1 px-2">{{__('admin.user_balance')}}</span>
                                        <span class="fs-lg-2tx fw-bolder d-flex justify-content-center"><span style="font-size: 15px">ريال</span>
													<span data-kt-countup="true" data-kt-countup-value="{{$countStatistics['user_balance']}}">0</span></span>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Title-->
                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap flex-stack">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap">
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder" data-kt-countup="true" data-kt-countup-value="{{$countStatistics['all_orders']}}">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-bold fs-6 text-primary">{{__('admin.all_orders')}}</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder" data-kt-countup="true" data-kt-countup-value="{{$countStatistics['completed_orders']}}">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-bold fs-6 text-success">{{__('admin.completed_orders')}}</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder" data-kt-countup="true" data-kt-countup-value="{{$countStatistics['processing_orders']}}">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-bold fs-6 text-warning">{{__('admin.processing_orders')}}</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bolder" data-kt-countup="true" data-kt-countup-value="{{$countStatistics['cancelled_orders']}}">0</div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-bold fs-6 text-danger">{{__('admin.cancelled_orders')}}</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
{{--                                    <!--begin::Stat-->--}}
{{--                                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">--}}
{{--                                        <!--begin::Number-->--}}
{{--                                        <div class="d-flex align-items-center">--}}
{{--                                            <div class="fs-2 fw-bolder" data-kt-countup="true" data-kt-countup-value="{{$countStatistics['pending_orders']}}">0</div>--}}
{{--                                        </div>--}}
{{--                                        <!--end::Number-->--}}
{{--                                        <!--begin::Label-->--}}
{{--                                        <div class="fw-bold fs-6 text-gray-400">{{__('admin.pending_orders')}}</div>--}}
{{--                                        <!--end::Label-->--}}
{{--                                    </div>--}}
{{--                                    <!--end::Stat-->--}}
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
                <!--begin::Navs-->
                <div class="d-flex overflow-auto h-55px">
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">
                        <!--begin::Nav item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{last(request()->segments()) == 'overview'?'active':''}}" href="{{route('customers.show', $customer->id)}}">Overview</a>
                        </li>
                        <!--end::Nav item-->
                        <!--begin::Nav item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{last(request()->segments()) == 'orders'?'active':''}}" href="{{route('customers.orders', $customer->id)}}">Orders</a>
                        </li>
                        <!--end::Nav item-->
                        <!--begin::Nav item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 {{last(request()->segments()) == 'addresses'?'active':''}}" href="{{route('customers.addresses', $customer->id)}}">Shipping Address</a>
                        </li>
                        <!--end::Nav item-->
                        <!--begin::Nav item-->
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link text-active-primary me-6" href="{{route('customers.edit', $customer->id)}}">Edit Profile</a>--}}
{{--                        </li>--}}
                        <!--end::Nav item-->
                    </ul>
                </div>
                <!--begin::Navs-->
            </div>
        </div>
        <!--end::Navbar-->
         @yield('sub_content')

@endsection

@section('script')

@endsection
