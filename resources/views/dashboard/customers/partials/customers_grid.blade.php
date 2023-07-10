<!--begin::Row-->
<div class="row g-6 g-xl-9">
         @foreach($customers as $customer)
        <div class="col-md-6 col-xxl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-stack mb-3">
                         @php
                            $actions = [
                                         ['label' => __('admin.show'), 'url' => route('customers.show', $customer->id)],
                                         ['label' => __('admin.edit'), 'url' => route('customers.edit', $customer->id)],
                                     ];
                         @endphp
                         @include('dashboard.customers.partials.grid_actions', ['actions' => $actions])
                       
                       </div>
                     <div class="d-flex flex-center flex-column pt-1 p-2">
                       <div class="symbol symbol-65px symbol-circle mb-5">
                             @if($customer->avatar)
                                <img src="{{$customer->avatar}}" alt="image" />
                            @else
                            @php
                                $customerBgColor = randomBootstrapColorsLabel($customer->id);
                                $firstLetterOfCustomer = strtoupper(substr(Str::slug($customer->name), 0, 1));
                             @endphp
                            <span class="symbol-label fs-2x fw-bold text-{{$customerBgColor}} bg-light-{{$customerBgColor}}">{{$firstLetterOfCustomer}}</span>
{{--                            <div class="position-absolute border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"><span style="color: red">{{$customer->cart_rate}}</span></div>--}}
                            @endif
                       </div>
                  
                       <a href="{{route('customers.show', $customer->id)}}" class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">{{$customer->name}}</a>
                          <div class="fw-bold text-gray-400">{{$customer->mobile}}</div>
                            <div class="fw-bold text-gray-400 mb-6 text-center">{{$customer->email}}</div>
                                <div class="d-flex flex-center flex-wrap">
                                  <div class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                                     <div class="fs-6 fw-bolder text-gray-700 text-center">{{$customer->orders_count}}</div>
                                        <div class="fw-bold text-gray-400">Orders</div>
                                    </div>
                               </div>
                           </div>
                     </div>
                 </div>
            </div>
         @endforeach
</div>
<!--end::Row-->
{!! $customers->links('dashboard.partials.paginator', ['disableJS' => true]) !!}
