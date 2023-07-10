<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\HomePageSetting;
use App\Models\WebsiteService;


class WebsiteSettingsController extends Controller
{
   
    public function index()
    {
         
         $gs = HomePageSetting::find(1);
         $services = WebsiteService::all();
      
         return view('index',compact('gs','services'));
    }
 
}
