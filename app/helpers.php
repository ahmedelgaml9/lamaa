<?php

use App\Models\Order;
use App\Models\Settings;


if (!function_exists('defaultImageUrl')) {
    function defaultImageUrl($type = 'default')
    {
        return asset('/dashboard/dist/assets/media/avatars/blank.png');
    }
}


if (!function_exists('randomBootstrapColorsLabel')) {
    function randomBootstrapColorsLabel($number = null)
    {
        $arr = array("success", "danger", "warning", "primary", "default");
        $key = array_rand($arr);
        return $arr[$key];
    }
}

if (!function_exists('show_status')) {

    function show_status($value)
    {
      
        if($value == 0)

         return "غير نشط";

       elseif($value == 1)

          return "نشط";
    }
}


if (!function_exists('show_order_status')) {
    
    function show_order_status($value)
    {
       
        if($value == 0)

       return "جديد"; 

       elseif($value == 1)

        return "قيد التنفيذ";

        elseif($value == 2)

        return "تمت الجدولة";
        
        elseif($value == 3)

        return "جارى التوصيل";

        elseif($value == 4)

        return "تم التسليم";

        elseif($value == 5)

        return "ملغى";

        elseif($value == 7)

        return "إعادة الجدولة";

        elseif($value == 8)

        return "مكتمل";
      
    }
}


if (!function_exists('get_setting')) {

    function get_setting($key, $default = null,$lang = false)
    {
        $settings = Cache::remember('settings', 86400, function(){
            return Settings::all();
        });

        if($lang == false){
            $setting = $settings->where('key', $key)->first();
        }else{
            $setting = $settings->where('key', $key)->where('lang',$lang)->first();
            $setting = !$setting ? $settings->where('type', $key)->first() : $setting;
        }
        return $setting == null ? $default : $setting->value;
    }
}

if (!function_exists('customerStatusLabels')) {
    function customerStatusLabels($status = 'inactive', $html = true)
    {
        if(!$html){
            
            return __('trans_admin.customer_status_options.'.$status);
        }

        switch ($status) {
            case 'active':
                $classLabel = "badge-light-primary";
                break;
            case 'pending':
                $classLabel = "badge-light-dark";
                break;
            case 'before_pending':
                $classLabel = "badge-light-default";
                break;
            case 'special_active':
                $classLabel = "badge-light-success";
                break;
            case 'special_pending':
                $classLabel = "badge-light-warning";
                break;
            default:
                $classLabel = "badge-light-danger";
        }
        return '<div class="badge '.$classLabel.' fw-bolder">'.__('trans_admin.customer_status_options.'.$status).'</div>';
    }
}

if (!function_exists('customerStatusPoint')) {

    function customerStatusClass($status = 0)
    {
        switch ($status) {
            case 1:
                $classLabel = "success";
                break;
            default:
                $classLabel = "danger";
        }
        return $classLabel;
    }
}

if (!function_exists('error_processor')) {
  function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }
}

if (!function_exists('SettingOption')) {
    function SettingOption($status = 0, $default = null)
    {
      return 1;
    }
}
if (!function_exists('intStatusClassLabels')) {

    function intStatusClassLabels($status = 0)
    {

        switch ($status) {
            case 1:
                $classLabel = "success";
                break;
            default:
                $classLabel = "danger";
        }
        return $classLabel;
    }
}


if (!function_exists('orderStatusLabel')) {
    function orderStatusLabel($status = 0, $html = true)
    {
        if(!$html){
            return __('admin.orders_status_options.'.$status);
        }

        switch ($status) {
            case 0:
                $classLabel = "dark";
                break;
            case 1:
                $classLabel = "warning";
                break;
            case 2:
                $classLabel = "info";
                break;
            case 3:
                $classLabel = "primary";
                break;
            case 4:
                $classLabel = "success";
                break;
            case 5:
                $classLabel = "danger";
                break;
                case 11:
                $classLabel = "danger";
                 break;
            default:
                $classLabel = "dark";
        }
        return '<div class="badge badge-light-'.$classLabel.' fw-bolder">'.__('admin.orders_status_options.'.$status).'</div>';
    }
}


if (!function_exists('intStatusLabel')) {

    function intStatusLabel($status = 0, $html = true, $yesOrNo = false)
    {
        if(!$html){

            return __('admin.int_status_options.'.$status);
        }

        switch ($status) {
            case 1:
                $classLabel = "badge-light-success";
            break;
            default:
                $classLabel = "badge-light-danger";
        }
        $trans = __('admin.int_status_options.'.$status);
        if($yesOrNo){
            $trans = __('admin.int_status_options_yes_no.'.$status);
        }
        return '<div class="badge '.$classLabel.' fw-bolder">'.$trans.'</div>';
    }
}

if (!function_exists('supportedLanguages')) {
    function supportedLanguages()
    {
        $languages = ['ar' => 'العربية'];
       return $languages;
    }
}

if (!function_exists('sendMobileNotification')) {
    function sendMobileNotification($content, $usersIds = [], $sendToAll = false)
    {
        if ($sendToAll) {
            \OneSignal::sendNotificationToAll($content);
            return true;
        }

        if (!empty($usersIds)) {
            $params = [];
            $params['include_player_ids'] = $usersIds;
            $contents = [
                "en" => $content,
            ];
            $params['contents'] = $contents;

            \OneSignal::sendNotificationCustom($params);
            return true;

        }
        return false;
    }
}

if (!function_exists('sendSMSNotification')) {
    function sendSMSNotification($content, $usersIds = [], $sendToAll = false)
    {

    }

}


if (!function_exists('orderStatusesList')) {

    function orderStatusesList()
    {
        $statuses = [];
        foreach (Order::ACTIVE_ORDER_STATUS_LIST as $status){

            $statuses[$status] = __('admin.orders_status_options.'.$status);
        }

        return $statuses;
    }
}

if (!function_exists('customerStatusesList')) {
    function customerStatusesList()
    {
        $statuses = [];

        foreach (\App\User::CUSTOMER_STATUSES_LIST as $status){
            $statuses[$status] = __('trans_admin.customer_status_options.'.$status);
        }

        return $statuses;
    }
}

if (!function_exists('customerCartRates')) {
    function customerCartRates()
    {
        return \App\User::CUSTOMER_CART_RATES;
    }
}

if (!function_exists('paymentMethodsList')) {
    function paymentMethodsList()
    {

        return \App\Models\PaymentMethod::where('status', 1)->pluck('name','id')->toArray();
    }
}

if (!function_exists('citiesList')) {
    function citiesList()
    {
        return \App\Models\City::where('active', 1)->pluck('name','id')->toArray();
    }

    function sendNewNotification($content, $usersIds = [], $sendToAll = false)
    {
         if($sendToAll){

            \OneSignal::sendNotificationToAll($content);

            return true;
        
         }
    
        if(!empty($usersIds)){

            $params = [];
            $params['include_player_ids'] = $usersIds;
            $contents = [
            "en" => $content,
            ];
            $params['contents'] = $contents;
    
            \OneSignal::sendNotificationCustom($params);
            
            return true;
        }
           return false;  
    }

}