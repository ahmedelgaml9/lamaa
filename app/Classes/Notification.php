<?php


namespace App\Classes;

use App\Models\Notification as NotificationModel;
use App\Models\NotificationSubscription;
use App\Models\NotificationTemplate;
use App\Models\UserNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Notification
{

    public static function replaceNotificationsTemplateVariables($content, $user = null, $order = null)
    {
        if ($order && (strpos($content, '{order_number}') !== false || strpos($content, '{order_status}'))) {
            $content = str_replace('{order_number}', $order->order_number, $content);
            $content = str_replace('{order_status}', trans('admin.orders_status_options.'.$order->status), $content);

        }
        
        if ($user && strpos($content, '{customer_name}') !== false) {
            $content = str_replace('{customer_name}', $user->name, $content);
        }

        $driver = $order->driver;
        if ($order && $driver && (strpos($content, '{driver_name}') !== false || strpos($content, '{driver_number}') !== false)) {
            $content = str_replace('{driver_name}', $driver->name, $content);
            $content = str_replace('{driver_number}', $driver->mobile, $content);
        }

        return $content;
        
    }

    public static function sendOneNotificationByStatus($status, $user, $order = null, $product = null){
       
        $template = NotificationTemplate::where('slug', $status)->first();
        if(!$template){
            return false;
        }
        if(!$template->title || !$template->content){
            return false;
        }
        $title = self::replaceNotificationsTemplateVariables($template->title, $user, $order);
        $content = self::replaceNotificationsTemplateVariables($template->content, $user, $order);
        $notificationSubscriptions = NotificationSubscription::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        $usersIds = [];
        foreach ($notificationSubscriptions as $sub){
            
            $usersIds[] = $sub->player_id;
        }
        
        $options=['type' =>'order','id' =>(int)$order->order_number];
        
        if(!empty($usersIds)){

            $notification = NotificationModel::create([

                'group' => 'private',
                'title' => ['ar' => $title],
                'content' => $content,
                'options'=>json_encode($options),
                'status' => 1,
                'send_at' => Carbon::now()
                
            ]);

            UserNotification::create(['user_id' => $user->id, 'notification_id' => $notification->id, 'status' => 1]);
        }

         return self::sendNewNotification($content, $title, $usersIds);
    }

    public static function sendMultiNotification($notificationData){

        $sentUsersIds = $notificationData->users()->wherePivot('status', 1)->pluck('users.id')->toArray();
        $usersIds = [];
//        if ($notificationData->group == 'auth_users'){
//            $usersIds = User::whereNotIn('id', $sentUsersIds)->orderBy('id', 'DESC')->take(300)->pluck('id')->toArray();
//            $playerIds = NotificationSubscription::whereIn('user_id', $usersIds)->pluck('player_id')->toArray();
//        }elseif ($notificationData->group == 'select_user'){
//            $usersIds = $notificationData->users()->orderBy('users.id', 'DESC')->wherePivot('status', 0)->take(300)->pluck('users.id')->toArray();
//            $playerIds = NotificationSubscription::whereIn('user_id', $usersIds)->pluck('player_id')->toArray();
//        }elseif ($notificationData->group == 'guests'){
//            $playerIds = NotificationSubscription::whereNotIn('user_id', $sentUsersIds)->pluck('player_id')->toArray();
//        }else{
//            $usersIds = User::whereNotIn('id', $sentUsersIds)->orderBy('id', 'DESC')->take(300)->pluck('id')->toArray();
//            $playerIds = NotificationSubscription::whereIn('user_id', $usersIds)->pluck('player_id')->toArray();
//        }

        $sendToAll = false;
        if ($notificationData->group == 'auth_users'){
            $playerIds = NotificationSubscription::whereNotNull('user_id')->pluck('player_id')->toArray();
        }elseif ($notificationData->group == 'select_user'){
            $usersIds = $notificationData->users()->orderBy('users.id', 'DESC')->pluck('users.id')->toArray();
            $playerIds = NotificationSubscription::whereIn('user_id', $usersIds)->pluck('player_id')->toArray();
        }elseif ($notificationData->group == 'guests'){
            $playerIds = NotificationSubscription::where('user_id', null)->pluck('player_id')->toArray();
        }else{
            
            $playerIds = [];
            $sendToAll = true;
        }
        
         $response = self::sendNewNotification($notificationData->content, $notificationData->title, $playerIds , $sendToAll, $notificationData->photo);
      
        if($response){
            
            $notificationData->update(['status' => 1]);
        }
        
        return true;
    }

    public static function sendNewNotification($content, $title = null, $usersIds = [], $sendToAll = false, $image = null )
    {
        if(empty($usersIds) && !$sendToAll){
            return true;
        }

        $params = [
            'api_key' => env('ONESIGNAL_REST_API_KEY')
        ];
        
        if(!$sendToAll){
            $params['include_player_ids'] = $usersIds;
        }else{
            
            $params['included_segments'] = array('All');
        }

            $contents = [
                
                "en" => $content,
            ];
            
            $params['contents'] =$contents;

            if($title){
                
                $titles = [
                    
                    "en" => $title,
                ];
                
                $params['headings'] = $titles;
            }

            if($image){
                $params = array_merge($params, [
                    'big_picture' => $image,
                    'ios_attachments' => [
                        "id" => $image
                    ],
                    'ios_badgeType'  => 'Increase',
                    'ios_badgeCount' => 1,
                ]);
            }
            
            \OneSignal::sendNotificationCustom($params);
            
           return true;
    }
    

}
