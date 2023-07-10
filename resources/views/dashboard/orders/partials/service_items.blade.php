
 <div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
        <thead>
            <tr class="border-bottom border-gray-200 text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                <th class="min-w-150px">{{__('admin.name')}}</th>
                <th class="min-w-125px">{{__('admin.carsize')}}</th>
                <th class="min-w-125px">{{__('admin.mattresstype')}}</th>
                <th class="min-w-125px">{{__('admin.additions')}}</th>
                <th class="min-w-125px">{{__('admin.total')}}</th>
           </tr>
          </thead>
              <tbody class="fw-bold text-gray-800">
                   @foreach($services as $productRow)
                 <tr>
                   <td>
                      <label class="w-150px">{{$productRow['title']}}</label>
                   </td>
                      <td>{{$productRow->pivot->size}}</td>
                      <td>{{$productRow->pivot->mattress }}</td>
                      <td>
                         @foreach($order->additions as $addition)
                            {{$addition->name }} <br>
                         @endforeach
                       </td>
                         <td>{{round($productRow->pivot->amount )}}</td>
                       </tr>
                       @endforeach
                   </tbody>
              </table>
          </div>

        <div class="d-flex justify-content-end mt-10">
           <div class="mw-300px">
              <div class="d-flex flex-stack mb-3">
                  <div class="fw-bold pe-10 text-gray-600 fs-7">{{__('admin.cart_total')}}</div>
                  <div class="text-end fw-bolder fs-6 text-gray-800">{{  $order->services_total + $order->products_total }}</div>
              </div>
              <div class="d-flex flex-stack mb-3">
                 <div class="fw-bold pe-10 text-gray-600 fs-7">{{__('admin.promo_code_discount')}}</div>
                 <div class="text-end fw-bolder fs-6" style="color:red;">{{round($order->coupon_discount, 2)}}</div>
             </div>

              <div class="d-flex flex-stack mb-3">
                 <div class="fw-bold pe-10 text-gray-600 fs-7">{{__('admin.order_total')}}</div>
                 <div class="text-end fw-bolder fs-6 text-gray-800">{{round($order->total, 2)}}</div>
            </div>
        </div>
    </div>
