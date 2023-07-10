<div class="card card-flush">
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table id="kt_project_users_table" class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bolder">
                <thead class="fs-7 text-gray-400 text-uppercase">
                <tr>
                    <th class="min-w-10px">ID</th>
                    <th class="min-w-150px">Name</th>
                    <th class="min-w-90px">Orders</th>
                    {{--<th class="min-w-50px text-end">Status</th>--}}
                    <th class="min-w-50px text-end">Actions</th>

                </tr>
                </thead>
               
                 <tbody class="fs-6">
                     @foreach($customers as $customer)
                    <tr>
                    <td>{{$customer->id}}</td>
                    <td>
                       
                        <div class="d-flex align-items-center">
                            <div class="me-5 position-relative">
                                <div class="symbol symbol-35px symbol-circle">
                                     @if($customer->avatar)
                                        <img src="{{$customer->avatar}}" alt="image" />
{{--                                     <div class="bg-success position-absolute border border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"></div>--}}
                                     @else
                                     @php
                                      $customerBgColor = randomBootstrapColorsLabel($customer->id);
                                      $firstLetterOfCustomer = strtoupper(substr(Str::slug($customer->name), 0, 1));
                                     @endphp
                                        <span class="symbol-label fs-2x fw-bold text-{{$customerBgColor}} bg-light-{{$customerBgColor}}">{{$firstLetterOfCustomer}}</span>
                                     @endif
{{--                                    <div class="position-absolute border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"><span style="color: red">{{$customer->cart_rate}}</span></div>--}}
                                   </div>
                                </div>
                           
                                <div class="d-flex flex-column justify-content-center">
                                    <a href="{{route('customers.show', $customer->id)}}" class="mb-1 text-gray-800 text-hover-primary">{{$customer->name}}</a>
                                    <div class="fw-bold fs-6 text-gray-400">{{$customer->email}}</div>
                                    <div class="fw-bold fs-6 text-gray-400">{{$customer->mobile}}</div>
                                </div>
                           </div>
                   
                       </td>
                       <td>{{$customer->orders_count}}</td>
                   
                     {{--<td>
                          {!! customerStatusLabels($customer->orders_status) !!} <span class="badge badge-info">{{$customer->cart_rate}}</span>
                      </td>--}}
                        <td class="text-end">
                            @php
                                $actions = [
                                             ['label' => __('admin.show'), 'url' => route('customers.show', $customer->id)],
                                             ['label' => __('admin.edit'), 'url' => route('customers.edit', $customer->id)],

                                      ];
                            @endphp
                            @include('dashboard.components.table_actions', ['actions', $actions])
                        </td>
                     </tr>
                   @endforeach
                 </tbody>
            </table>
        </div>
             {!! $customers->links('dashboard.partials.paginator', ['disableJS' => true]) !!}
    </div>
</div>
