<?php


namespace App\Classes;

use App\Models\Audit;
use App\Models\City;
use App\Models\Message;
use App\Models\NotificationSubscription;
use App\Models\Order;
use App\Models\Template;
use App\Models\UserBalance;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Support\Str;

class Operation
{
  
    public static function userNotificationsCount($user,$ordersUpdates = true, $chatMessages = true)
    {
       
        $ordersCount = 0;
        $msgCount = 0;
        if($ordersUpdates){
            $ordersCount = Order::where('user_id', $user->id)->where('status_updates_count', '>',0)->count();
        }
        if($chatMessages){
            $msgCount = Message::whereHas('conversation',function ($q) use ($user) {
                $q->where('user_one', $user->id)->orWhere('user_two', $user->id);
            })->where('user_id','!=',$user->id)->where('is_seen', 0)->count();
        }
        return ['ordersCount' => (int) $ordersCount, 'chatMessages' => (int) $msgCount];
    }

    function getCityNameByCoordinates($lat , $long)
    {
        
        $ch = curl_init();
        $headers = array(

            'Accept: application/json',
            'Content-Type: application/json',
        );

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&sensor=true&key=AIzaSyDLrCll-mkZYtb9prkvR8cUYXfBK3dormA&language=ar';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $responseJson = curl_exec($ch);
        $response = json_decode($responseJson);

        $results = $response->results[0]->address_components;
        $cityName = null;
        foreach ($results as $row){
            $types = isset($row->types)?$row->types:[];
            foreach ($types as $key => $value){
                if($value == 'administrative_area_level_2'){
                    $cityName = $row->long_name;
                }
            }
        }

        $slug = Str::slug($cityName);
        $cityNameArray['name'] = ['ar' => $cityName, 'en' => ucfirst(str_replace('-', ' ', $slug))];

        return City::updateOrCreate(['slug' => $slug], ['country_code' => 'SA', 'slug' => $slug, 'name' => $cityNameArray]);
    }

    public static function updateUserBalance($user, $value, $operation, $type = 'cashback',$orderId = null, $expiryDate = null, $reasonNote = null, $createdBy = null,$status = 1){
        
        $userBalanceObj = UserBalance::create(['user_id' => $user->id, 'operation_type' => $operation, 'order_id' => $orderId, 'type' => $type, 'value' => $value, 'reason_note' => $reasonNote, 'expiry_date' => $expiryDate, 'created_by' => $createdBy, 'status' => $status]);
        $plusBalance = $user->balances()->where('operation_type', 'plus')->sum('value');
        $minusBalance = $user->balances()->where('operation_type', 'minus')->sum('value');

        $user->balance = max((float) $plusBalance - (float) $minusBalance, 0);
        $user->save();
        return ['success' => true, 'newBalanceValue' => $user->balance, 'userBalanceObj' => $userBalanceObj];
    }

    public static function countOrdersByDeliveryShifts($date, $start, $end)
    {
        
        return Order::whereNotIn('status', [Order::STATUS_CANCELED])

            ->where('delivery_date', $date)
            ->where('delivery_start_time', '>=', $start)->where('delivery_end_time', '<=', $start)
            ->where('delivery_end_time', '<=', $end)->where('delivery_start_time', '>=', $end)
            ->count();
    }
    
     public static function orderLog($order, $oldValue = [], $newValue = [])
     {
          
             $data = [

                'user_id' => auth()->id(),
                'event' => 'updated',
                'auditable_type' => 'orders',
                'auditable_id' => $order->id,
                'old_values' => json_encode($oldValue),
                'new_values' => json_encode($newValue),
                'url' => request()->fullUrl(),
                'ip_address' =>  request()->method(),
                'user_agent' => request()->header('user-agent'),
                'tags' => null,
              ];

             return Audit::create($data);
      }  

      public static function sendSMS($mobile, $content){

             $appSid = env('UNIFONIC_APPSID');
             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL, 'http://basic.unifonic.com/rest/SMS/messages');
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
             curl_setopt($ch, CURLOPT_HEADER, FALSE);
             curl_setopt($ch, CURLOPT_POST, TRUE);
             curl_setopt($ch, CURLOPT_POSTFIELDS,
                 "AppSid=".$appSid."&Recipient=966".$mobile."&Body=".$content."&SenderID=MAWARED");
             curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                 "Content-Type: application/x-www-form-urlencoded"
             ));
 
             $result = curl_exec($ch);
              curl_close($ch);
              return json_decode($result);
      }

      public static function createInvoiceQrCode($total, $vat, $createdAt){
 
         $index=1;
         $string ="";
         $array_tag = [

            'company_name' => "",
            'vat_reference' => "300185066300003",
            'timestamp' => $createdAt->format('Y-m-d H:i:s'),
            'total' => $total,
            'vat' => $vat
            
         ];

          foreach($array_tag as $tagVal){
            $string.=pack("H*", sprintf("%02X",(string) $index)).
                pack("H*", sprintf("%02X",strlen((string) $tagVal))).
                (string) $tagVal;
            $index++;
        }

        $base64_encode = base64_encode($string);

        return '<img style="width: 130px" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.$base64_encode.'&choe=UTF-8" title="موارد الحياة" />';
    }

    public static function canCancelOrder($order){

        if(in_array($order->status, json_decode(get_setting('statuses_available_to_cancel', '{}')))){
         
            return true;
        }
         return false;
     }

}
