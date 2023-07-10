<style>
    .product-checkbox[type='checkbox'] {
        -webkit-appearance:none;
        width:45px;
        height:45px;
        border-radius:5px;
        border:2px solid #ececec;
        background: #F5F8FA;
    }
    .product-checkbox[type='checkbox']:checked {
        background: #b5b5b5;
    }
</style>
<div class="row">
@foreach($products as $product)
    @php
    $currentQuantity = isset($orderProductsArray[$product->id])?(int) $orderProductsArray[$product->id]:1;
    @endphp
     <div class="col-md-4 text-center mb-9">
          <div class="octagon mx-auto mb-2 d-flex w-150px h-150px bgi-no-repeat bgi-size-contain bgi-position-center" style="background-image:url('{{$product->thumbnail}}')"></div>
         <div class="mb-0">
             <a href="{{$product->thumbnail}}" class="text-dark fw-bolder text-hover-primary fs-3">{{$product->title}}</a>
             <div class="text-muted fs-6 fw-bold">
                 <div class="form-group">
                     <div class="input-group input-group-solid" style="max-width: 140px;margin: 15px 80px;">
                         <input type="number" name="products[{{$loop->index}}][quantity]" class="form-control" min="1" value="{{$currentQuantity}}">
                         <input type="checkbox" class="product-checkbox" name="products[{{$loop->index}}][id]" value="{{$product->id}}" @if(key_exists($product->id, $orderProductsArray)) checked @endif/>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 @endforeach
</div>
