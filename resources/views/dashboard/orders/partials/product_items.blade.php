
<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
        <thead>
        <tr class="border-bottom border-gray-200 text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
            <th class="min-w-150px">{{__('admin.name')}}</th>
            <th class="min-w-125px">{{__('admin.quantity')}}</th>
            <th class="min-w-125px">{{__('admin.total')}}</th>
        </tr>
        </thead>
      
           <tbody class="fw-bold text-gray-800">
             @foreach($orderProductsDetails['productsDetails'] as $productRow)
             <tr>
                <td>
                    <label class="w-150px">{{$productRow['title']}}</label>
                </td>
                <td>{{$productRow['quantity']}}</td>
                <td>{{round($productRow['salePrice'], 2)}}</td>
             </tr>
             @if($productRow['hasOffer'] && isset($productRow['offerDetails']['type']) && $productRow['offerDetails']['type'] == 'product')
                @php
                $offerProduct = $productRow['offerDetails']['offerProduct'];
                if($offerProduct){
                    $productRow['title'] = $offerProduct->title;
                    $productRow['sku'] = $offerProduct->sku;
                    $productRow['quantity'] = $productRow['offerDetails']['get'];
                    $productRow['salePrice'] = 0;
                }else{
                   $productRow['quantity'] = $productRow['offerDetails']['get'];
                }
                @endphp
                <tr>
                    <td>
                        <label class="w-150px">{{$productRow['title']}}</label>
                            <div class="fw-normal text-gray-600"><span class="badge badge-light-danger">{{__('admin.offer')}} <br/> </span></div>
                    </td>
                    <td>{{$productRow['sku']}}</td>
                    <td>{{$productRow['quantity']}}</td>
                    <td>{{round($productRow['salePrice'], 2)}}</td>
                </tr>
             @endif
             @endforeach
         </tbody>
      </table>
  </div>

