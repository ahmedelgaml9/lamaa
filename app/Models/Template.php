<?php

namespace App\Models;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    
    
    use HasTranslations;
    protected $table="templates";
    protected $guarded = ['id'];
    public $timestamps = false;
    public $translatable = ['title','content','content_sms'];

    public function rules()
    {
       
        return [

            'title.ar' => 'required|string',
            'content.ar' => 'required',
            'content_sms.ar' => 'required',
        ];
    }

    public function setTitleAttribute($value)
    {

        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

}
