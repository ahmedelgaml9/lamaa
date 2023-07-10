<?php

namespace App\Models;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

use Illuminate\Database\Eloquent\Model;

class ChatBot extends Model
{
    protected $table="chat_bot";
    protected $guarded = ['id'];

    public function rules(){
        
        return [

            'question_title' => 'required|string',
            'question_body' => 'required|string',
            'answer' => 'required|string',
            'sort' => 'required|int',
            'to_customer_service' => 'required|int',
            'status' => 'required|int',

        ];
    }

}
