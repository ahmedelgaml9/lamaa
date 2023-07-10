<?php

/**
 * Created by Mohammed Zidan.
 * Date: 8/16/18
 * Time: 9:08 AM
 */

namespace App\Http\Controllers\Dashboard;
use App\Models\ChatBot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ChatbotController extends Controller
{

    public function index()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.chatbot_answers');
        $data['data'] = ChatBot::whereNotNull('id')->orderBy('sort','asc')->paginate(20);

        return view('dashboard.chatbot.index', $data);
    }

    public function create()
    {
        $data['breadcrumb'] = [
            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.chatbot_answers'), 'url' => route('banks.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.chatbot_answers');

        $data['data'] = new ChatBot;

        $lastAnswer = ChatBot::orderBy('id', 'desc')->first();

        $data['lastSort'] = (int)($lastAnswer?$lastAnswer->sort:1) + 1;

        return view('dashboard.chatbot.create_edit', $data);
    }


    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), (new ChatBot)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        ChatBot::create($data);

        return  redirect()->route('chatbot.answers.index')->with('success','تم لانشاء بنجاج');

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.chatbot_answers'), 'url' => route('banks.index')],
            ['name' => trans('admin.edit'), 'url' => null]
        ];

        $data['page_title'] = trans('admin.chatbot_answers');
        $data['data'] = ChatBot::findOrFail($id);
        $data['lastSort'] = (int) $data['data']->sort;

        return view('dashboard.chatbot.create_edit', $data);
    }


    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), (new ChatBot)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        
        }

        $data = $request->all();
        $chatbot = Chatbot::findOrFail($id);
        $chatbot->update($data);

        return  redirect()->route('chatbot.answers.index')->with('success','تم التعديل بنجاح');
    }


    public function destroy($id)
    {
        //
    }
}
