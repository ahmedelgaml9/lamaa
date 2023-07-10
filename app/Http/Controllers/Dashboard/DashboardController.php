<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Delegates;
use App\Models\Product;
use App\Models\City;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Appnotification;
use Carbon\Carbon;
use App\Models\Area;
use DB;
use App\Models\LogActivity as LogActivityModel;

class DashboardController extends Controller
{
    
    public function index()
    {
        
         $data['orders'] = Order::OrderBy('created_at','desc')->get()->take(5);
       
         return view('dashboard.index',$data);
    }
}
