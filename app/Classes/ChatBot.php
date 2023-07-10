<?php

namespace App\Classes;
use App\Models\ChatBot as ChatBotModel;
use App\Models\Order;
use App\User;

class ChatBot
{
   
    public static function reply($conversation, $user, $reply){

        if($conversation->chat_bot_status == 'finished'){

            return ['success' => true, 'question_id' => null,'status' => 'need_agent_question', 'transfer_to_agent' => true,'message' =>  'لم نفهم إجابتك. هل ترغب بالتحدث لأحد ممثلي خدمة العملاء؟'];
        }elseif ($reply && $conversation->chat_bot_status == 'need_agent_question'){
          
            if(in_array(trim($reply), ['نعم','أجل','الله يعافيك','بالتأكيد','اجل'])){

                return ['success' => true, 'question_id' => null,'status' => 'need_agent_question', 'transfer_to_agent' => true,'message' =>  'جاري تحويلك الى أحد ممثلي خدمة العملاء.'];
            }
            
            return ['success' => true, 'question_id' => null,'status' => 'disabled', 'transfer_to_agent' => true,'message' =>  'شكراُ لك, متواجدون دائماً في خدمتك (جاري إغلاق المحادثة)'];
        }

        if(!$conversation->last_question_id){
//            if(strpos($reply, '%question_title%') !== false){
                $reply = str_replace('%question_title%', '', $reply);
                $replyData = ChatBotModel::where('question_title', $reply)->first();
                if(!$replyData){
                    if(in_array($conversation->chat_bot_status, ['pending'])){
                        return ['success' => false, 'question_id' => null,'status' => 'pending', 'transfer_to_agent' => true,'message' =>  'اختر من القائمة موضوع المحادثة.'];
                    }
                    return ['success' => false, 'question_id' => null,'status' => 'disabled', 'transfer_to_agent' => true,'message' =>  'عفواً لم نفهم رسالتك ... سيتم تحويلك الى احد ممثلي خدمة العملاء.'];
                }
                return ['success' => true, 'question_id' => $replyData->id, 'status' => 'chatting', 'transfer_to_agent' => false,'message' =>  $replyData->question_body];
//            }
//            return ['success' => false, 'question_id' => null,'status' => 'disabled', 'transfer_to_agent' => true,'message' =>  'عفواً لم نفهم رسالتك ... سيتم تحويلك الى احد ممثلي خدمة العملاء.'];
        }

        $replyData = ChatBotModel::find($conversation->last_question_id);
        $replyNumber = self::get_numerics($reply);
        if(!$replyData){
            return ['success' => false, 'question_id' => null,'status' => 'disabled', 'transfer_to_agent' => true,'message' =>  'عفواً لم نفهم رسالتك ... سيتم تحويلك الى احد ممثلي خدمة العملاء.'];
        }

        $order = null;
        if($replyData->input && $replyData->input == 'order_number' && isset($replyNumber[0])){
            $order = Order::where('id', substr($replyNumber[0], 2))->first();
            if(!$order){
                return ['success' => false, 'question_id' => $conversation->last_question_id,'status' => 'chatting', 'transfer_to_agent' => false,'message' =>  'لم يتم العثور على الطلب, تأكد ان الرقم المدخل صحيح.'];
            }
        }
        $agentReply = self::replace_reply_variables($user,$replyData->answer, $order);

        if(!$agentReply){
            return ['success' => false, 'question_id' => null, 'status' => 'disabled', 'transfer_to_agent' => true,'message' =>  'عفواً لم نفهم رسالتك ... سيتم تحويلك الى احد ممثلي خدمة العملاء.'];
        }
         return ['success' => true, 'question_id' => null, 'status' => 'finished', 'transfer_to_agent' => true,'message' =>  $reply];
     }

    public static function get_numerics ($str) {
        preg_match_all('/\d+/', $str, $matches);
        return isset($matches[0])?$matches[0]:0;
    }

    public static function replace_reply_variables ($user,$answer, $order = null) {

        if(!$answer){

            return null;
        }

        if(!$order){
            $order = $user->orders()->orderBy('orders.id', 'desc')->first();
        }

        if (strpos($answer, '{order_number}') !== false) {
            if(!$order){

                return 'لم يتم العثور على طلبات قمت بها مسبقاً';
            }
            
            $answer = str_replace('{order_number}', $order->order_number, $answer);
        }
        if (strpos($answer, '{customer_name}') !== false) {
            $answer = str_replace('{customer_name}', $user->name, $answer);
        }

        if(strpos($answer, '{order_status}') !== false){
            if(!$order){
                return 'لم يتم العثور على طلبات قمت بها مسبقاً';
            }
            $answer = str_replace('{order_status}', trans('admin.orders_status_options.'.$order->status), $answer);
            if($driver= $order->driver){
                $answer .= ' - '.__('trans_admin.chat_bot_driver_message', ['mobile' => $driver->mobile, 'name' => $driver->name]);
            }
        }

        return $answer;
    }

    //order_number, customer_name, driver_name, driver_number, order_status

    }
