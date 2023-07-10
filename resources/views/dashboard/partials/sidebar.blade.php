@inject('pages','App\Models\Page')
@php
    $allpages = $pages->all();
@endphp
<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
         <div class="aside-logo flex-column-auto" id="kt_aside_logo">
             {{-- <a href="">
                     <img alt="Logo" src="http://mawared.badee.com.sa/images/logo.svg" class="h-15px logo" />
                  </a>--}}
                                  <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
                                           <span class="svg-icon svg-icon-1 rotate-180">
							 	                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								         	         <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										                  <polygon points="0 0 24 0 24 24 0 24" />
										                  <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
								 		                  <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.5" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
                                                      </g>
							                     </svg>
						     	             </span>
                                         </div>
                                    </div>

							    	<div class="aside-menu flex-column-fluid">
									        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
									           <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
											         <div class="menu-item">
												            <a class="menu-link" href="{{url('admin')}}">
																	<span class="menu-icon">
																		<span class="svg-icon svg-icon-2">
																		<i class="fas fa-home"></i>

																		</span>
																		</span>
													   <span class="menu-title">الرئيسية</span>
											    	</a>
										 	 </div>

								          @if(Auth::user()->user_type == 'admin' || array_intersect(['2','3'], json_decode(Auth::user()->staff->role->permissions, true)))
										  <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                               <span class="menu-link">
                                                  <span class="menu-icon">
                                                      <span class="svg-icon svg-icon-2">
												 	    <i class="fas fa-cart-arrow-down"></i>
												     </span>
												    </span>
                                                   <span class="menu-title">{{__('admin.orders')}}</span>
                                                  <span class="menu-arrow"></span>
                                              </span>
                                              <div class="menu-sub menu-sub-accordion">
                                                  <div class="menu-item">
                                                      <a class="menu-link" href="{{route('orders.index')}}">
												         	<span class="menu-icon">
													 	      <span class="svg-icon svg-icon-2">
														       <i class="fas fa-cart-arrow-down"></i>

													    	  </span>
													      </span>
													      <span class="menu-title">{{__('admin.weborders')}}</span>
												      </a>
                                                </div>

											   <div class="menu-item">
                                                    <a class="menu-link"  id="kt_docs7_sweetalert_basic" href="#">
											 	        <span class="menu-icon">
													       <span class="svg-icon svg-icon-2">
													       	  <i class="fas fa-hand-holding-usd"></i>
												    	  </span>
											            </span>
												         <span class="menu-title">{{__('admin.compansations')}}</span>
												      </a>
                                                </div>
 
                                                 <div  class="menu-item">
                                                     <a class="menu-link" href="{{route('delegates.index')}}">
											 	          <span class="menu-icon">
													          <span class="svg-icon svg-icon-2">
													              <i class="fa fa-truck"></i>
													         </span>
												          </span>
												           <span class="menu-title">{{__('admin.delegates')}}</span>
												       </a>
                                                 </div>

										 	     <div class="menu-item">
                                                      <a class="menu-link" href="{{route('deliverydays.index')}}">
											 	         <span class="menu-icon">
												    	      <span class="svg-icon svg-icon-2">
												    	           <i class="fa fa-truck"></i>
												    	      </span>
												             </span>
												               <span class="menu-title">{{__('admin.deliverytime')}}</span>
												           </a>
                                                       </div>
											       </div>
										      </div>
									     	  @endif

										     @if(Auth::user()->user_type == 'admin' || array_intersect(['14','15'], json_decode(Auth::user()->staff->role->permissions, true)))
									          <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                                    <span class="menu-link">
                                                        <span class="menu-icon">
                                                            <span class="svg-icon svg-icon-2">
												                <i class="fas fa-tags"></i>
												            </span>
												        </span>
                                                       <span class="menu-title">{{__('admin.productsandservices')}}</span>
                                                      <span class="menu-arrow"></span>
                                                </span>
                                               <div class="menu-sub menu-sub-accordion">
								     	             <div class="menu-item">
                                                        <a class="menu-link" href="{{route('category.index')}}">
													       <span class="menu-bullet">
															   <span class="bullet bullet-dot"></span>
														   </span>
                                                         <span class="menu-title">{{__('admin.categories')}}</span>
                                                      </a>
                                                 </div>

									               <div class="menu-item">
                                                        <a class="menu-link" href="{{route('services.index')}}">
												             <span class="menu-bullet">
															     <span class="bullet bullet-dot"></span>
														    </span>
                                                              <span class="menu-title">{{__('admin.services')}}</span>
                                                        </a>
                                                   </div>
      
											       <div class="menu-item">
												     	<a class="menu-link" href="{{route('products.index')}}">
														    <span class="menu-bullet">
															   <span class="bullet bullet-dot"></span>
													 	    </span>
													      <span class="menu-title">{{__('admin.products')}}</span>
												       </a>
											       </div>

													<div class="menu-item">
														<a class="menu-link" href="{{route('carsize.index')}}">
															  <span class="menu-bullet">
																 <span class="bullet bullet-dot"></span>
														 	  </span>
															   <span class="menu-title">{{__('admin.carsizes')}}</span>
													  	   </a>
													   </div>

													   <div class="menu-item">
															   <a class="menu-link" href="{{route('mattersstype.index')}}">
																     <span class="menu-bullet">
																 	     <span class="bullet bullet-dot"></span>
															 	     </span>
																    <span class="menu-title">{{__('admin.mattress')}}</span>
														  	     </a>
														     </div>
													    </div>
												    </div>
												     @endif
 
													@if(Auth::user()->user_type == 'admin' || array_intersect(['4','5'], json_decode(Auth::user()->staff->role->permissions, true)))
								                     <div class="menu-item">
                                                          <a class="menu-link" href="{{route('customers.index')}}">
												              <span class="menu-icon">
													            <span class="svg-icon svg-icon-2">
													               <i class="fas fa-users"></i>
												 	            </span>
											                </span>
								         	                <span class="menu-title">{{__('admin.customers')}}</span>
							                             </a>
							                      </div>
									              @endif
									              @if(Auth::user()->user_type == 'admin')
									 	         <div class="menu-item">
						                             <a class="menu-link" href="{{route('banks.index')}}">
										                  <span class="menu-icon">
											              <span class="svg-icon svg-icon-2">
											                  <i class="fa fa-building"></i>
										                  </span>
									    	          </span>
                                                          <span class="menu-title">{{__('admin.bank_accounts')}}</span>
										              </span>
                                                   </a>
                                              </div>
								 	          @endif

								    	      @if(Auth::user()->user_type == 'admin' || array_intersect(['6'], json_decode(Auth::user()->staff->role->permissions, true)))
								       	       <div class="menu-item">
                                                   <a class="menu-link" href="{{route('promocodes.index')}}">
										              <span class="menu-icon">
										          	       <span class="svg-icon svg-icon-2">
											                  <i class="fas fa-tags"></i>
										    	         </span>
										              </span>
                                                       <span class="menu-title">{{__('admin.promocodes')}}</span>
                                                    </a>
                                              </div>
								             @endif

							            	 @if(Auth::user()->user_type == 'admin' || array_intersect(['7'], json_decode(Auth::user()->staff->role->permissions, true)))
									         <div class="menu-item">
									     	     <a class="menu-link" href="{{ route('notification-messages.index') }}">
									                <span class="menu-icon">
									                    <span class="svg-icon svg-icon-2">
											              <i class="fas fa-bell"></i>
											           </span>
                                                    </span>
										             <span class="menu-title">{{__('admin.app_notifications')}}</span>
									 	        </a>
								    	   </div>
								           @endif

								            @if(Auth::user()->user_type == 'admin')
                                           <div class="menu-item">
                                                 <a class="menu-link" href="{{ url('admin/contacts')}}">
									         	     <span class="menu-icon">
										    	        <span class="svg-icon svg-icon-2">
											               <i class="fas fa-phone"></i>
										        	   </span>
										           </span>
                                                    <span class="menu-title">{{__('admin.contactus')}}</span>
                                                 </a>
                                           </div>
								   	      @endif
 
										 @if(Auth::user()->user_type == 'admin')
										   <div class="menu-item">
										   	    <a class="menu-link" id="kt_docs4_sweetalert_basic" href="#">
											  	     <span class="menu-icon">
													     <span class="svg-icon svg-icon-2">
													        <i class="fas fa-robot"></i>
													    </span>
													 </span>
													 <span class="menu-title">{{__('admin.chatbot_answers')}}</span>
												 </a>
										   </div>
									   	   @endif
 
							              @if(Auth::user()->user_type == 'admin')
                                          @foreach(supportedLanguages() as $keyLang => $valueLang)
                                          <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
									          <span class="menu-link">
									   	           <span class="menu-icon">
											         <span class="svg-icon svg-icon-2">
											            <i class="fas fa-pen-nib"></i>
											       </span>
									         	  </span>
                                                  <span class="menu-title">{{__('admin.pages')}}</span>
										           <span class="menu-arrow"></span>
                                               </span>

								        	    <div class="menu-sub menu-sub-accordion">
                                                       <div class="menu-item">
                                                          <a  class="menu-link" href="{{ url('admin/settings/home_page') }}">
														      <span class="menu-bullet">
														   	     <span class="bullet bullet-dot"></span>
														     </span>
                                                             <span class="menu-title">{{__('admin.home_page')}}</span>
											             </a>
                                                    </div>
										                @foreach($allpages as $page)
											          <div class="menu-item">
													        <a class="menu-link" href="{{ url('admin/pages/'.$page->id.'/edit') }}">
														 	    <span class="menu-bullet">
															   	   <span class="bullet bullet-dot"></span>
															     </span>
													       	      <span class="menu-title">{{ $page->title }}</span>
													         </a>
												          </div>
										                  @endforeach
									    	           </div>
									              </div>
							         	        @endforeach
						     	                @endif

							            	   @if(Auth::user()->user_type == 'admin' || array_intersect(['18',19], json_decode(Auth::user()->staff->role->permissions, true)))
									             <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
								    	               <span class="menu-link">
										    	           <span class="menu-icon">
											                  <span class="svg-icon svg-icon-2">
												                  <i class="fas fa-money-check"></i>
												             </span>
										                 </span>
									         	            <span class="menu-title">{{__('admin.financial')}}</span>
                                                            <span class="menu-arrow"></span>
									                    </span>
     
                                                       <div class="menu-sub menu-sub-accordion">
									                       @if(Auth::user()->user_type == 'admin' || array_intersect(['18',19], json_decode(Auth::user()->staff->role->permissions, true)))
                                                            <div class="menu-item">
															    <a class="menu-link" href="{{ url('admin/financial')}}">
														  		     <span class="menu-bullet">
															      	 <span class="bullet bullet-dot"></span>
																    </span>
															      	<span class="menu-title">{{__('admin.payment_methods')}}</span>
														     	</a>
									                	  </div>
                                                          @endif

                                                           <div class="menu-item">
											                   <a class="menu-link" id="kt_docs3_sweetalert_basic" href="{{ url('admin/reports/cashback')}}">
												    		       <span class="menu-bullet">
															          <span class="bullet bullet-dot"></span>
														           </span>
                                                                  <span class="menu-title">{{__('admin.cashback_reports')}}</span>
										            	       </a>
									            	       </div>
 
								                            @if(Auth::user()->user_type == 'admin' || array_intersect(['18'], json_decode(Auth::user()->staff->role->permissions, true)))
								                             <div class="menu-item">
										                           <a class="menu-link" id="kt_docs2_sweetalert_basic"  href="{{ url('admin/offersfilter')}}">
										                                <span class="menu-bullet">
											  	                           <span class="bullet bullet-dot"></span>
													                    </span>
													                     <span class="menu-title">{{__('admin.offers_reports')}}</span>
												                      </a>
											   	                 </div>
											                      @endif
									                        </div>
								                      </div>
							        	               @endif
 
						           	                 @if(Auth::user()->user_type == 'admin')
									                  <div class="menu-item">
										                   <a class="menu-link" id="kt_docs_sweetalert_basic" href="#">
															   <span class="menu-bullet">
                                                                  <span class="bullet bullet-dot"></span>
															    </span>
										                	   <span class="menu-title">{{__('admin.reports')}}</span>
									                	   </a>
								     	               </div>
								                     @endif

                                                    @if(Auth::user()->user_type == 'admin')
                                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                                        <span class="menu-link">
                                                            <span class="menu-icon">
                                                               <span class="svg-icon svg-icon-2">
												            	<i class="fas fa-unlock-alt"></i>
												             </span>
												            </span>
                                                             <span class="menu-title">{{__('admin.users_staf')}}</span>
                                                             <span class="menu-arrow"></span>
                                                        </span>

                                                     <div class="menu-sub menu-sub-accordion">
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="{{ route('users.index') }}">
                                                                 <span class="menu-bullet">
                                                                  <span class="bullet bullet-dot"></span>
                                                                </span>
                                                                <span class="menu-title">{{__('admin.users_staf')}}</span>
                                                             </a>
                                                       </div>
     
                                                       <div class="menu-item">
                                                            <a class="menu-link" href="{{route('roles.index')}}">
                                                                <span class="menu-bullet">
                                                                  <span class="bullet bullet-dot"></span>
                                                                  </span>
                                                                <span class="menu-title">{{__('admin.permissions')}} </span>
                                                              </a>
                                                          </a>
                                                      </div>

                                                       <div class="menu-item">
                                                           <a class="menu-link" id="kt_docs8_sweetalert_basic"  href="#">
                                                               <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                              </span>
                                                                <span class="menu-title">{{__('admin.activitylog')}}</span>
                                                             </a>
                                                         </div>
                                                    </div>
                                                </div>
							                   @endif

							                    @if(Auth::user()->user_type == 'admin')
							                     <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
									                 <span class="menu-link">
									        	         <span class="menu-icon">
									        	            <span class="svg-icon svg-icon-2">
                                                              <i class="fas fa-cogs"></i>
											               </span>
									    	            </span>
									        	        <span class="menu-title">{{__('admin.website_settings')}}</span>
									            	    <span class="menu-arrow"></span>
									                </span>
 
                                                    <div class="menu-sub menu-sub-accordion">
											   	       <div class="menu-item">
													       <a class="menu-link" href="{{ route('slider.index') }}">
														       <span class="menu-bullet">
														         <span class="bullet bullet-dot"></span>
														       </span>
												            	<span class="menu-title">{{__('admin.slider')}}</span>
													        </a>
											   	       </div>

												  	      <div class="menu-item">
									                           <a class="menu-link" href="{{ route('websiteservice.index') }}">
													               <span class="menu-bullet">
													                  <span class="bullet bullet-dot"></span>
													               </span>
										                           <span class="menu-title">{{__('admin.websiteservices')}}</span>
									                            </a>
							         	                    </div>

							             	             {{--<div class="menu-item">
									                            <a class="menu-link" href="{{ url('admin/settings/orders') }}">
													                <span class="menu-bullet">
													                  <span class="bullet bullet-dot"></span>
													               </span>
										                            <span class="menu-title">{{__('admin.order_settings')}}</span>
									                            </a>
							         	                    </div>--}}
                                                        </div>
                                                    </div>
							                        @endif

                                                   @if(Auth::user()->user_type == 'admin')
							                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
								    	                 <span class="menu-link">
									        	             <span class="menu-icon">
										     	                <span class="svg-icon svg-icon-2">
											                      <i class="fa fa-map-marker"></i>
										                	    </span>
									    	                 </span>
									             	         <span class="menu-title">{{__('admin.regions')}}</span>
                                                            <span class="menu-arrow"></span>
                                                       </span>
                                                     <div class="menu-sub menu-sub-accordion">
                                                         <div class="menu-item">
									                	     <a class="menu-link" href="{{route('region.index')}}">
										 		                  <span class="menu-bullet">
											    	                <span class="bullet bullet-dot"></span>
											 	                </span>
										     	                <span class="menu-title">{{__('admin.regions')}}</span>
										                     </a>
							                           </div>
   
												  	 <div class="menu-item">
													 	 <a class="menu-link" href="{{route('cities.index')}}">
														 	   <span class="menu-bullet">
																  <span class="bullet bullet-dot"></span>
															   </span>
														    	<span class="menu-title">{{__('admin.cities')}}</span>
														 	</a>
											    	 	</div>
													 </div>
												 </div>
											  @endif
										  </div>
									  </div>
								  </div>


</div>
<script>

const button = document.getElementById('kt_docs_sweetalert_basic');
const button2 = document.getElementById('kt_docs2_sweetalert_basic');
const button3 = document.getElementById('kt_docs3_sweetalert_basic');
const button4 = document.getElementById('kt_docs4_sweetalert_basic');
const button7 = document.getElementById('kt_docs7_sweetalert_basic');
const button8 = document.getElementById('kt_docs8_sweetalert_basic');


button.addEventListener('click', e =>{
    e.preventDefault();

    Swal.fire({
        text: "عفواً هذي الخاصية غير متاحة لك يرجى طلب تطويرها من شركة بديع الحلول",
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
});


button2.addEventListener('click', e =>{
    e.preventDefault();

    Swal.fire({
        text: "عفواً هذي الخاصية غير متاحة لك يرجى طلب تطويرها من شركة بديع الحلول",
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
});

button3.addEventListener('click', e =>{
    e.preventDefault();

    Swal.fire({
        text: "عفواً هذي الخاصية غير متاحة لك يرجى طلب تطويرها من شركة بديع الحلول",
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
});

button4.addEventListener('click', e =>{
    e.preventDefault();

    Swal.fire({
        text: "عفواً هذي الخاصية غير متاحة لك يرجى طلب تطويرها من شركة بديع الحلول",
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
});



button7.addEventListener('click', e =>{
    e.preventDefault();

    Swal.fire({
        text: "عفواً هذي الخاصية غير متاحة لك يرجى طلب تطويرها من شركة بديع الحلول",
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
});

button8.addEventListener('click', e =>{
    e.preventDefault();

    Swal.fire({
        text: "عفواً هذي الخاصية غير متاحة لك يرجى طلب تطويرها من شركة بديع الحلول",
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
});


</script>
