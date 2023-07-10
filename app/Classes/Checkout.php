<?php


namespace App\Classes;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Offer;
use App\Models\OrderReceipt;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\PromoCodeuser;
use Carbon\Carbon;
use App\Models\OrderAddition;
use phpDocumentor\Reflection\Types\This;


class Checkout
{

    public static function checkCoupon($code, $price = 0, $productsArray = [])
    {
        
       $coupon = PromoCode::where('code', trim($code))->first();

      if(!$coupon){

          return ['success' => false, 'message' => trans('messages.promo_code_not_found'), 'data' => null];
      }

      $forProducts = false;
      $couponSettings = $coupon->settings?json_decode($coupon->settings, true):[];
      $promoProducts = $coupon->products;
      $productsTotalPrice = 0;
      $appliedProductsArray = [];
      $value = 0;
      if(!$promoProducts->count() > 0){
          foreach ($promoProducts as $promoProduct){
              if(isset($productsArray[$promoProduct->id])){
                  $rowPrice = $productsArray[$promoProduct->id]['quantity']*$productsArray[$promoProduct->id]['price'];
                  if(is_array($couponSettings) && isset($couponSettings['percent'])){
                      $type = 'percent';
                      $value = $couponSettings['percent'];
                      $afterApply = max($rowPrice - ($rowPrice*$couponSettings['percent']), 0);
                      $appliedProductsArray[$promoProduct->id] = [
                          'percent' => $value,
                          'value' => $afterApply,
                      ];

                       $productsTotalPrice += $afterApply;
                  }
              }
          }

           $newPrice = max($price - $productsTotalPrice, 0);
          if($productsTotalPrice > 0){
              $forProducts = true;
              $value = ($newPrice/$price)*100;
           }
           }else{

             if(is_array($couponSettings) && isset($couponSettings['fixed'])){

                  $type = 'fixed';
                  $value = (float) $couponSettings['fixed'];
                  $newPrice = max($price - $value, 0);

             }elseif(is_array($couponSettings) && isset($couponSettings['percent'])){
                   $type = 'percent';
                   $value = (float) $couponSettings['percent']/100;
                   $newPrice = max($price - ($price*$value), 0);
             }else{
                
                  $type = null;
                  $value = 0;
                  $newPrice = $price;
          }
      }

        return ['success' => true, 'message' => trans('messages.success_process'), 'new_price' => $newPrice, 'discount_value' => max($price - $newPrice, 0), 'percent' => $value, 'data' => $coupon, 'for_products' => $forProducts, 'applied_products_array' => $appliedProductsArray];
    }

    public static function payWithPayfort($order, $paymentMethod = 'visa'){
        return \Payfort::redirection()->displayRedirectionPage([
            'command' => 'AUTHORIZATION',              # AUTHORIZATION/PURCHASE according to your operation.
            'merchant_reference' => $order->last_payment_reference_id,   # You reference id for this operation (Order id for example).
            'amount' => $order->total,                           # The operation amount.
            'currency' => env('PAYFORT_CURRENCY', 'USD'),
            'customer_email' => $order->user->email,  # Customer email.
            'merchant_extra1' => $paymentMethod,
        ])->render();
    }

    public static function sumCurrentProductsPriceWithIds($productsIds = [], $cityId = null, $promoCodeText = null, $promoCodeValue = 0, $balanceValue = 0, $vatPercent = null, $deliveryFees = null){
        $products = Product::whereIn('id', array_keys($productsIds))->get();
        $productsDetails = [];
        foreach ($products as $product){
            $productsDetails[] = self::getProductSaleDetails($product, $productsIds[$product->id], $cityId);
        } //endForeach $products
        return self::calculateOrderFinalPrices($productsDetails, $promoCodeText, $promoCodeValue,$balanceValue, $vatPercent, $deliveryFees);
    }

    public static function sumCurrentProductsPriceWithProductsObject($products, $productsIds, $cityId = null, $promoCodeText = null, $promoCodeValue = 0, $balanceValue = 0, $vatPercent = null, $deliveryFees = null){
        $productsDetails = [];
        foreach ($products as $product){
            $productsDetails[] = self::getProductSaleDetails($product, $productsIds[$product->id], $cityId);
        } //endForeach $products
        return self::calculateOrderFinalPrices($productsDetails, $promoCodeText, $promoCodeValue,$balanceValue, $vatPercent, $deliveryFees);
    }

    public static function calculateOrderFinalPrices($productsDetails = [], $promoCodeText = null, $promoCodeValue = 0, $balanceValue = 0, $vatPercent = null, $deliveryFees = null){
        $returnArray = ['productsDetails' => $productsDetails, 'productsPrice' => 0, 'productsPriceAfterDiscount' => 0, 'promoCodeDiscount' => 0, 'promoCodeDiscountPercent' => 0, 'couponData' => null, 'couponProducts' => [],'discountValue' => 0, 'discountPercentage' => 0, 'deliveryFees' => 0, 'taxValue' => 0, 'taxPercent' => 0,'balanceValue' => 0,'finalPrice' => 0];
        if(empty($productsDetails)){
            return $returnArray;
        }

        $productsQuantityAndPriceArray = [];
        $saleSubTotal = 0;
        foreach ($productsDetails as $product){
            if($product['hasOffer'] && $product['offerDetails']['type'] == 'price'){
                $returnArray['discountValue'] += $product['offerDetails']['totalValue'];
            }
            $returnArray['productsPrice'] += $product['price'];
            $productsQuantityAndPriceArray[$product['id']] = [
               'quantity' => $product['quantity'],
               'price' => $product['unitPrice']
            ];
        }

        if($returnArray['discountValue'] > 0 && $returnArray['productsPrice'] > 0){
            $returnArray['discountPercentage'] = ($returnArray['discountValue']/$returnArray['productsPrice'])*100;
        }

        $returnArray['productsPriceAfterDiscount'] = $returnArray['productsPrice'] - $returnArray['discountValue'];
        $balanceValue = min($returnArray['productsPriceAfterDiscount'], $balanceValue);
        $returnArray['finalPrice'] =  $returnArray['productsPriceAfterDiscount'] - $balanceValue;
        $returnArray['balanceValue'] = $balanceValue;
        if($promoCodeText && $promoCodeValue <= 0){
            $promoCodeDetails = self::checkCoupon($promoCodeText, $returnArray['finalPrice'], $productsQuantityAndPriceArray);
            if($promoCodeDetails['success']){
                if(count($promoCodeDetails['applied_products_array']) > 0){
                    $returnArray['couponProducts'] = $promoCodeDetails['applied_products_array'];
                }
                $returnArray['couponData'] = $promoCodeDetails['data'];
                $returnArray['finalPrice'] = $promoCodeDetails['new_price'];
                $returnArray['promoCodeDiscount'] = $promoCodeDetails['discount_value'];
                $returnArray['promoCodeDiscountPercent'] = $promoCodeDetails['percent'];
            }
        }else{
            $returnArray['finalPrice'] -= $promoCodeValue;
            $returnArray['promoCodeDiscount'] = $promoCodeValue;
        }

        $returnArray['taxValue'] = $returnArray['finalPrice'] * (get_setting('tax_percent', 15)/100);
        $returnArray['taxPercent'] = !is_null($vatPercent)?$vatPercent:get_setting('tax_percent', 15);

        if(!is_null($deliveryFees)){
            $returnArray['deliveryFees'] = $deliveryFees;
        }else{
            if($returnArray['finalPrice'] >= (float) get_setting('min_value_to_apply_free_delivery', 0)){
                $returnArray['deliveryFees'] = 0;
            }else{
                $returnArray['deliveryFees'] = (float) get_setting('delivery_fees', 0);
            }
        }
      //  $returnArray['finalPrice'] += $returnArray['deliveryFees'];
       // $returnArray['finalPrice'] += $returnArray['taxValue'];

           return $returnArray;
        }

        public static function getProductSaleDetails($product, $priceAndQuantity,$cityId = null){
            
        $price = (isset($priceAndQuantity['price']) && $priceAndQuantity['price'])?$priceAndQuantity['price']:$product->price;
        $salePrice = $price;
        $hasOldOffer = (isset($priceAndQuantity['has_offer']) && isset($priceAndQuantity['offer']))?true:false;
        $quantity = (int) $priceAndQuantity['quantity'];
        if($hasOldOffer){
            $submittedOffer = $priceAndQuantity['offer'];
            $hasOffer = true;
            $offerDetails = [
                'id' => $submittedOffer['id'],
                'type' => $submittedOffer['type'],
                'expiryAt' => null,
                'take' => (int) $submittedOffer['take'],
                'get' => (int) $submittedOffer['get'],
                'value' => (float) $submittedOffer['discount_value'],
                'totalValue' => (float) $submittedOffer['discount_value']*$quantity,
                'percent' => (float) $submittedOffer['discount_percent'],
                'offerProduct' => $submittedOffer['offer_product'],

            ];

        }else{

             $offer = Offer::whereHas('products', function ($q) use ($product) {
             $q->where('products.id', $product->id);
          });
        
           if($cityId){
                 $offer = $offer->whereHas('cities', function ($q) use ($cityId) {
                 $q->where('cities.id', $cityId);
            });
        }

        $offer = $offer->where('status', 1)->orderBy('id', 'desc')->first();
        $hasOffer = false;
        $offerDetails = ['id' => 0,'type' => null, 'expiryAt' => null, 'take' => 1, 'get' => 0, 'offerProduct' => null, 'value' => 0, 'totalValue' => 0,'percent' => 0];
        if($offer){
            $offerDetails['id'] = (int) $offer->id;
            $offerDetails['type'] = $offer->type;
            $offerDetails['expiryAt'] = $offer->expiry_date;
            switch ($offerDetails['type']){
                case 'price';
                    $offerDetails['percent'] = $offer->percent;
                    if($salePrice > 0 && (float) $offer->percent > 0){
                        $offerDetails['value'] = $salePrice *  ((float) $offer->percent/100);
                    }
                    $salePrice = max($salePrice - $offerDetails['value'], 0);

                    if($offerDetails['value'] > 0){
                        $offerDetails['totalValue'] = $offerDetails['value'] * (int) $priceAndQuantity['quantity'];
                        $hasOffer = true;
                    }
                    break;
                default;
                if($offer->take >= (int) $priceAndQuantity['quantity']){
                    $offerDetails['take'] = $offer->take;
                    $offerDetails['get'] = $offer->get;
                    $offerDetails['offerProduct'] = $offer->offerProduct;
                    $hasOffer = true;
                }
            } //endSwitch $offerDetails['type']
        } //endIf $offer

        }//endIf $hasOldOffer
        return [
            
            'id' => (int) $product->id,
            'title' => $product->title,
            'sku' => $product->sku,
            'inStock' => $product->quantity,
            'quantity' => (int) $priceAndQuantity['quantity'],
            'unitPrice' => (float) $price,
            'saleUnitPrice' => (float) $salePrice,
            'price' => (float) $price*(int) $priceAndQuantity['quantity'],
            'salePrice' => (float) $salePrice*(int) $priceAndQuantity['quantity'],
            'hasOffer' => $hasOffer,
            'offerDetails' => $offerDetails
        ];
    }

    public static function createReceipt($order, $orderPrices)
    {
      
        return OrderReceipt::create([

            'order_id' => $order->id,
            'products_price' => $orderPrices['productsPrice'],
            'offer_discounts' => $orderPrices['discountValue'],
            'coupon_for_products' => !empty($orderPrices['couponProducts']),
            'coupon_discount_value' => $orderPrices['promoCodeDiscount'],
            'coupon_discount_percent' => $orderPrices['promoCodeDiscountPercent'],
            'delivery_fees' => $orderPrices['deliveryFees'],
            'balance_value' => $orderPrices['balanceValue'],
            'vat_value' => $orderPrices['taxValue'],
            'vat_percent' => $orderPrices['taxPercent'],
            'total' => $orderPrices['finalPrice'],

        ]);
    }

    public static function handleProductsRequest($requestProducts)
    {
        $productsArray = [];

        foreach ($requestProducts as $row){
            if(!isset($row['id'])){
                return ['success' => false, 'productsIds' => [], 'message' => 'api.something_wrong', 'error_code' => 30];
            }
            if(!isset($row['quantity'])){
                return ['success' => false, 'productsIds' => [], 'message' => 'api.something_wrong', 'error_code' => 30];
            }
            
            $productsArray[$row['id']] = ['quantity' => $row['quantity']];
         }

            return ['success' => true, 'productsIds' => $productsArray, 'message' => 'success', 'error_code' => null];
       }

         public static function saveProducts($order, $cartItems)
         {
             
            foreach($cartItems as $item){

                $itemProduct = $item->serviceProduct;
                $itemSize= $item->size;
                $itemMatterss= $item->MattressType;
                $Additions = $item->additions;
                $carType =$item->car_type;
                $total_additions = $item->additions->sum('price');
                $productPrice = $itemProduct?(float)$itemProduct->price:0;
                $productsizePrice = $itemSize?(float)$itemSize->size_price:0;
                $productMatterssPrice = $itemMatterss?(float)$itemMatterss->price:0;
                $price = $productPrice + $productsizePrice + $productMatterssPrice + $total_additions;

                OrderProduct::create([

                    'order_id' => $order->id,
                    'product_id' =>  $itemProduct->id,
                    'amount' => $price ,
                    'quantity' => $item->amount,
                    'city_id'=> $order->city_id,
                    'size'=>isset($itemSize) ? $itemSize->size: null ,
                    'mattress'=>isset($itemMatterss)?$itemMatterss->name:null,
                    'car_type'=> $item->car_type 
                
               ]);

                foreach($Additions as $addition)
                {
                  
                    OrderAddition::create([
                        
                        'order_id' => $order->id,
                        'addition_id'=> $addition->addition_id,
                        'name'=> $addition->addition ,
                        'price'=> $addition->price,
                        'service_id'=> $addition->service_id

                 ]);
               } 
             } 

              return true;
       }

       public static function getProducts($order ,$newOrder)
       {
          
            $products = OrderProduct::where('order_id',$order->id)->get();
            $additions = OrderAddition::where('order_id',$order->id)->get();

            foreach ($products as $item){

                 OrderProduct::create([

                    'order_id' => $newOrder->id,
                    'product_id' => $item->product_id,
                    'amount' => $item->amount,
                    'quantity' => $item->quantity,
                    'city_id'=> $order->city_id,
                    'size'=>$item->size,
                    'mattress'=>$item->mattress,
                    'car_type'=> $item->car_type 

               ]);
            }

            foreach($additions as $addition)
            {
                
                OrderAddition::create([

                     'order_id' => $newOrder->id,
                     'addition_id'=> $addition->addition_id,
                     'name'=> $addition->addition ,
                     'price'=> $addition->price,
                     'service_id'=> $addition->service_id
                ]);
            }
            
              return true;
       }
 
     public static function checkProducts($request)
     {
       
        $requestProducts = $request->get('products', []);
        $productsArray = [];

        foreach ($requestProducts as $row){
            if(!isset($row['id'])){
                continue;
            }

            if(!isset($row['quantity'])){
                return ['success' => false, 'message' => 'api.something_wrong', 'error_code' => 30];
             }

             $productsArray[$row['id']]['quantity'] = $row['quantity'];
         }

         $productsData = Product::whereIn('id', array_keys($productsArray))->get();

         $total = 0;

         foreach ($productsData as $product){

            $amount = $productsArray[$product->id]['quantity'] * $product->sale_price;
            $productsArray[$product->id]['amount'] = $amount;
            $total += $amount;
         }

         if($request->promocode){
            $response = Checkout::checkCoupon($request->promocode, $total);
              if($response['success']){
                 $total = $response['new_price'];
             }
         }

          return ['success' => true, 'products' => $productsData, 'total' => $total, 'productsIds' => $productsArray];
      }

     public static function applyPromoCode($orderPrices, $user, $order)
     {
         
           $promoCode = $orderPrices['couponData'];
           $parent = PromoCodeuser::create([
                'user_id' => $user->id,
                'promocode_id' => $promoCode->id,
                'used_at' => $order->created_at,
                'promo_code_type' => 'percent',
                'promo_code_value' => $orderPrices['promoCodeDiscountPercent'],
                'final_amount' => $orderPrices['promoCodeDiscount'],
                'referenceable_id' => $order->id,
                'referenceable_type' => 'orders'
          ]);

              foreach ($orderPrices['couponProducts'] as $key => $product){

                 PromoCodeuser::create([

                    'user_id' => $user->id,
                    'promocode_id' => $promoCode->id,
                    'used_at' => $order->created_at,
                    'promo_code_type' => 'percent',
                    'promo_code_value' => $product['percent'],
                    'final_amount' => $product['value'],
                    'parent_id' => $parent->id,
                    'referenceable_id' => $key,
                    'referenceable_type' => 'products'
              ]);
          }
     }

    public static function emptyAllCarts($user)
    {

       Cart::where('user_id',$user)->delete();

        return true;
    }

    public static function callStcPay($url, $payload, $stageType = 'init'){
        $stcPrefix = 'https://Test.B2B.stcpay.com.sa/';
        $headers = array(
            'X-ClientCode: 61286815613',
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $stcPrefix.$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_VERBOSE => true,
            CURLOPT_SSLCERT => public_path('cert.pem'),
            CURLOPT_SSLCERTPASSWD =>  "MF2030",
        ));

        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if(curl_error($ch))
        {
            throw new \Exception(curl_error($ch));
        }

        curl_close($ch);

         return array('response' => $response?json_decode($response, true):[], 'code' => $responseCode);
    }

    public function confirmStcPay(Request $request)
    {
        /** @var APIOperations $apiOperations */
        $apiOperations = $this->get('api_operations');
        $environment = $this->getParameter('stc_environment');
        $stcPrefix = $this->getParameter('stc_'.$environment.'_url');

        try {
            $url = $stcPrefix."B2B.DirectPayment.WebApi/DirectPayment/V4/DirectPaymentConfirm";
            $stcCurlData = $this->curlStcPay($url, $request->getContent(), 'payment');
        } catch (\Exception $e){
            return $apiOperations->getErrorJsonResponse($e->getMessage());
        }

        return new JsonResponse(json_decode($stcCurlData['response']), $stcCurlData['code']);
    }

    public static function useWallet($price)
    {
        $authUser = auth()->user();
        if($authUser->balance > 0 && $authUser->balance >= get_setting('min_balance_value_to_use', 0) ){
            $balanceValue = $authUser->balance;
            $diff = (float) $price - (float) $balanceValue;
            if($diff >= 0 ){
                $paidWithBalance = (float) $balanceValue;
            }else{
                $paidWithBalance = (float) $price;
            }
            return ['success' => true, 'new_price' => (float) max($diff, 0), 'paid_with_balance' => (float) $paidWithBalance];
         }
           return ['success' => false];
       }

    }
