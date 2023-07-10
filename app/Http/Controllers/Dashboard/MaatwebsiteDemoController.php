<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use App\Exports\OrdersExport;
use App\User;
use App\Exports\OfferExport;
use App\Models\City;
use App\Models\Appnotification;
use App\Exports\CommonExport;
use App\Exports\DelegateExport;
use Carbon\Carbon;


class MaatwebsiteDemoController extends Controller {

   
    public function exportToExcel(Request $request ,$id)
    {
    
         $data['orders'] =  Order::where('driver_id',$id)->where('status',Order::STATUS_DELIVERED)->get();
         $driverssales  = Order::where('driver_id',$id)->where('status',Order::STATUS_DELIVERED)->sum('total');
         $allorders =Order::where('driver_id',$id)->where('status',Order::STATUS_DELIVERED)->count();

         $final = [];
         $sales_results=[];
         $sales_results [] =  array('عدد الطلبات'=> $allorders > 0 ? $allorders:0 ,'اجمالى المبيعات' =>  $driverssales > 0 ?   $driverssales : 0);
       
         foreach($data['orders'] as $key => $value) {
    
              $final[] = array('رقم الطلب'=>$value->order_number ,'انشى فى'=> date('Y-m-d',strtotime($value->created_at)) ,'تاريخ التوصيل'=> $value->delivery_date ,'موبايل العميل' => $value->user->mobile,'المجموع'=> $value->total , 'المدينة'=>isset($value->city)? $value->city->name:null  ,'وسيلةالدفع'=> $value->PaymentMethod ,'الحالة'=>show_order_status($value->status));
         }
         
         $arrays = [$final,  $sales_results ];

         return Excel::download(new DelegateExport($arrays), 'المناديب.xlsx');
    }

 
}
