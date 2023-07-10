<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Operation;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nahid\Talk\Facades\Talk;
use Illuminate\Support\Facades\View;

class ChatController extends Controller
{
    
    protected $authUser;
    
    public function __construct()
    {
        
        $data = request()->get('filter', []);
        $conversations = Conversation::query();
        if($orderId = request()->get('order_id')){
            $conversations = $conversations->where('order_id', $orderId);
        }
        if(request()->get('customer_id')){
            $conversations = $conversations->whereHas('authCustomer', function ($q) use ($data) {
                   $q->where('users.id', request()->get('customer_id'));
            });
        }
        if(!is_null(request()->get('status'))){
            $status = request()->get('status');
            $conversations = $conversations->where('status', (int) $status);
        }

        if(!empty($data)){

             if(isset($data['order_number'])){
                $orderNumber = $data['order_number'];
                $conversations = $conversations->where('order_id', substr($orderNumber, 2));
            }

            if(isset($data['customer_mobile']) || isset($data['customer_name']) || request()->get('customer_id')){
                $conversations = $conversations->whereHas('authCustomer', function ($q) use ($data) {
                    if($data['customer_mobile']){
                        $q = $q->where('mobile', $data['customer_mobile']);
                    }
                    if($data['customer_name']){
                        $q = $q->where('name', $data['customer_name']);
                    }
                    if(request()->get('customer_id')){
                        $q = $q->where('users.id', request()->get('customer_id'));
                    }
                });
            }
            if(isset($data['status']) && !is_null($data['status'])){
                $conversations = $conversations->where('status', (int) $data['status']);
            }
            if(isset($data['action']) && $data['action']){
                $conversations = $conversations->where('action', $data['action']);
            }

            if(isset($data['action']) && $data['city']){
                $cityId = $data['city'];
                $conversations = $conversations->whereHas('authCustomer', function ($q) use ($cityId, $data) {
                    $q->whereHas('addresses', function ($qC) use ($cityId) {
                        $qC->where('customer_shipping_addresses.city_id', $cityId);
                    });
                });
            }
        }

        $conversations = $conversations->orderBy('conversations.id', 'desc')->paginate(20);
        $chatsCounts = ['opened' => Conversation::where('status', 1)->count(), 'closed' => Conversation::where('status', 0)->count()];

        View::composer('dashboard.messenger.partials.peoplelist', function($view) use ($conversations, $chatsCounts) {
            $threads = Talk::threads(); //Users who made conversions with auth user
            $view->with(compact('conversations', 'chatsCounts'));
        });
//        parent::__construct();
    }

    public function index()
    {
        $user = auth()->user();
        Talk::setAuthUserId($user->id);
        $show_conversation = false;
        $conversations = null;
        $messages = [];

        if (count($messages) > 0) {
            $messages = $messages->sortBy('id');
        }
        return view('dashboard.messenger.messages.conversations', compact('messages', 'user', 'show_conversation'));
    }

    public function setAction(Request $request, $conversation_id)
    {
        
        $conversation = Conversation::findOrFail($conversation_id);
        $data = $request->get('actions', []);
        if($data['action'] == 'closed'){
            $data['status'] = 0;
        }else{
            $data['status'] = 1;
        }
        $conversation->update($data);

        return redirect()->back();
    }
    public function closeConversation(Request $request, $conversation_id)
    {
        $conversation = Conversation::findOrFail($conversation_id);
        $authUser = auth()->user();
//        if(!Talk::isAuthenticUser($conversation_id, $authUser->id)){
//            //TODO >> translate this message
//            return responder()->error(403, 'عفواً! ليس لديك الصلاحية لغلق هذه المحادثة.');
//        }
        if($authUser->id != $conversation->user_two){
            return redirect()->back();
        }
        $conversation->update(['status' => (int) $request->get('conversation_status')]);
        return redirect()->back();
        return redirect()->route('message.index');

    }

    public function chatHistory(Request $request, $conversation_id)
    {
//        $authUser = auth()->user();
//        Talk::setAuthUserId($authUser->id);
        $conversationData = Conversation::findOrFail($conversation_id);
        Talk::setAuthUserId($conversationData->user_two);
        $authUser = User::findOrFail($conversationData->user_two);
        $conversation = $conversationData;
        $user = null;
        $messages = [];
        if(!$conversation) {
            abort(404);
        } else {
            $user = $conversation->authCustomer;
            $messages = $conversation->messages()->orderBy('id', 'asc')->get();
            $conversationData->messages()->where('user_id', '!=', $authUser->id)->where('is_seen', 0)->update(['is_seen' => 1]);
        }


        if (!is_array($messages)) {
            $messages = $messages->sortBy('id');
        }
        $show_conversation = true;
        return view('dashboard.messenger.messages.conversations', compact('messages', 'user', 'show_conversation', 'authUser', 'conversation', 'conversation_id', 'conversationData'));
    }


    public function ajaxSendMessage(Request $request)
    {
        $authUser = auth()->user();
        Talk::setAuthUserId($authUser->id);
        $conversation = Conversation::find($request->input('_id'));
        if(!$conversation){
            return response()->json(['status'=>false, 'html'=>'', 'msg' => 'Something get wrong.','refreshPage' => true], 200);
        }

        if ($request->ajax()) {
            if((int)$request->input('reassignment') > 0){
                $rules = [
                    '_id'=>'required'
                ];
                $this->validate($request, $rules);
                $conversation->update(['user_two' => $authUser->id]);
                Message::create(['message' => '', 'reassignment_message' => 1, 'deleted_from_sender' => 1, 'user_id' => $authUser->id, 'conversation_id' => $conversation->id, 'is_seen' => 1]);
                return response()->json(['status'=>true, 'html'=>'', 'msg' => 'Success','refreshPage' => true], 200);
            }

             $rules = [
                 'message-data'=>'required',
                 '_id'=>'required'
             ];

             $this->validate($request, $rules);

            if($conversation->user_two != $authUser->id){
                return response()->json(['status'=>false, 'html'=>'', 'msg' => 'تم إعادة اسناد المحادثة الى موظف آخر. يرجى إعادة تحميل صفحة الشات.','refreshPage' => true], 200);
            }

            $body = $request->input('message-data');
            $conversationId = $request->input('_id');

            if ($message = Talk::sendMessage($conversationId, $body)) {

                Message::where('user_id', '!=', $authUser->id)->where('conversation_id',$conversationId)->where('is_seen', 0)->update(['is_seen' => 1]);

                $sender = $message->sender->toArray();
                $conversation = $message->conversation;
                $messageArray = $message->toArray();
                $messageArray['sender'] = $sender;
                $receivers =   User::where(function($query) use ($conversation, $authUser){
                    $query->where('id', $conversation->user_two);
                    $query->orWhere('id', $conversation->user_one);
                })->where('id', '!=', $authUser->id)->get();

                $html = view('dashboard.messenger.ajax.newMessageHtml', compact('message', 'authUser'))->render();
               
                return response()->json(['status'=>true, 'html'=>$html, 'msg' => 'Success','refreshPage' => false], 200);
            }
        }
    }


    
    public function ajaxDeleteMessage(Request $request, $id)
    {
        $authUser = auth()->user();
        Talk::setAuthUserId($authUser->id);
        if ($request->ajax()) {
            if(Talk::deleteMessage($id)) {
                return response()->json(['status'=>'success'], 200);
            }

            return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
        }
    }

    public function tests()
    {
        dd(Talk::channel());
    }
}
