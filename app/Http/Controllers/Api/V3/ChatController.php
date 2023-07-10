<?php

namespace App\Http\Controllers\Api\V3;

use App\Classes\ChatBot;
use App\Classes\Operation;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nahid\Talk\Facades\Talk;
use App\Models\ChatBot as ChatBotModel;
use Spatie\MediaLibrary\Models\Media;


class ChatController extends Controller
{

    public function unreadMessageCount(Request $request)
    {
       
        $user = auth()->user();
        $notificationsCount = Operation::userNotificationsCount($user, false, true);
        return $this->sendResponse(['unread' => $notificationsCount['chatMessages']], trans('messages.get_data_success'));

    }

    public function index(Request $request)
    {
       
        $authUser = auth()->user();
        $chats = Conversation::where(function($query) use ($authUser){
            $query->where('user_one', $authUser->id);
            $query->orWhere('user_two', $authUser->id);
        })->orderBy('created_at', 'desc');

        if($request->order_id){
            $chats = $chats->where('order_id', $request->order_id);
        }
        $chats = $chats->with('lastMessage')->get();
        foreach ($chats as $chat){
            $chat->order_id = (int) $chat->order_id;
            $chat->status = (string) $chat->status;
        }
        $chats = $chats->toArray();
        return $this->sendResponse($chats, trans('messages.get_data_success'));
    }
//Get Chat By Order Id or Conversation Id
    public function getChatByDefinitionId(Request $request)
    {
        $order_id = $request->order_id?substr($request->order_id, 2):0;
        $conversation_id = $request->conversation_id?:0;
        $authUser = auth()->user();
        $lastActiveConversation = Conversation::where(function($query) use ($order_id, $conversation_id){
            $query->where('order_id', $order_id);
            $query->orWhere('id', $conversation_id);
        })
            ->where(function($query) use ($authUser){
                $query->where('user_one', $authUser->id);
                $query->orWhere('user_two', $authUser->id);
            })
            ->orderBy('created_at', 'desc')->first();
        Talk::setAuthUserId($authUser->id);
        $conversation_id = null;
        if(!$lastActiveConversation){
            return $this->sendError([], trans('messages.not_found_conversation'), 404);
        }
        $conversation_id = $lastActiveConversation->id;

        $conversation = Talk::getConversationsById($conversation_id, 0, 100000);
        $user = null;
        $messages = [];
        if($conversation) {
            $user = $conversation->withUser;
            $messages = $conversation->messages;
            $lastActiveConversation->messages()->where('user_id', '!=',$authUser->id )->where('is_seen', '!=',1)->update(['is_seen' => 1]);
        }
        if (!is_array($messages)) {
            foreach ($messages as $msg){
                $media = Media::where('collection_name', 'chat_message_images')->where('model_id', $msg->id)->get();
                $images = [];
                foreach ($media as $image){
                    $images[] = (string)$image->getFullUrl();
                }

                $msg->conversation_id = (string) $msg->conversation_id;
                $msg->user_id = (string) $msg->user_id;
                $msg->images = $images;
            }

              $messages = $messages->sortBy('id');
        }

        $show_conversation = true;
        $talk__appKey = config('talk.broadcast.pusher.app_key');
        $talk__appName = config('talk.broadcast.app_name');
        $talk_conversation_channel = sha1($talk__appName.'-conversation-'.$conversation_id);

        return $this->sendResponse([

            'hide_rate' => (boolean) $lastActiveConversation->hide_rate,
            'conversation_id' => (int) $conversation_id,
            'conversation_title' => (string) $lastActiveConversation->title,
            'channel_id' => (string) $talk_conversation_channel,
            'conversation_status' => (string) $lastActiveConversation->status,
            'show_chat_bot_questions' => in_array($lastActiveConversation->chat_bot_status, ['pending']),
            'agent_name' => (string) 'الدعم الفني',
            'messages' => $messages
            
            ], __('messages.get_data_success'));
    }

    public function sendMessageForTest(Request $request)
    {
       
        Talk::setAuthUserId($request->auth_user_id);
        $body = $request->input('message');

        if ($message = Talk::sendMessage($request->conversation_id, $body)) {

              return response()->json([

                   'status' => 442,
                   'success' => false,
                   'message_text' => $message
             ]);
         }

          return response()->json([

             'status' => 442,
             'success' => false,
             'message' => 'Cannot send this message'
            
         ]);
    }

    public function chatBotQuestions(Request $request)
    {
        
        $questions = ChatBotModel::where('status', 1)->get();
        $questionsArray = [];
        foreach ($questions as $row){
            $questionsArray[] = [
                 'id' => (int) $row->id,
                 'title' => (string) $row->question_title,
             ];
        }

        return $this->sendResponse($questionsArray, 'الأسئلة');
    }

    public function sendMessage(Request $request)
    {
        
         $validator = Validator::make($request->all(), [

             'message'=>'required',
         ]);

         if ($validator->fails()) {

             return $this->sendError(error_processor($validator), trans('messages.validation_error'), 442);
         }
        
        $authUser = auth()->user();
        $conversation_id = $request->conversation_id?:0;
        $order_id = $request->order_id?substr($request->order_id, 2):0;
        if($conversation_id){
            $conversation = Conversation::where(function($query) use ($order_id, $conversation_id){
                $query->where('id', $conversation_id);
                $query->orWhere('order_id', $order_id);
            })->where(function($query) use ($authUser){
                $query->where('user_one', $authUser->id);
                $query->orWhere('user_two', $authUser->id);
            })->first();
            
        }else{
            
            $agentData = $this->get_available_support_user($request);
            $conversation = Conversation::create(['user_one' => $authUser->id, 'user_two' => $agentData['agent_id'], 'status' => 1, 'order_id' => $request->order_id?substr($request->order_id, 2):null, 'title' => $request->title]);
        }

        $chatBotStatus = $conversation->chat_bot_status;

        if(!$conversation){

            return $this->sendError([], trans('messages.not_found_conversation'), 404);
        }

        if($conversation->status != 1){

            return $this->sendError([], trans('عفواً! هذه المحادثة تم اغلاقها.'), 404);
        }

        Talk::setAuthUserId($authUser->id);
        $body = $request->input('message');

        if ($message = Talk::sendMessage($conversation->id, $body)) {
            $postImages = $request->images?:[];
            if(!empty($postImages)){
                $messageData = Message::find($message->id);
                foreach ($postImages as $image){
                    $messageData->addMedia($image)->toMediaCollection($messageData->galleryMediaCollection);
                }
            }
            if($conversation->chat_bot_status != 'disabled'){
                $chatBotReply = ChatBot::reply($conversation, $authUser, $body);
                $closeConversation = $chatBotReply['status'] == 'close_conversation';
                $chatBotStatus = $chatBotReply['status'];
                if($chatBotReply['success']){

                    $conversation->update(['last_question_id' => $chatBotReply['question_id'], 'chat_bot_status' => $chatBotReply['status'], 'chat_bot_errors_count' => 0, 'status' => $closeConversation?0:1]);
                
                }else{

                    $conversation->update(['last_question_id' => $chatBotReply['question_id'], 'chat_bot_status' => $chatBotReply['status'], 'chat_bot_errors_count' => 1, 'status' => $closeConversation?0:1]);
                }
                
                $conversation->status = $closeConversation?0:1;
                Talk::setAuthUserId($conversation->user_two);
                Talk::sendMessage($conversation->id, $chatBotReply['message']);
            }

            $talk__appKey = config('talk.broadcast.pusher.app_key');
            $talk__appName = config('talk.broadcast.app_name');
            $talk_conversation_channel = sha1($talk__appName.'-conversation-'.$conversation->id);

            return $this->sendResponse([

                'conversation_id' => (int)$conversation->id,
                'conversation_title' => (string)$conversation->title,
                'channel_id' => (string)$talk_conversation_channel,
                'conversation_status' => (int)$conversation->status,
                'show_chat_bot_questions' => in_array($chatBotStatus, ['pending']),
                'agent_name' => (string)'الدعم الفني'
            ], 'تم الإرسال بنجاح');

        }

        return $this->sendError([], 'عفواً! تعذر الإرسال.', 442);
    }

    public function changeConversationStatus(Request $request)
    {
       
        $rules = [

            'conversation_id'=>'required|integer',
            'status' => 'required|integer',
        ];
        
        $this->validate($request, $rules);
        $authUser = auth()->user();
        $conversation = Conversation::where(function($query) use ($authUser){
            $query->where('user_one', $authUser->id);
            $query->orWhere('user_two', $authUser->id);
        })->findOrFail($request->conversation_id);

        $conversation->update(['status' => (int) $request->status]);

        $talk__appKey = config('talk.broadcast.pusher.app_key');
        $talk__appName = config('talk.broadcast.app_name');
        $talk_conversation_channel = sha1($talk__appName.'-conversation-'.$conversation->id);

        return $this->sendResponse(['conversation_status' => (int) $conversation->status, 'channel_id' => $talk_conversation_channel], 'عفواً! تعذر الإرسال.');
    }

    public function conversationStatus(Request $request)
    {
        
        $rules = [
            
            'conversation_id'=>'required',
        ];

        $this->validate($request, $rules);

        $conversation = Conversation::findOrFail($request->conversation_id);
        $talk__appKey = config('talk.broadcast.pusher.app_key');
        $talk__appName = config('talk.broadcast.app_name');
        $talk_conversation_channel = sha1($talk__appName.'-conversation-'.$conversation->id);

        return $this->sendResponse(['conversation_status' => (int) $conversation->status, 'channel_id' => $talk_conversation_channel], 'تم تغيير حالة الشات');
    }

    public function get_available_support_user($request)
    {
        
          $userData = User::find(2);
//        $userData = User::where('support_chat_status', 'available')->inRandomOrder()->first();
//        if(!$userData){
//            $adminUser = User::whereHas('roles', function ($query){
//                $query->where('is_admin', true);
//            })->inRandomOrder()->first();
//            if(!$adminUser){
//                $userArray = ['status' => 0,'id' => 0, 'nick_name' => 'الدعم الفني', 'in_queue' => 5, 'message' => 'Unavailable'];
//                return response()->json($userArray);
//            }
//            $userData = $adminUser;
//        }
        return ['status' => 1,'agent_id' => $userData->id, 'nick_name' => 'الدعم الفني', 'in_queue' => 5, 'message' => 'Available'];
    }


    public function available_support_user(Request $request)
    {
       
        $userArray = $this->get_available_support_user($request);

        return $this->sendResponse($userArray, __('messages.get_data_success'));
    }

    public function set_chat_status(Request $request, $status = 'offline')
    {
       
        $user = User::find(auth()->id());

        if(!$user){

            return $this->sendError([], trans('messages.something_went_wrong'), 442);
        }

        $user->update(['chat_status' => $status]);

        return $this->sendResponse([],'Conversation status turned into '.$status);
    }

    public function rateConversation(Request $request)
    {
        
        $authUser = auth()->user();
        $conversation = Conversation::where(function($query) use ($authUser, $request){
            $query->where('user_one', $authUser->id);
            $query->orWhere('user_two', $authUser->id);
        })->where('id', (int) $request->conversation_id)->first();

        if(!$conversation){

            return $this->sendError([], trans('messages.not_found_data'), 404);
        }

        $rating = (int) $request->rating > 0? (int) $request->rating:1;
        $conversation->update(['hide_rate' => true, 'rating' => $rating]);

        return $this->sendResponse([],'messages.updated_success');
    }

    public function getOrdersWithoutChats(Request $request)
    {
       
        $ordersIds = Order::notPending()->where('user_id', auth()->id())->whereDoesntHave('conversations', function ($q){
            $q->where('status', 1);
        })->orderBy('orders.id', 'desc')->pluck('orders.id')->toArray();
        $orderNumbers = [];
        foreach ($ordersIds as $id){
            $orderNumbers[] = intval('99'.$id);
        }
        
        return $this->sendResponse($orderNumbers,'messages.get_data_success');
    }
}
