       <div class="row g-6 g-xl-9">
               @foreach($products as $product)
            <div class="col-md-6 col-xxl-4">
               <div class="card">
                   <div class="card-body">
                         <div class="d-flex flex-stack mb-3">
                              <td class="text-end">
                                  <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">{{__('admin.actions')}}
                                      <span class="svg-icon svg-icon-5 m-0"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon points="0 0 24 0 24 24 0 24" /><path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)" /></g></svg></span><!--end::Svg Icon--></a>
                                      <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="{{route('products.edit', $product->id)}}" class="menu-link px-3">{{__('admin.edit')}}</a>
                                       </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_1{{$product->id}}">{{__('admin.delete')}}</a>
                                        </div>
                                    </div>
                                 </td>

                                 <div id="kt_modal_1{{$product->id}}"  tabindex="-1" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                          <div class="modal-content">
                                             <div class="modal-header" style="text-align:center;">
                                                <h5 class="modal-title" >{{__('admin.Delete Confirmation')}}</h5>
                                                 <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                      <span class="svg-icon svg-icon-2x"></span>
                                                  </div>
                                              </div>

                                                 <div class="modal-body text-center">
                                                    <p>{{ __('admin.Areyousure')}}</p>
                                                      <div class="text-center pt-15">
                                                       <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="position:relative;float:right;">{{__('admin.Close')}}</button>
                                                         {!! Form::open(['url' => route('products.destroy', $product->id), 'method' => 'delete']) !!}
                                                           <button type="submit" class="btn btn-primary mt-2">{{__('admin.Delete')}}</a>
                                                         {!! Form::close() !!}
                                                    </div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           <div>
                                <div class="badge badge-light-{{intStatusClassLabels($product->available)}} fw-bolder">{{__('admin.int_status_options.'.(int)$product->available)}}</div>
                           </div>
                      </div>
   
                       <div class="d-flex flex-center flex-column pt-1 p-2">
                       <a href="{{route('products.show', $product->id)}}" class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0 text-center">{{$product->title}}</a>
          
                       <div class="d-flex flex-center flex-wrap">
                          <div class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                               <span class="fs-lg-2tx fw-bolder d-flex justify-content-center"><span style="font-size: 15px">ريال</span><span data-kt-countup="true" data-kt-countup-value="{{(float) $product->price}}" class="counted">{{(float) $product->price}}</span>
                              </span>
{{--                            <span class="fw-bold text-gray-400 text-center"  style="font-size: 15px">{{__('admin.price')}}</span>--}}

                          </div>
                      </div>
                   </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
<!--end::Row-->
{!! $products->links('dashboard.partials.paginator', ['disableJS' => true]) !!}
