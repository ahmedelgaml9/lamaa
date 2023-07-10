<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    
    protected $table="pages";
    protected $guarded = ['id'];
    public $translatable = ['title','content'];

    public function rules()
    {
        
        return [

            'title' => 'required|string',
            'content' => 'required',
        ];
    }

}
